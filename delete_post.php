<?php
include 'config.php';

// Eğer POST isteği geldiyse
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Yazıyı silme sorgusu
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    
    if ($stmt->execute()) {
        echo "Yazı başarıyla silindi!";
    } else {
        echo "Hata: " . $stmt->error;
    }
    
    $stmt->close();

    // Ana sayfaya yönlendirme
    header("Location: index.php");
    exit();
}
?>

