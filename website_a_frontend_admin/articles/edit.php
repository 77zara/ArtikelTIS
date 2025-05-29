<?php
// File: project_integrasi_artikel/website_a_frontend_admin/articles/edit.php
require_once '../config.php';
require_once '../includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php?status=error&message=' . urlencode('ID artikel tidak valid.'));
    exit;
}

$article_id = $_GET['id'];

// Ambil data artikel yang akan diedit
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '/articles/' . $article_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$article = null;
if ($http_code == 200) {
    $result = json_decode($response, true);
    if ($result && $result['status'] == 'success') {
        $article = $result['data'];
    }
}

if (!$article) {
    header('Location: index.php?status=error&message=' . urlencode('Artikel tidak ditemukan atau gagal mengambil data.'));
    exit;
}
?>

<h2>Edit Artikel: <?php echo htmlspecialchars($article['title']); ?></h2>

<?php
if (isset($_GET['message'])) {
    $status = $_GET['status'] ?? 'error';
    echo "<div class='alert alert-" . htmlspecialchars($status) . "'>" . htmlspecialchars(urldecode($_GET['message'])) . "</div>";
}
?>

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">
    <div class="form-group">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
    </div>
    <div class="form-group">
        <label for="content">Konten:</label>
        <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($article['content']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-submit">Update Artikel</button>
</form>

<?php
require_once '../includes/footer.php';
?>
