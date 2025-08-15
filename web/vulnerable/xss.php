<?php
if (isset($_GET['message'])) {
    $message = $_GET['message']; // 漏洞：未对输入进行转义
    echo "<h1>Message: $message</h1>";
}
?>
<form method="GET" action="xss.php">
    <input type="text" name="message" />
    <input type="submit" value="Submit" />
</form>
