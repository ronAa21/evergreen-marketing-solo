<?php
/**
 * Script to fix duplicate key definitions in bankingdb(2).sql
 * This will remove duplicate ADD KEY and ADD UNIQUE KEY statements
 */

$inputFile = __DIR__ . '/sql/bankingdb(2).sql';
$outputFile = __DIR__ . '/sql/bankingdb_fixed.sql';

echo "Reading SQL file...\n";
$content = file_get_contents($inputFile);

if ($content === false) {
    die("Error: Could not read file $inputFile\n");
}

echo "Fixing duplicate key definitions...\n";

// Split content into lines
$lines = explode("\n", $content);
$fixedLines = [];
$previousLine = '';

foreach ($lines as $line) {
    $trimmedLine = trim($line);
    $trimmedPrevious = trim($previousLine);
    
    // Skip if this line is identical to the previous line and contains ADD KEY or ADD UNIQUE KEY
    if ($trimmedLine === $trimmedPrevious && 
        (strpos($trimmedLine, 'ADD KEY') !== false || 
         strpos($trimmedLine, 'ADD UNIQUE KEY') !== false)) {
        echo "Removing duplicate: $trimmedLine\n";
        continue;
    }
    
    $fixedLines[] = $line;
    $previousLine = $line;
}

// Join lines back together
$fixedContent = implode("\n", $fixedLines);

echo "Writing fixed SQL file...\n";
$result = file_put_contents($outputFile, $fixedContent);

if ($result === false) {
    die("Error: Could not write file $outputFile\n");
}

echo "Success! Fixed SQL file saved to: $outputFile\n";
echo "Total lines processed: " . count($lines) . "\n";
echo "Duplicates removed: " . (count($lines) - count($fixedLines)) . "\n";
echo "\nTo use the fixed file:\n";
echo "1. Drop your current database (if needed)\n";
echo "2. Import the fixed file: bankingdb_fixed.sql\n";
?>
