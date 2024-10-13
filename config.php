<?php
// AWS RDS MySQL bağlantı ayarları
define('DB_HOST', 'phpblog.cjq46ywe28x8.us-east-1.rds.amazonaws.com');
define('DB_USER', 'admin');
define('DB_PASS', '');
define('DB_NAME', 'blogdb');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// AWS S3 bağlantı ayarları
require 'vendor/autoload.php';

use Aws\S3\S3Client;
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => '',
    'credentials' => [
    ],
]);

$bucket = 'phpblogdatarepo';
?>
