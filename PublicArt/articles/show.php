<?php
// File: project_integrasi_artikel/website_b_frontend_public/articles/show.php
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='alert alert-error'>Artikel tidak ditemukan. ID tidak valid.</p>";
    require_once '../includes/footer.php';
    exit;
}

$article_id = $_GET['id'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '/articles/' . $article_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $result = json_decode($response, true);
    if ($result && $result['status'] == 'success' && !empty($result['data'])) {
        $article = $result['data'];
        echo "<div class='article-detail'>";
        echo "<h2>" . htmlspecialchars($article['title']) . "</h2>";
        echo "<p class='meta'>Dipublikasikan pada: " . htmlspecialchars(date('d M Y, H:i', strtotime($article['created_at']))) . "</p>";
        // Menggunakan nl2br untuk menjaga format paragraf dari textarea
        echo "<div class='content'>" . nl2br(htmlspecialchars($article['content'])) . "</div>";
        echo "</div>";
    } else {
        echo "<p class='alert alert-error'>Artikel tidak ditemukan atau format respons tidak sesuai.</p>";
        // var_dump($result); // Untuk debugging
    }
} else if ($http_code == 404) {
    echo "<p class='alert alert-error'>Artikel dengan ID " . htmlspecialchars($article_id) . " tidak ditemukan.</p>";
} else {
     echo "<p class='alert alert-error'>Gagal mengambil detail artikel dari API (HTTP Code: " . htmlspecialchars($http_code) . ").</p>";
}

echo "<br><p><a href='index.php'>&laquo; Kembali ke daftar artikel</a></p>";

require_once '../includes/footer.php';
?>