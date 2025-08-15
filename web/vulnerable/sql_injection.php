<?php
include '../db_config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id']; // 漏洞：SQL注入
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["name"]. "<br>";
        }
    } else {
        echo "0 results";
    }
}

$conn->close();
?>
