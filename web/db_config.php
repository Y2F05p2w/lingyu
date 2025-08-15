<?php
$host = "localhost";
$username = "root";
$password = "Lifei23";
$dbname = "target_db";

// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);
// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
?>
