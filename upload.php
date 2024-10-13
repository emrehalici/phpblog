<?php
// upload.php

include 'config.php';

// Formdan gelen verileri al
$title = $_POST['title'];
$content = $_POST['content'];
$imageUrl = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    
    // S3'ye yükleme işlemi
    try {
        $result = $s3->putObject([
            'Bucket' => 'your-s3-bucket-name',
            'Key' => 'uploads/' . $fileName,
            'SourceFile' => $fileTmpPath,
            'ACL' => 'public-read'
        ]);
        $imageUrl = $result['ObjectURL'];
    } catch (Exception $e) {
        error_log("S3 yükleme hatası: " . $e->getMessage());
        die("Dosya yüklenirken bir hata oluştu.");
    }
}

// Eğer bir resim yüklenmediyse, image_url değerini NULL yap
if (empty($imageUrl)) {
    $imageUrl = ''; // NULL veya boş string kullanabilirsiniz
}

// Veritabanına yaz
$stmt = $conn->prepare("INSERT INTO posts (title, content, image_url) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $imageUrl);
$stmt->execute();
$stmt->close();

header('Location: index.php');
exit;
?>

