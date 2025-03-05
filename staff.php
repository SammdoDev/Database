<?php
include 'dbconn.php';

if (!isset($_GET['id_staff'])) {
    die("Staff tidak ditemukan.");
}

$id_staff = intval($_GET['id_staff']);

// Ambil data staff
$query = $conn->prepare("SELECT nama_staff FROM staff WHERE id_staff = ?");
$query->bind_param("i", $id_staff);
$query->execute();
$result = $query->get_result();
$staff = $result->fetch_assoc();

if (!$staff) {
    die("Staff tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Staff - <?= $staff['nama_staff'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-4">Skill Matrix - <?= $staff['nama_staff'] ?></h1>
    <table class="w-full bg-white shadow-lg rounded-lg">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Skill</th>
                <th class="p-2">Rata-rata Skill</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $conn->prepare("SELECT nama_skill, rata_rata_skill FROM skill WHERE id_cabang IN (SELECT id_cabang FROM staff WHERE id_staff = ?) ");
            $query->bind_param("i", $id_staff);
            $query->execute();
            $result = $query->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<tr class='border-b'>
                        <td class='p-2'>" . $row['nama_skill'] . "</td>
                        <td class='p-2'>" . $row['rata_rata_skill'] . "</td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
