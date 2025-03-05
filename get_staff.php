<?php
include '../dbconn.php';

$id_divisi = $_GET['id_divisi'] ?? null;

if (!$id_divisi) {
    die(json_encode(["error" => "ID divisi tidak diberikan"]));
}

$sql = "SELECT id_staff, nama_staff FROM staff WHERE id_divisi = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_divisi);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

if (empty($data)) {
    echo json_encode(["error" => "Tidak ada staff ditemukan untuk divisi $id_divisi"]);
} else {
    echo json_encode($data);
}
?>
