<?php
include 'config.php';
include 'templates/header.php';
require 'vendor/autoload.php'; // AWS SDK'yı dahil et

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Yeni blog yazısı ekleme formu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_url = null;

    // Eğer resim yüklendiyse
    if (!empty($_FILES['image']['name'])) {
        // S3 istemcisini oluştur
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1', // Bölgenizi buraya girin
            'credentials' => [
            ],
        ]);

        // Resmi yükleme işlemleri
        try {
            $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
            $image_path = 'uploads/' . $image_name; // Yükleme yolu
            $result = $s3->putObject([
                'Bucket' => 'phpblogdatarepo', // S3 Bucket adınızı buraya girin
                'Key'    => $image_path,
                'SourceFile' => $_FILES['image']['tmp_name'],
            ]);
            $image_url = $result['ObjectURL']; // Yükleme başarılıysa URL'yi al
        } catch (AwsException $e) {
            echo "S3 yükleme hatası: " . $e->getMessage();
            $image_url = null; // Hata durumunda null ayarla
        }
    }

    // Veritabanına ekleme
    $stmt = $conn->prepare("INSERT INTO posts (title, content, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $image_url);

    if ($stmt->execute()) {
	    echo "Yazı başarıyla eklendi!";
	    echo '<a href="index.php">Ana Sayfaya Dön</a>';
    } else {
        echo "Hata: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<h2>Yeni Yazı Ekle</h2>
<form action="new_post.php" method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Başlık" required><br>
    <textarea name="content" placeholder="Yazı içeriği" required></textarea><br>
    <input type="file" name="image"><br> <!-- Dosya yükleme isteğe bağlı -->
    <input type="submit" value="Gönder">
</form>

<?php
include 'templates/footer.php';
?>

