<?php
include 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_staff = $_POST['nama_staff'];
    $id_divisi = $_POST['id_divisi'];

    if (empty($nama_staff) || empty($id_divisi)) {
        echo json_encode(['success' => false, 'error' => 'Nama staff dan ID divisi harus diisi!']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO staff (nama_staff, id_divisi) VALUES (?, ?)");
    $stmt->bind_param("si", $nama_staff, $id_divisi);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Gagal menambahkan staff!']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Metode request tidak valid!']);
}
?>