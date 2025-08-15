<?php
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'low';
$message = isset($_GET['message']) ? $_GET['message'] : '';
$output = '';

switch ($difficulty) {
    case 'low':
        // 低难度：直接输出
        $output = $message;
        break;
    case 'medium':
        // 中难度：过滤<script>标签
        $output = str_replace('<script>', '', $message);
        break;
    case 'high':
        // 高难度：完全HTML转义
        $output = htmlspecialchars($message);
        break;
}
?>
<!-- 下拉框和表单结构与SQL注入页面一致 -->
