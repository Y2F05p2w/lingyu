<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "文件 " . basename($_FILES["fileToUpload"]["name"]) . " 已上传.";
    } else {
        echo "抱歉，上传文件失败.";
    }
}
?>

<form action="file_upload.php" method="post" enctype="multipart/form-data">
    选择文件上传:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="上传文件" name="submit">
</form>
