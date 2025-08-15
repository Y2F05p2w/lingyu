<?php
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'low';
$message = '';

if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $filename = $_FILES['file']['name'];
    $target = "uploads/" . $filename;
    
    switch ($difficulty) {
        case 'low':
            // 低难度：直接上传
            move_uploaded_file($_FILES['file']['tmp_name'], $target);
            $message = "文件上传成功: $filename";
            break;
        case 'medium':
            // 中难度：检查文件扩展名
            $allowed = ['jpg', 'png', 'gif'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($ext, $allowed)) {
                move_uploaded_file($_FILES['file']['tmp_name'], $target);
                $message = "文件上传成功: $filename";
            } else {
                $message = "仅允许上传图片文件（jpg/png/gif）";
            }
            break;
        case 'high':
            // 高难度：检查MIME类型+重命名文件
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['file']['tmp_name']);
            if (strpos($mime, 'image/') === 0) {
                $newName = uniqid() . '.' . pathinfo($filename, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['file']['tmp_name'], "uploads/$newName");
                $message = "文件上传成功: $newName";
            } else {
                $message = "仅允许上传图片文件";
            }
            break;
    }
}
?>
<!-- 下拉框和表单结构与SQL注入页面一致 -->
