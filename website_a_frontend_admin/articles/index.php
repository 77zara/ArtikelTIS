<?php
// File: project_integrasi_artikel/website_a_frontend_admin/articles/index.php
require_once '../config.php';
require_once '../includes/header.php';

echo "<h2>Daftar Artikel</h2>";

if (isset($_GET['message'])) {
    $status = $_GET['status'] ?? 'success';
    echo "<div class='alert alert-" . htmlspecialchars($status) . "'>" . htmlspecialchars(urldecode($_GET['message'])) . "</div>";
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '/articles');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $result = json_decode($response, true);
    if ($result && $result['status'] == 'success' && !empty($result['data'])) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Judul</th><th>Konten (Singkat)</th><th>Aksi</th></tr>";
        foreach ($result['data'] as $article) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($article['id']) . "</td>";
            echo "<td>" . htmlspecialchars($article['title']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($article['content'], 0, 100)) . (strlen($article['content']) > 100 ? '...' : '') . "</td>";
            echo "<td>
                    <a href='edit.php?id=" . htmlspecialchars($article['id']) . "' class='btn btn-edit'>Edit</a>
                    <a href='delete.php?id=" . htmlspecialchars($article['id']) . "' class='btn btn-delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus artikel ini?\")'>Hapus</a>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else if ($result && $result['status'] == 'success' && empty($result['data'])) {
        echo "<p>Belum ada artikel.</p>";
    } else {
        echo "<p class='alert alert-error'>Gagal mengambil data artikel atau format respons tidak sesuai.</p>";
        // var_dump($result); // Untuk debugging
    }
} else {
    echo "<p class='alert alert-error'>Gagal terhubung ke API (HTTP Code: " . htmlspecialchars($http_code) . "). Respons: " . htmlspecialchars($response) . "</p>";
}

require_once '../includes/footer.php';
