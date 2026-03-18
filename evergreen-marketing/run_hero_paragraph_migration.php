<?php
/**
 * Migration Script: Add hero_paragraph and update hero_title
 * Run this once to add the hero_paragraph field and update hero_title in the database
 */

include('db_connect.php');

echo "<h2>Running Migration: Add hero_paragraph & Update hero_title</h2>";

// Add hero_paragraph
$sql1 = "INSERT IGNORE INTO `site_content` (`content_key`, `content_value`, `content_type`) VALUES
('hero_paragraph', 'Secure financial solutions for every stage of your life journey. Invest, save, and achieve your goals with Evergreen.', 'text')";

// Update hero_title to new default
$sql2 = "UPDATE `site_content` SET `content_value` = 'Banking that grows with you' WHERE `content_key` = 'hero_title' AND `content_value` = 'Secure. Invest. Achieve.'";

$success = true;

if ($conn->query($sql1) === TRUE) {
    echo "<p style='color: green;'>✓ hero_paragraph content key added successfully.</p>";
} else {
    echo "<p style='color: red;'>✗ Error adding hero_paragraph: " . $conn->error . "</p>";
    $success = false;
}

if ($conn->query($sql2) === TRUE) {
    if ($conn->affected_rows > 0) {
        echo "<p style='color: green;'>✓ hero_title updated successfully.</p>";
    } else {
        echo "<p style='color: orange;'>⚠ hero_title was already updated or has custom value.</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Error updating hero_title: " . $conn->error . "</p>";
    $success = false;
}

if ($success) {
    echo "<br><p style='color: green; font-weight: bold;'>✓ Migration completed successfully!</p>";
    echo "<p>You can now edit these contents from the Admin Dashboard → Manage Content:</p>";
    echo "<ul>";
    echo "<li><strong>Hero Title:</strong> The main heading on the hero section</li>";
    echo "<li><strong>Hero Paragraph:</strong> The description text below the title</li>";
    echo "</ul>";
}

$conn->close();

echo "<br><br><a href='admin_dashboard.php' style='padding: 10px 20px; background: #003631; color: white; text-decoration: none; border-radius: 5px;'>Go to Admin Dashboard</a>";
echo " ";
echo "<a href='viewing.php' style='padding: 10px 20px; background: #F1B24A; color: #003631; text-decoration: none; border-radius: 5px;'>View Website</a>";
?>
