<?php
    $host = "agilesaptech.com";
    $user = "agiletwn_dcp";
    $pass = "agiletwn_dcp";
    $dbnm = "agiletwn_dcp";

    // Create connection
    $conn = new mysqli($host, $user, $pass, $dbnm);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>