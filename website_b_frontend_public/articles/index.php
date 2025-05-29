
<?php
// File: project_integrasi_artikel/website_b_frontend_public/articles/index.php
require_once '../config.php';
require_once '../includes/header.php';

echo "<h2>Selamat Datang di Portal Artikel Kami</h2>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '/articles');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $result = json_decode($response, true);
    if ($result && $result['status'] == 'success' && !empty($result['data'])) {
        echo "<div class='articles-list'>";
        foreach ($result['data'] as $article) {
            echo "<div class='article-item'>";
            echo "<h3><a href='show.php?id=" . htmlspecialchars($article['id']) . "'>" . htmlspecialchars($article['title']) . "</a></h3>";
            echo "<p>" . htmlspecialchars(substr($article['content'], 0, 200)) . (strlen($article['content']) > 200 ? '...' : '') . "</p>";
            echo "<p><small>Dipublikasikan pada: " . htmlspecialchars(date('d M Y, H:i', strtotime($article['created_at']))) . "</small></p>";
            echo "</div>";
        }
        echo "</div>";
    } else if ($result && $result['status'] == 'success' && empty($result['data'])) {
        echo "<p>Saat ini belum ada artikel yang dipublikasikan.</p>";
    } else {
        echo "<p class='alert alert-error'>Gagal mengambil daftar artikel atau format respons tidak sesuai.</p>";
        // var_dump($result); // Untuk debugging
    }
} else {
    echo "<p class='alert alert-error'>Gagal terhubung ke API untuk mengambil artikel (HTTP Code: " . htmlspecialchars($http_code) . ").</p>";
}

require_once '../includes/footer.php';
?>