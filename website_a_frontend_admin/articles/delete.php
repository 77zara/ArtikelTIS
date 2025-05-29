<?php
// File: project_integrasi_artikel/website_a_frontend_admin/articles/delete.php
require_once '../config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '/articles/' . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Menggunakan metode DELETE

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_code == 200 && $result && $result['status'] == 'success') {
        header('Location: index.php?status=success&message=' . urlencode('Artikel berhasil dihapus.'));
    } else {
        $error_message = 'Gagal menghapus artikel.';
        if ($result && isset($result['message'])) {
            $error_message .= ' Detail: ' . $result['message'];
        } else if ($http_code != 200) {
             $error_message .= ' HTTP Code: ' . $http_code . '. Response: ' . $response;
        }
        header('Location: index.php?status=error&message=' . urlencode($error_message));
    }
    exit;
} else {
    header('Location: index.php?status=error&message=' . urlencode('ID artikel tidak valid untuk dihapus.'));
    exit;
}
?>