<?php
function getConnection() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'mom';  

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}


function executeQuery($sql, $params = [], $types = '') {
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}


function executeNonQuery($sql, $params = [], $types = '') {
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $success = $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    
    $stmt->close();
    $conn->close();
    
    return ['success' => $success, 'affected_rows' => $affected_rows];
}
?>
