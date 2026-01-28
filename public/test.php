<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>PHP Test</h1>";
echo "<p>PHP is working correctly.</p>";
echo "<p>Current PHP Version: " . phpversion() . "</p>";

// Try to include config to see if that fails
try {
    echo "<p>Attempting to include config/constants.php...</p>";
    include '../config/constants.php';
    echo "<p>constants.php included successfully.</p>";
    
    echo "<p>Attempting to include config/database.php...</p>";
    include '../config/database.php';
    echo "<p>database.php included successfully.</p>";
    
    if (isset($db)) {
        echo "<p>Database variable is set.</p>";
        if ($db->connect_error) {
            echo "<p style='color:red'>Database connection error: " . $db->connect_error . "</p>";
        } else {
            echo "<p style='color:green'>Database connected successfully!</p>";
        }
    }
} catch (Throwable $e) {
    echo "<p style='color:red'>Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>