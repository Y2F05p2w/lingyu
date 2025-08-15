<?php
// 定义难度等级（默认低难度）
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'low';

// 处理命令执行逻辑
if (isset($_GET['command'])) {
    $command = $_GET['command'];
    $output = '';
    $error = '';

    switch ($difficulty) {
        case 'low':
            // 低难度：无防护（原始漏洞）
            echo "Executing command: " . htmlspecialchars($command) . "<br>";
            $output = shell_exec($command);
            break;

        case 'medium':
            // 中难度：基础过滤（仅转义命令分隔符）
            $filtered_cmd = str_replace([';', '|', '&', '`'], '', $command);
            echo "Executing filtered command: " . htmlspecialchars($filtered_cmd) . "<br>";
            $output = shell_exec($filtered_cmd);
            break;

        case 'high':
            // 高难度：白名单机制（仅允许预设安全命令）
            $allowed_commands = ['ls', 'pwd', 'date', 'whoami'];
            $cmd_parts = explode(' ', trim($command));
            $cmd_name = strtolower($cmd_parts[0]);

            if (in_array($cmd_name, $allowed_commands)) {
                // 仅允许执行命令本身，不允许参数
                echo "Executing safe command: " . htmlspecialchars($cmd_name) . "<br>";
                $output = shell_exec($cmd_name);
            } else {
                $error = "Error: Only allowed commands: " . implode(', ', $allowed_commands);
            }
            break;
    }
}
?>

<!-- 难度切换表单 -->
<div style="margin: 20px 0;">
    <p>当前难度：<strong><?php echo ucfirst($difficulty); ?></strong></p>
    <a href="?difficulty=low">低难度</a> | 
    <a href="?difficulty=medium">中难度</a> | 
    <a href="?difficulty=high">高难度</a>
</div>

<!-- 命令执行表单 -->
<form method="GET" action="command_injection.php">
    <input type="hidden" name="difficulty" value="<?php echo $difficulty; ?>">
    <input type="text" name="command" placeholder="Enter command" />
    <input type="submit" value="Execute Command" />
</form>

<!-- 输出展示 -->
<?php if (isset($output) && $output !== null): ?>
    <pre><?php echo htmlspecialchars($output); ?></pre>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
