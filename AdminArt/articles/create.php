<?php
if (isset($_GET['message'])) {
    $status = $_GET['status'] ?? 'error';
    echo "<div class='alert alert-" . htmlspecialchars($status) . "'>" . htmlspecialchars(urldecode($_GET['message'])) . "</div>";
}
?>

<form action="store.php" method="POST">
    <div class="form-group">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="content">Konten:</label>
        <textarea id="content" name="content" rows="10" required></textarea>
    </div>
    <button type="submit" class="btn btn-submit">Simpan Artikel</button>
</form>

<?php
require_once '../includes/footer.php';
?>