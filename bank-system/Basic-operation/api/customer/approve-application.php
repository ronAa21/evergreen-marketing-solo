<?php
/**
 * Approve Account Application
 * Approves a pending application and creates the actual bank account
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!isset($data['application_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Application ID is required'
        ]);
        exit();
    }
    
    $applicationId = $data['application_id'];
    $employeeId = $_SESSION['employee_id'] ?? 1; // Default to employee ID 1 for testing
    
    // Start transaction
    $db->beginTransaction();
    
    try {
        // 1. Get application details
        $stmt = $db->prepare("
            SELECT * FROM account_applications 
            WHERE application_id = :application_id 
            AND application_status = 'pending'
        ");
        $stmt->bindParam(':application_id', $applicationId);
        $stmt->execute();
        $application = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$application) {
            throw new Exception('Application not found or already processed');
        }
        
        // 2. Get customer_id - check both application_id link and direct customer_id in account_applications
        $customerId = null;
        
        // First, try to get customer_id directly from account_applications (for existing customers)
        if (!empty($application['customer_id'])) {
            $customerId = $application['customer_id'];
            error_log("Found customer_id directly in application: " . $customerId);
        } else {
            // If not found, look up by application_id in bank_customers (for new customers)
            $stmt = $db->prepare("
                SELECT customer_id 
                FROM bank_customers 
                WHERE application_id = :application_id
            ");
            $stmt->bindParam(':application_id', $applicationId);
            $stmt->execute();
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($customer) {
                $customerId = $customer['customer_id'];
                error_log("Found customer_id via application_id link: " . $customerId);
            }
        }
        
        if (!$customerId) {
            throw new Exception('Customer record not found for this application');
        }
        
        // 3. Get account type ID
        $accountTypeBase = $application['account_type']; // e.g., 'Savings Account' or 'Checking Account'
        $stmt = $db->prepare("
            SELECT account_type_id, type_name
            FROM bank_account_types 
            WHERE type_name LIKE :type_name
        ");
        // Use LIKE to match 'Savings' with 'Savings Account' or 'Checking' with 'Checking Account'
        $searchPattern = '%' . $accountTypeBase . '%';
        $stmt->bindParam(':type_name', $searchPattern);
        $stmt->execute();
        $accountType = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$accountType) {
            throw new Exception('Account type not found: ' . $accountTypeBase);
        }
        
        $accountTypeId = $accountType['account_type_id'];
        
        // 4. Generate account number in format: SA-XXXX-YYYY or CHA-XXXX-YYYY
        // SA = Savings Account, CHA = Checking Account
        // XXXX = Random 4-digit number
        // YYYY = Current year
        // Check if it's a Savings Account (check both application's account_type and matched type_name)
        $isSavings = (stripos($accountTypeBase, 'Savings') !== false) || 
                     (stripos($accountType['type_name'], 'Savings') !== false);
        $prefix = $isSavings ? 'SA' : 'CHA';
        $randomNumber = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $year = date('Y');
        $accountNumber = $prefix . '-' . $randomNumber . '-' . $year;
        
        // Check if account number already exists (very unlikely but good practice)
        $maxAttempts = 10;
        $attempt = 0;
        while ($attempt < $maxAttempts) {
            $checkStmt = $db->prepare("SELECT COUNT(*) as count FROM customer_accounts WHERE account_number = :account_number");
            $checkStmt->bindParam(':account_number', $accountNumber);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] == 0) {
                break; // Account number is unique
            }
            
            // Generate new random number
            $randomNumber = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $accountNumber = $prefix . '-' . $randomNumber . '-' . $year;
            $attempt++;
        }
        
        // 5. Create customer_accounts record
        $stmt = $db->prepare("
            INSERT INTO customer_accounts (
                account_number,
                customer_id,
                account_type_id,
                is_locked,
                created_at,
                created_by_employee_id,
                account_status
            ) VALUES (
                :account_number,
                :customer_id,
                :account_type_id,
                0,
                NOW(),
                :employee_id,
                'active'
            )
        ");
        
        $stmt->bindParam(':account_number', $accountNumber);
        $stmt->bindParam(':customer_id', $customerId);
        $stmt->bindParam(':account_type_id', $accountTypeId);
        $stmt->bindParam(':employee_id', $employeeId);
        $stmt->execute();
        
        $accountId = $db->lastInsertId();
        
        // 6. Link account to customer in customer_linked_accounts
        $stmt = $db->prepare("
            INSERT INTO customer_linked_accounts (
                customer_id,
                account_id,
                is_active
            ) VALUES (
                :customer_id,
                :account_id,
                1
            )
        ");
        $stmt->bindParam(':customer_id', $customerId);
        $stmt->bindParam(':account_id', $accountId);
        $stmt->execute();
        
        // 7. Create customer_profiles record with personal details from application
        // Check if profile already exists
        $checkStmt = $db->prepare("SELECT profile_id FROM customer_profiles WHERE customer_id = :customer_id");
        $checkStmt->bindParam(':customer_id', $customerId);
        $checkStmt->execute();
        
        if (!$checkStmt->fetch()) {
            // Get gender_id from genders table
            $genderId = null;
            if (!empty($application['gender'])) {
                $genderStmt = $db->prepare("SELECT gender_id FROM genders WHERE gender_name = :gender_name");
                $genderStmt->bindParam(':gender_name', $application['gender']);
                $genderStmt->execute();
                $genderResult = $genderStmt->fetch(PDO::FETCH_ASSOC);
                $genderId = $genderResult['gender_id'] ?? null;
            }
            
            $stmt = $db->prepare("
                INSERT INTO customer_profiles (
                    customer_id,
                    gender_id,
                    date_of_birth,
                    marital_status,
                    nationality,
                    occupation,
                    income_range
                ) VALUES (
                    :customer_id,
                    :gender_id,
                    :date_of_birth,
                    :marital_status,
                    :nationality,
                    :occupation,
                    :income_range
                )
            ");
            $stmt->bindParam(':customer_id', $customerId);
            $stmt->bindParam(':gender_id', $genderId);
            $stmt->bindParam(':date_of_birth', $application['date_of_birth']);
            $stmt->bindParam(':marital_status', $application['civil_status']);
            $stmt->bindParam(':nationality', $application['nationality']);
            $stmt->bindParam(':occupation', $application['occupation']);
            $incomeRange = $application['annual_income'] ? number_format($application['annual_income'], 0) : null;
            $stmt->bindParam(':income_range', $incomeRange);
            $stmt->execute();
            error_log("Created customer_profiles record for customer_id: " . $customerId);
        }
        
        // 8. Create addresses record with address details from application
        // Check if address already exists
        $checkStmt = $db->prepare("SELECT address_id FROM addresses WHERE customer_id = :customer_id AND is_primary = 1");
        $checkStmt->bindParam(':customer_id', $customerId);
        $checkStmt->execute();
        
        if (!$checkStmt->fetch()) {
            $stmt = $db->prepare("
                INSERT INTO addresses (
                    customer_id,
                    address_line,
                    barangay_id,
                    city_id,
                    province_id,
                    postal_code,
                    address_type,
                    is_primary
                ) VALUES (
                    :customer_id,
                    :address_line,
                    :barangay_id,
                    :city_id,
                    :province_id,
                    :postal_code,
                    'home',
                    1
                )
            ");
            $stmt->bindParam(':customer_id', $customerId);
            $stmt->bindParam(':address_line', $application['street_address']);
            $stmt->bindParam(':barangay_id', $application['barangay_id']);
            $stmt->bindParam(':city_id', $application['city_id']);
            $stmt->bindParam(':province_id', $application['province_id']);
            $stmt->bindParam(':postal_code', $application['postal_code']);
            $stmt->execute();
            error_log("Created addresses record for customer_id: " . $customerId);
        }
        
        // 9. Create emails record if email exists
        if (!empty($application['email'])) {
            $checkStmt = $db->prepare("SELECT email_id FROM emails WHERE customer_id = :customer_id AND email = :email");
            $checkStmt->bindParam(':customer_id', $customerId);
            $checkStmt->bindParam(':email', $application['email']);
            $checkStmt->execute();
            
            if (!$checkStmt->fetch()) {
                $stmt = $db->prepare("
                    INSERT INTO emails (customer_id, email, is_primary)
                    VALUES (:customer_id, :email, 1)
                ");
                $stmt->bindParam(':customer_id', $customerId);
                $stmt->bindParam(':email', $application['email']);
                $stmt->execute();
                error_log("Created emails record for customer_id: " . $customerId);
            }
        }
        
        // 10. Create phones record if phone exists
        if (!empty($application['phone_number'])) {
            $checkStmt = $db->prepare("SELECT phone_id FROM phones WHERE customer_id = :customer_id AND phone_number = :phone_number");
            $checkStmt->bindParam(':customer_id', $customerId);
            $checkStmt->bindParam(':phone_number', $application['phone_number']);
            $checkStmt->execute();
            
            if (!$checkStmt->fetch()) {
                $stmt = $db->prepare("
                    INSERT INTO phones (customer_id, phone_number, phone_type, is_primary)
                    VALUES (:customer_id, :phone_number, 'mobile', 1)
                ");
                $stmt->bindParam(':customer_id', $customerId);
                $stmt->bindParam(':phone_number', $application['phone_number']);
                $stmt->execute();
                error_log("Created phones record for customer_id: " . $customerId);
            }
        }
        
        // 11. Update bank_customers with is_active = 1 (now approved)
        $stmt = $db->prepare("
            UPDATE bank_customers 
            SET is_active = 1, is_verified = 1
            WHERE customer_id = :customer_id
        ");
        $stmt->bindParam(':customer_id', $customerId);
        $stmt->execute();
        
        // 12. Update application status to approved
        $stmt = $db->prepare("
            UPDATE account_applications 
            SET application_status = 'approved',
                reviewed_at = NOW(),
                reviewed_by_employee_id = :employee_id
            WHERE application_id = :application_id
        ");
        $stmt->bindParam(':employee_id', $employeeId);
        $stmt->bindParam(':application_id', $applicationId);
        $stmt->execute();
        
        // Commit transaction
        $db->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Application approved successfully',
            'account_number' => $accountNumber,
            'account_id' => $accountId,
            'customer_id' => $customerId
        ]);
        
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Database error in approve-application.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error in approve-application.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}