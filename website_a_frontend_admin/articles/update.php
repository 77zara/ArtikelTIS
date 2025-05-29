<?php
// File: project_integrasi_artikel/website_a_frontend_admin/articles/update.php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (empty($id) || empty($title) || empty($content)) {
        header('Location: edit.php?id=' . urlencode($id) . '&status=error&message=' . urlencode('ID, Judul, dan Konten tidak boleh kosong.'));
        exit;
    }

    $data = [
        'title' => $title,
        'content' => $content,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '/articles/' . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Menggunakan metode PUT
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_code == 200 && $result && $result['status'] == 'success') {
        header('Location: index.php?status=success&message=' . urlencode('Artikel berhasil diperbarui.'));
    } else {
        $error_message = 'Gagal memperbarui artikel.';
         if ($result && isset($result['message'])) {
            if (is_array($result['message'])) {
                $error_details = [];
                foreach ($result['message'] as $field => $errors) {
                    $error_details[] = implode(', ', $errors);
                }
                $error_message .= ' Detail: ' . implode('; ', $error_details);
            } else {
                $error_message .= ' Detail: ' . $result['message'];
            }
        } else if ($http_code != 200) {
             $error_message .= ' HTTP Code: ' . $http_code . '. Response: ' . $response;
        }
        header('Location: edit.php?id=' . urlencode($id) . '&status=error&message=' . urlencode($error_message));
    }
    exit;
} else {
    // Jika bukan POST, redirect ke halaman edit dengan ID jika ada, atau ke index jika tidak ada ID
    $id_redirect = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);
    if ($id_redirect) {
        header('Location: edit.php?id=' . urlencode($id_redirect));
    } else {
        header('Location: index.php');
    }
    exit;
}
?>