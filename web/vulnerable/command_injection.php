<?php
if (isset($_GET['command'])) {
    $command = $_GET['command']; // 漏洞：直接执行用户输入的命令
    echo "Executing command: " . $command . "<br>";
    $output = shell_exec($command);
    echo "<pre>$output</pre>";
}
?>

<form method="GET" action="command_injection.php">
    <input type="text" name="command" placeholder="Enter command" />
    <input type="submit" value="Execute Command" />
</form>
