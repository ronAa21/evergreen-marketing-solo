<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Populating Cities and Barangays</h2>";

// Sample cities data for major provinces (abbreviated for demo)
$cities_data = [
    // Metro Manila (special case - using province_id 1 as placeholder)
    1 => ['Manila', 'Quezon City', 'Caloocan', 'Makati', 'Pasay', 'Pasig', 'Mandaluyong', 'San Juan', 'Taguig', 'Paranaque'],
    
    // Abra (province_id 1)
    1 => ['Bangued', 'Boliney', 'Bucay', 'Bucloc', 'Daguioman', 'Danglas', 'Dolores', 'La Paz', 'Lacub', 'Lagangilang', 'Lagayan', 'Langiden', 'Licuan-Baaba', 'Luba', 'Malibcong', 'Manabo', 'Peñarrubia', 'Pidigan', 'Pilar', 'Sallapadan', 'San Isidro', 'San Juan', 'San Quintin', 'Tayum', 'Tineg', 'Villaviciosa'],
    
    // Agusan del Norte (province_id 2)
    2 => ['Butuan City', 'Cabadbaran City', 'Buenavista', 'Carmen', 'Jabonga', 'Kitcharao', 'Las Nieves', 'Magallanes', 'Nasipit', 'Remedios T. Romualdez', 'Santiago', 'Tubay'],
    
    // Agusan del Sur (province_id 3)
    3 => ['Bayugan City', 'Bunawan', 'Esperanza', 'La Paz', 'Loreto', 'Prosperidad', 'Rosario', 'San Francisco', 'San Luis', 'Santa Josefa', 'Talacogon', 'Trento', 'Veruela'],
    
    // Aklan (province_id 4)
    4 => ['Kalibo', 'Banga', 'Batan', 'Buruanga', 'Ibajay', 'Lezo', 'Libacao', 'Madalag', 'Makato', 'Malay', 'Malinao', 'Nabas', 'New Washington', 'Numancia', 'Tangalan'],
    
    // Albay (province_id 5)
    5 => ['Legazpi City', 'Ligao City', 'Tabaco City', 'Bacacay', 'Camalig', 'Daraga', 'Guinobatan', 'Jovellar', 'Libon', 'Malilipot', 'Malinao', 'Manito', 'Oas', 'Pio Duran', 'Polangui', 'Rapu-Rapu', 'Santo Domingo', 'Tiwi'],
    
    // Antique (province_id 6)
    6 => ['San Jose de Buenavista', 'Anini-y', 'Barbaza', 'Belison', 'Bugasong', 'Caluya', 'Culasi', 'Hamtic', 'Laua-an', 'Libertad', 'Pandan', 'Patnongon', 'San Remigio', 'Sebaste', 'Sibalom', 'Tibiao', 'Tobias Fornier', 'Valderrama'],
    
    // Apayao (province_id 7)
    7 => ['Calanasan', 'Conner', 'Flora', 'Kabugao', 'Luna', 'Pudtol', 'Santa Marcela'],
    
    // Aurora (province_id 8)
    8 => ['Baler', 'Casiguran', 'Dilasag', 'Dinalupihan', 'General Nakar', 'Luis', 'Maria Aurora', 'San Luis'],
    
    // Basilan (province_id 9)
    9 => ['Lamitan City', 'Akbar', 'Al-Barka', 'Hadji Mohammad Ajul', 'Hadji Muhtamad', 'Lantawan', 'Maluso', 'Sumisip', 'Tipo-Tipo', 'Tuburan', 'Ungkaya Pukan'],
    
    // Bataan (province_id 10)
    10 => ['Balanga City', 'Abucay', 'Bagac', 'Dinalupihan', 'Hermosa', 'Limay', 'Mariveles', 'Morong', 'Orani', 'Orion', 'Pilar', 'Samal'],
    
    // Batanes (province_id 11)
    11 => ['Basco', 'Itbayat', 'Ivana', 'Mahatao', 'Sabtang', 'Uyugan'],
    
    // Batangas (province_id 12)
    12 => ['Batangas City', 'Lipa City', 'Tanauan City', 'Calaca', 'Calauan', 'Cuenca', 'Ibaan', 'Laurel', 'Lemery', 'Lian', 'Lobo', 'Mabini', 'Malvar', 'Mataasnakahoy', 'Nasugbu', 'Padre Garcia', 'Rosario', 'San Jose', 'San Juan', 'San Luis', 'San Nicolas', 'San Pascual', 'Santa Teresita', 'Santo Tomas', 'Taal', 'Talisay', 'Taysan', 'Tingloy', 'Tuy'],
    
    // Benguet (province_id 13)
    13 => ['Baguio City', 'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 'Sablan', 'Tuba', 'Tublay'],
    
    // Biliran (province_id 14)
    14 => ['Naval', 'Almeria', 'Biliran', 'Cabucgayan', 'Caibiran', 'Culaba', 'Kawayan', 'Maripipi', 'Naval'],
    
    // Bohol (province_id 15)
    15 => ['Tagbilaran City', 'Alburquerque', 'Alicia', 'Anda', 'Antequera', 'Baclayon', 'Balilihan', 'Batuan', 'Bilar', 'Buenavista', 'Calape', 'Candijay', 'Carmen', 'Catigbian', 'Clarin', 'Corella', 'Cortes', 'Dagohoy', 'Danao', 'Dimiao', 'Duero', 'Garcia Hernandez', 'Getafe', 'Guindulman', 'Inabanga', 'Jagna', 'Lila', 'Loay', 'Loboc', 'Loon', 'Mabini', 'Maribojoc', 'Panglao', 'Pilar', 'President Carlos P. Garcia', 'Sagbayan', 'San Isidro', 'San Miguel', 'Sevilla', 'Sierra Bullones', 'Sikatuna', 'Talibon', 'Trinidad', 'Tubigon', 'Ubay', 'Valencia'],
    
    // Bukidnon (province_id 16)
    16 => ['Malaybalay City', 'Valencia City', 'Baungon', 'Cabanglasan', 'Damulog', 'Dangcagan', 'Don Carlos', 'Impasugong', 'Kadingilan', 'Kalilangan', 'Kibawe', 'Kitaotao', 'Lantapan', 'Libona', 'Malitbog', 'Manolo Fortich', 'Maramag', 'Pangantucan', 'Quezon', 'San Fernando', 'Sumilao', 'Talakag', 'Talumingan'],
    
    // Bulacan (province_id 17)
    17 => ['Malolos City', 'Meycauayan City', 'San Jose del Monte City', 'Angat', 'Balagtas', 'Baliuag', 'Bocaue', 'Bulacan', 'Calumpit', 'Candaba', 'Doña Remedios Trinidad', 'Guiguinto', 'Hagonoy', 'Marilao', 'Norzagaray', 'Obando', 'Pandi', 'Paombong', 'Plaridel', 'Pulilan', 'San Ildefonso', 'San Miguel', 'San Rafael', 'Santa Maria'],
    
    // Cagayan (province_id 18)
    18 => ['Tuguegarao City', 'Aparri', 'Baggao', 'Ballesteros', 'Buguey', 'Calayan', 'Camalaniugan', 'Claveria', 'Enrile', 'Gattaran', 'Gonzaga', 'Iguig', 'Lal-lo', 'Lasam', 'Pamplona', 'Peñablanca', 'Piat', 'Rizal', 'Sanchez-Mira', 'Santa Ana', 'Santa Praxedes', 'Santa Teresita', 'Santo Niño', 'Solana', 'Tuao', 'Faire'],
    
    // Camarines Norte (province_id 19)
    19 => ['Daet', 'Basud', 'Capalonga', 'Jose Panganiban', 'Labo', 'Mercedes', 'Paracale', 'San Lorenzo Ruiz', 'San Vicente', 'Santa Elena', 'Talisay', 'Vinzons'],
    
    // Camarines Sur (province_id 20)
    20 => ['Naga City', 'Iriga City', 'Legazpi City', 'Pili', 'Baao', 'Balatan', 'Bato', 'Bombon', 'Buhi', 'Bula', 'Cabusao', 'Calabanga', 'Camaligan', 'Canaman', 'Caramoan', 'Del Gallego', 'Gainza', 'Garchitorena', 'Goa', 'Lagonoy', 'Libmanan', 'Lupi', 'Magarao', 'Milaor', 'Minalabac', 'Nabua', 'Ocampo', 'Pasacao', 'Ragay', 'Reyes', 'San Fernando', 'San Jose', 'Sipocot', 'Siruma', 'Tigaon', 'Tinambac'],
    
    // Camiguin (province_id 21)
    21 => ['Mambajao', 'Catarman', 'Guinsiliban', 'Mahinog', 'Sagay'],
    
    // Capiz (province_id 22)
    22 => ['Roxas City', 'Cuartero', 'Dao', 'Dumalag', 'Dumarao', 'Ivisan', 'Jamindan', 'Ma-ayon', 'Mambusao', 'Panay', 'Panitan', 'Pilar', 'Pontevedra', 'President Roxas', 'Sigma', 'Tapaz'],
    
    // Catanduanes (province_id 23)
    23 => ['Virac', 'Bagamanoc', 'Baras', 'Bato', 'Caramoran', 'Gigmoto', 'Pandan', 'Panganiban', 'San Andres', 'San Miguel', 'Viga', 'Caramoran'],
    
    // Cavite (province_id 24)
    24 => ['Trece Martires City', 'Bacoor City', 'Cavite City', 'Dasmariñas City', 'General Trias City', 'Imus City', 'Tagaytay City', 'Alfonso', 'Amadeo', 'Bailen', 'Carmona', 'Gen. Mariano Alvarez', 'Gen. Emilio Aguinaldo', 'Indang', 'Kawit', 'Magallanes', 'Maragondon', 'Mendez', 'Naic', 'Noveleta', 'Rosario', 'Silang', 'Tanza', 'Ternate'],
    
    // Cebu (province_id 25)
    25 => ['Cebu City', 'Lapu-Lapu City', 'Mandaue City', 'Bogo City', 'Carcar City', 'Naga City', 'Talisay City', 'Toledo City', 'Alcantara', 'Alcoy', 'Alegria', 'Aloguinsan', 'Argao', 'Asturias', 'Badian', 'Balamban', 'Bantayan', 'Barili', 'Boljoon', 'Borbon', 'Carmen', 'Catmon', 'Compostela', 'Consolacion', 'Cordova', 'Daanbantayan', 'Dalaguete', 'Danao City', 'Dumanjug', 'Ginatilan', 'Liloan', 'Madridejos', 'Malabuyoc', 'Medellin', 'Minglanilla', 'Moalboal', 'Oslob', 'Pilar', 'Pinamungahan', 'Ronda', 'Samboan', 'San Fernando', 'San Francisco', 'San Remigio', 'Santa Fe', 'Santander', 'Sibonga', 'Sogod', 'Tabogon', 'Tabuelan', 'Tuburan', 'Tudela'],
];

// Clear existing data
$conn->query("DELETE FROM cities");
$conn->query("DELETE FROM barangays");
echo "<p>Cleared existing cities and barangays data</p>";

// Insert cities
$stmt = $conn->prepare("INSERT INTO cities (city_name, province_id) VALUES (?, ?)");
$city_count = 0;

foreach ($cities_data as $province_id => $cities) {
    foreach ($cities as $city_name) {
        $stmt->bind_param("si", $city_name, $province_id);
        if ($stmt->execute()) {
            $city_count++;
        } else {
            echo "<p style='color: red;'>Error inserting city $city_name: " . $stmt->error . "</p>";
        }
    }
}
$stmt->close();

echo "<p style='color: green;'>✅ Successfully inserted $city_count cities</p>";

// Insert sample barangays for some cities
$barangays_data = [
    // Manila barangays (sample)
    1 => ['Tondo', 'Binondo', 'Quiapo', 'Sampaloc', 'San Miguel', 'Santa Cruz', 'Santa Mesa', 'San Andres', 'Paco', 'Pandacan', 'Port Area', 'Ermita', 'Intramuros', 'Malate', 'San Juan'],
    
    // Quezon City barangays (sample)
    2 => ['Bagong Pag-asa', 'Bahay Toro', 'Bahay Toro Proper', 'Balingasa', 'Balintawak', 'Batasan', 'Culiat', 'Damar', 'Damayan', 'Doña Imelda', 'Fairview', 'Greater Lagro', 'Holy Spirit', 'Kaligayahan', 'Kamuning', 'Kaunlaran', 'Krus na Ligas', 'Laging Handa', 'Mabuhay', 'Manresa', 'Masambong', 'Nayong Kanluran', 'Paang Bundok', 'Paltok', 'Pasong Tamo', 'Pinagkaisahan', 'Project 6', 'Ramon Magsaysay', 'Sacred Heart', 'San Antonio', 'San Bartolome', 'San Isidro', 'San Roque', 'Santa Cruz', 'Santa Monica', 'Santo Domingo', 'Santo Niño', 'Tandang Sora', 'Tatalon', 'Teachers Village', 'UP Campus', 'Vasra', 'West Triangle'],
    
    // Caloocan barangays (sample)
    3 => ['Bagong Barrio', 'Bagong Silang', 'Bangkal', 'Barangay 128', 'Barangay 129', 'Barangay 130', 'Barangay 131', 'Barangay 132', 'Barangay 133', 'Barangay 134', 'Barangay 135', 'Barangay 136', 'Barangay 137', 'Barangay 138', 'Barangay 139', 'Barangay 140', 'Barangay 141', 'Barangay 142', 'Barangay 143', 'Barangay 144', 'Barangay 145', 'Barangay 146', 'Barangay 147', 'Barangay 148', 'Barangay 149', 'Barangay 150', 'Barangay 151', 'Barangay 152', 'Barangay 153', 'Barangay 154', 'Barangay 155', 'Barangay 156', 'Barangay 157', 'Barangay 158', 'Barangay 159', 'Barangay 160', 'Barangay 161', 'Barangay 162', 'Barangay 163', 'Barangay 164', 'Barangay 165', 'Barangay 166', 'Barangay 167', 'Barangay 168', 'Barangay 169', 'Barangay 170', 'Barangay 171', 'Barangay 172', 'Barangay 173', 'Barangay 174', 'Barangay 175', 'Barangay 176', 'Barangay 177', 'Barangay 178', 'Barangay 179', 'Barangay 180', 'Barangay 181', 'Barangay 182', 'Barangay 183', 'Barangay 184', 'Barangay 185', 'Barangay 186', 'Barangay 187', 'Barangay 188'],
];

// Insert barangays
$stmt = $conn->prepare("INSERT INTO barangays (barangay_name, city_id, zip_code) VALUES (?, ?, ?)");
$barangay_count = 0;

foreach ($barangays_data as $city_id => $barangays) {
    foreach ($barangays as $barangay_name) {
        $zip_code = rand(1000, 9999); // Random zip code for demo
        $stmt->bind_param("sis", $barangay_name, $city_id, $zip_code);
        if ($stmt->execute()) {
            $barangay_count++;
        } else {
            echo "<p style='color: red;'>Error inserting barangay $barangay_name: " . $stmt->error . "</p>";
        }
    }
}
$stmt->close();

echo "<p style='color: green;'>✅ Successfully inserted $barangay_count barangays</p>";

// Verify data
$cities_result = $conn->query("SELECT COUNT(*) as count FROM cities");
$cities_count = $cities_result->fetch_assoc()['count'];

$barangays_result = $conn->query("SELECT COUNT(*) as count FROM barangays");
$barangays_count = $barangays_result->fetch_assoc()['count'];

echo "<h3>Summary:</h3>";
echo "<p>Total provinces: 82</p>";
echo "<p>Total cities: $cities_count</p>";
echo "<p>Total barangays: $barangays_count</p>";

echo "<h3>Sample data:</h3>";
echo "<h4>Sample cities:</h4>";
$result = $conn->query("SELECT c.city_id, c.city_name, p.province_name FROM cities c JOIN provinces p ON c.province_id = p.province_id LIMIT 10");
echo "<table border='1'><tr><th>City ID</th><th>City Name</th><th>Province</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['city_id'] . "</td><td>" . $row['city_name'] . "</td><td>" . $row['province_name'] . "</td></tr>";
}
echo "</table>";

echo "<h4>Sample barangays:</h4>";
$result = $conn->query("SELECT b.barangay_id, b.barangay_name, c.city_name, b.zip_code FROM barangays b JOIN cities c ON b.city_id = c.city_id LIMIT 10");
echo "<table border='1'><tr><th>Barangay ID</th><th>Barangay Name</th><th>City</th><th>Zip Code</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['barangay_id'] . "</td><td>" . $row['barangay_name'] . "</td><td>" . $row['city_name'] . "</td><td>" . $row['zip_code'] . "</td></tr>";
}
echo "</table>";

$conn->close();
echo "<p style='color: green;'><strong>✅ Location data populated successfully!</strong></p>";
echo "<p><a href='signup.php'>Test the signup form now</a></p>";
?>
