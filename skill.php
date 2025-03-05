<?php
include 'dbconn.php';

$id_staff = $_GET['id_staff'] ?? '';

if (empty($id_staff)) {
    die("Error: ID staff tidak ditemukan!");
}

// Ambil data staff
$stmt = $conn->prepare("SELECT nama_staff, id_divisi, id_cabang FROM staff WHERE id_staff = ?");
$stmt->bind_param("i", $id_staff);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();

if (!$staff) {
    die("Error: Staff tidak ditemukan!");
}

// Ambil daftar skill berdasarkan divisi dan cabang
$stmt = $conn->prepare("SELECT id_skill, nama_skill FROM skill WHERE id_divisi = ? AND id_cabang = ?");
$stmt->bind_param("ii", $staff['id_divisi'], $staff['id_cabang']);
$stmt->execute();
$result = $stmt->get_result();

$skills = [];
while ($row = $result->fetch_assoc()) {
    $skills[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Skill - <?= htmlspecialchars($staff['nama_staff']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Skill: <?= htmlspecialchars($staff['nama_staff']) ?></h2>

    <table class="w-full bg-white shadow-lg rounded-lg">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Nama Skill</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skills as $skill) : ?>
                <tr class="border-b">
                    <td class="p-2"><?= htmlspecialchars($skill['nama_skill']) ?></td>
                    <td class="p-2">
                        <a href="skill_matrix.php?id_staff=<?= $id_staff ?>&id_skill=<?= $skill['id_skill'] ?>" class="text-blue-500 underline">Lihat Skill Matrix</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
