<?php
// Populate provinces table with Philippine provinces
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Philippine provinces data
$provinces = [
    'Abra', 'Agusan del Norte', 'Agusan del Sur', 'Aklan', 'Albay', 'Antique', 'Apayao', 'Aurora',
    'Basilan', 'Bataan', 'Batanes', 'Batangas', 'Benguet', 'Biliran', 'Bohol', 'Bukidnon',
    'Bulacan', 'Cagayan', 'Camarines Norte', 'Camarines Sur', 'Camiguin', 'Capiz', 'Catanduanes', 'Cavite',
    'Cebu', 'Compostela Valley', 'Cotabato', 'Davao de Oro', 'Davao del Norte', 'Davao del Sur', 'Davao Occidental', 'Davao Oriental',
    'Dinagat Islands', 'Eastern Samar', 'Guimaras', 'Ifugao', 'Ilocos Norte', 'Ilocos Sur', 'Iloilo', 'Isabela',
    'Kalinga', 'La Union', 'Laguna', 'Lanao del Norte', 'Lanao del Sur', 'Leyte', 'Maguindanao', 'Marinduque',
    'Masbate', 'Misamis Occidental', 'Misamis Oriental', 'Mountain Province', 'Negros Occidental', 'Negros Oriental', 'Northern Samar', 'Nueva Ecija',
    'Nueva Vizcaya', 'Occidental Mindoro', 'Oriental Mindoro', 'Palawan', 'Pampanga', 'Pangasinan', 'Quezon', 'Quirino',
    'Rizal', 'Romblon', 'Samar', 'Sarangani', 'Siquijor', 'Sorsogon', 'South Cotabato', 'Southern Leyte',
    'Sultan Kudarat', 'Sulu', 'Surigao del Norte', 'Surigao del Sur', 'Tarlac', 'Tawi-Tawi', 'Zambales', 'Zamboanga del Norte',
    'Zamboanga del Sur', 'Zamboanga Sibugay'
];

echo "<h2>Populating Provinces Table</h2>";

// Clear existing data first
$conn->query("DELETE FROM provinces");
echo "<p>Cleared existing provinces data</p>";

// Insert provinces
$stmt = $conn->prepare("INSERT INTO provinces (province_name, country) VALUES (?, 'Philippines')");
$success_count = 0;
$error_count = 0;

foreach ($provinces as $province) {
    $stmt->bind_param("s", $province);
    if ($stmt->execute()) {
        $success_count++;
    } else {
        $error_count++;
        echo "<p style='color: red;'>Error inserting $province: " . $stmt->error . "</p>";
    }
}

$stmt->close();

echo "<p style='color: green;'>✅ Successfully inserted $success_count provinces</p>";
if ($error_count > 0) {
    echo "<p style='color: red;'>❌ Failed to insert $error_count provinces</p>";
}

// Verify the data
$result = $conn->query("SELECT COUNT(*) as count FROM provinces");
$count = $result->fetch_assoc()['count'];
echo "<p>Total provinces in database: $count</p>";

echo "<h3>Sample data:</h3>";
$result = $conn->query("SELECT * FROM provinces LIMIT 10");
echo "<table border='1'><tr><th>ID</th><th>Province Name</th><th>Country</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['province_id'] . "</td><td>" . $row['province_name'] . "</td><td>" . $row['country'] . "</td></tr>";
}
echo "</table>";

$conn->close();
echo "<p style='color: green;'><strong>✅ Provinces table populated successfully!</strong></p>";
echo "<p><a href='signup.php'>Test the signup form now</a></p>";
?>
