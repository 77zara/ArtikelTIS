<?php
// File: project_integrasi_artikel/website_a_frontend_admin/articles/store.php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (empty($title) || empty($content)) {
        header('Location: create.php?status=error&message=' . urlencode('Judul dan Konten tidak boleh kosong.'));
        exit;
    }

    $data = [
        'title' => $title,
        'content' => $content,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '/articles');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Kirim sebagai JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_code == 201 && $result && $result['status'] == 'success') {
        header('Location: index.php?status=success&message=' . urlencode('Artikel berhasil ditambahkan.'));
    } else {
        $error_message = 'Gagal menambahkan artikel.';
        if ($result && isset($result['message'])) {
            if (is_array($result['message'])) { // Jika error validasi dari Lumen
                $error_details = [];
                foreach ($result['message'] as $field => $errors) {
                    $error_details[] = implode(', ', $errors);
                }
                $error_message .= ' Detail: ' . implode('; ', $error_details);
            } else {
                $error_message .= ' Detail: ' . $result['message'];
            }
        } else if ($http_code != 201) {
             $error_message .= ' HTTP Code: ' . $http_code . '. Response: ' . $response;
        }
        header('Location: create.php?status=error&message=' . urlencode($error_message));
    }
    exit;
} else {
    header('Location: create.php');
    exit;
}
?>