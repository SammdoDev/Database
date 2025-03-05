<?php
include 'dbconn.php';

$id_staff = $_GET['id_staff'] ?? '';
$id_skill = $_GET['id_skill'] ?? '';

if (empty($id_staff) || empty($id_skill)) {
    die("Error: Data tidak lengkap!");
}

// Ambil nama staff dan skill
$stmt = $conn->prepare("SELECT s.nama_staff, sk.nama_skill 
                        FROM staff s 
                        JOIN skill sk ON sk.id_skill = ? 
                        WHERE s.id_staff = ?");
$stmt->bind_param("ii", $id_skill, $id_staff);
$stmt->execute();
$result = $stmt->get_result();
$staffSkill = $result->fetch_assoc();
$stmt->close();

if (!$staffSkill) {
    die("Error: Data tidak ditemukan!");
}

// Ambil skill matrix terkait
$stmt = $conn->prepare("SELECT total_look, konsultasi_komunikasi, teknik, kerapian_kebersihan, produk_knowledge, rata_rata 
                        FROM skill_matrix 
                        WHERE id_staff = ? AND id_skill = ?");
$stmt->bind_param("ii", $id_staff, $id_skill);
$stmt->execute();
$result = $stmt->get_result();

$skillMatrix = [];
while ($row = $result->fetch_assoc()) {
    $skillMatrix[] = $row;
}
$stmt->close();

// Hitung rata-rata skill
$rata_rata_skill = 0;
if (!empty($skillMatrix)) {
    $total = 0;
    $jumlah = 0;
    foreach ($skillMatrix as $skill) {
        $total += $skill['rata_rata'];
        $jumlah++;
    }
    $rata_rata_skill = ($jumlah > 0) ? $total / $jumlah : 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Matrix - <?= htmlspecialchars($staffSkill['nama_skill']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h2 class="text-2xl font-bold mb-4">Skill Matrix: <?= htmlspecialchars($staffSkill['nama_skill']) ?> (<?= htmlspecialchars($staffSkill['nama_staff']) ?>)</h2>

    <table class="w-full bg-white shadow-lg rounded-lg">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Total Look</th>
                <th class="p-2">Konsultasi & Komunikasi</th>
                <th class="p-2">Teknik</th>
                <th class="p-2">Kerapian & Kebersihan</th>
                <th class="p-2">Produk Knowledge</th>
                <th class="p-2">Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skillMatrix as $skill) : ?>
                <tr class="border-b">
                    <td class="p-2"><?= $skill['total_look'] ?></td>
                    <td class="p-2"><?= $skill['konsultasi_komunikasi'] ?></td>
                    <td class="p-2"><?= $skill['teknik'] ?></td>
                    <td class="p-2"><?= $skill['kerapian_kebersihan'] ?></td>
                    <td class="p-2"><?= $skill['produk_knowledge'] ?></td>
                    <td class="p-2 font-bold"><?= $skill['rata_rata'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="text-xl font-bold mt-4">Nilai Rata-rata Skill: <?= number_format($rata_rata_skill, 2) ?></h3>
</body>
</html>
