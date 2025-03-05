<?php
include 'dbconn.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Query khusus cabang Saidan (id_cabang = 1)
$sql = "SELECT id_divisi, nama_divisi FROM divisi WHERE id_cabang = 1";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query error: " . $conn->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Jika data kosong, tampilkan pesan error
if (empty($data)) {
    echo json_encode(["error" => "Tidak ada divisi untuk cabang Saidan"]);
} else {
    echo json_encode($data, JSON_PRETTY_PRINT);
}
?>
