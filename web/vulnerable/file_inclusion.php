<?php
if (isset($_GET['file'])) {
    $file = $_GET['file']; // 漏洞：直接包含用户输入的文件
    include($file);
}
?>

<form method="GET" action="file_inclusion.php">
    <input type="text" name="file" placeholder="Enter file name" />
    <input type="submit" value="Include File" />
</form>
