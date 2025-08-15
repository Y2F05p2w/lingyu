<?php
// 数据库连接配置
$host = "db";
$username = "root";
$password = "Lifei23";
$dbname = "target_db";

// 初始化连接
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 处理难度等级（默认低难度）
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'low';
$showSource = isset($_GET['show_source']) ? true : false;
$id = isset($_GET['id']) ? $_GET['id'] : 1;
$result = null;
$error = "";
$sql = "";

// 根据难度处理SQL查询
switch ($difficulty) {
    case 'low':
        // 低难度：无防护，直接拼接SQL
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $conn->query($sql);
        break;
        
    case 'medium':
        // 中难度：基础过滤（转义单引号）
        $filtered_id = str_replace("'", "\'", $id);
        $sql = "SELECT * FROM users WHERE id = '$filtered_id'";
        $result = $conn->query($sql);
        break;
        
    case 'high':
        // 高难度：使用预处理语句（参数化查询）
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id); // 强制整数类型
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        break;
}

// 获取当前难度对应的源码片段
function getSourceCode($difficulty) {
    $source = [
        'low' => <<<HTML
// 低难度：无任何防护措施
\$id = \$_GET['id'];
\$sql = "SELECT * FROM users WHERE id = \$id";
\$result = \$conn->query(\$sql);
HTML
        ,
        'medium' => <<<HTML
// 中难度：基础过滤（仅转义单引号）
\$id = \$_GET['id'];
\$filtered_id = str_replace("'", "\'", \$id);
\$sql = "SELECT * FROM users WHERE id = '\$filtered_id'";
\$result = \$conn->query(\$sql);
HTML
        ,
        'high' => <<<HTML
// 高难度：参数化查询（预处理语句）
\$id = \$_GET['id'];
\$stmt = \$conn->prepare("SELECT * FROM users WHERE id = ?");
\$stmt->bind_param("i", \$id); // 强制整数类型
\$stmt->execute();
\$result = \$stmt->get_result();
\$stmt->close();
HTML
    ];
    return $source[$difficulty];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SQL注入演示（带难度等级和源码）</title>
    <style>
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .difficulty { margin: 15px 0; padding: 10px; background: #f0f0f0; }
        .result { margin: 15px 0; padding: 10px; border: 1px solid #ccc; }
        .error { color: red; }
        .source-code { 
            margin: 15px 0; 
            padding: 15px; 
            background: #f8f8f8; 
            border: 1px solid #ddd; 
            border-radius: 4px;
            font-family: monospace;
            white-space: pre;
            overflow-x: auto;
        }
        .btn {
            padding: 6px 12px;
            margin-left: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SQL注入漏洞演示</h1>
        
        <!-- 难度选择和源码按钮 -->
        <div class="difficulty">
            <form method="GET" action="sql_injection.php">
                <label for="difficulty">选择难度：</label>
                <select name="difficulty" id="difficulty" onchange="this.form.submit()">
                    <option value="low" <?php echo $difficulty == 'low' ? 'selected' : ''; ?>>低难度（无防护）</option>
                    <option value="medium" <?php echo $difficulty == 'medium' ? 'selected' : ''; ?>>中难度（基础过滤）</option>
                    <option value="high" <?php echo $difficulty == 'high' ? 'selected' : ''; ?>>高难度（参数化查询）</option>
                </select>
                
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <button type="submit" name="show_source" value="1" class="btn">
                    <?php echo $showSource ? '隐藏源码' : '显示源码'; ?>
                </button>
                
                <div style="margin-top: 10px;">
                    <label for="id">用户ID：</label>
                    <input type="text" name="id" id="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="submit" value="查询">
                </div>
            </form>
        </div>
        
        <!-- 源码显示区域 -->
        <?php if ($showSource): ?>
            <div>
                <h3>当前难度的核心代码：</h3>
                <div class="source-code">
                    <?php echo htmlspecialchars(getSourceCode($difficulty)); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- 显示当前查询和结果 -->
        <div>
            <p>当前执行的SQL：
                <?php 
                if ($difficulty != 'high') {
                    echo "<code>" . htmlspecialchars($sql) . "</code>";
                } else {
                    echo "<code>SELECT * FROM users WHERE id = ?</code>（参数化查询）";
                }
                ?>
            </p>
            
            <div class="result">
                <?php
                if ($error) {
                    echo "<p class='error'>$error</p>";
                } elseif ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "ID: " . $row["id"] . "<br>";
                        echo "姓名: " . $row["name"] . "<br>";
                        echo "邮箱: " . $row["email"] . "<br><br>";
                    }
                } elseif ($result) {
                    echo "没有找到匹配的记录";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>

