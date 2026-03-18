<?php
date_default_timezone_set('Asia/Manila');
echo "Current Day: " . date('l') . "<br>";
echo "First Letter: " . strtoupper(substr(date('l'), 0, 1)) . "<br>";
echo "Current Date/Time: " . date('Y-m-d H:i:s') . "<br>";
echo "Timezone: " . date_default_timezone_get();
?>
