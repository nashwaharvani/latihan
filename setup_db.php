<?php
include "koneksi.php";

$sql_files = ['article.sql', 'gallery.sql', 'user.sql'];

foreach ($sql_files as $file) {
    if (file_exists($file)) {
        $sql = file_get_contents($file);
        if ($conn->multi_query($sql)) {
            echo "Successfully imported $file<br>";
             // consume all results to clear the connection related to multi_query
            while ($conn->next_result()) {;} 
        } else {
            echo "Error importing $file: " . $conn->error . "<br>";
        }
    } else {
        echo "File $file not found.<br>";
    }
}

$conn->close();
?>
