<?php
include '../dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_staff = $_POST["nama_staff"];
    $id_divisi = $_POST["id_divisi"];

    if (empty($nama_staff) || empty($id_divisi)) {
        echo json_encode(["success" => false, "error" => "Data tidak lengkap"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO staff (nama_staff, id_divisi) VALUES (?, ?)");
    $stmt->bind_param("si", $nama_staff, $id_divisi);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
}
?>
