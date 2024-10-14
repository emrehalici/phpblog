<?php
include 'config.php';
include 'templates/header.php';

// Blog başlığı
echo "<header><h1>Emre Halıcı Blog Sayfası</h1></header>";

// Yeni yazı ekleme bağlantısı
echo "<h2><a href='new_post.php'>Yeni Yazı Ekle</a></h2>";

// Blog yazılarını veritabanından çekip listeleme
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

while ($row = $result->fetch_assoc()) {
    echo "<h2>{$row['title']}</h2>";
    
    // Resmi görüntüleme
    if (!empty($row['image_url'])) {
        echo "<img src='{$row['image_url']}' alt='Blog Image' style='max-width: 100%; height: auto;'>";
    } else {
        echo "<p>Resim mevcut değil.</p>";
    }
    
    echo "<p>{$row['content']}</p>";

    // Silme butonu
    echo "<form action='delete_post.php' method='post' style='display:inline;'>
            <input type='hidden' name='post_id' value='{$row['id']}'>
            <input type='submit' value='Sil' onclick='return confirm(\"Bu yazıyı silmek istediğinize emin misiniz?\");'>
          </form>";

    echo "<hr>";
}
?>

<?php
include 'templates/footer.php';
?>

