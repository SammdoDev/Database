<?php
// Koneksi ke database
$file_path = __DIR__ . '/../dbconn.php';
if (file_exists($file_path)) {
    include $file_path;
} else {
    die("Error: File dbconn.php tidak ditemukan!");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saidan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">
    <div class="w-1/4 bg-white p-4 shadow-lg min-h-screen">
        <h2 class="text-xl font-bold mb-4">Divisi - Cabang Saidan</h2>
        <ul id="divisi-list" class="space-y-2">
            <!-- Daftar divisi akan dimuat di sini -->
        </ul>
    </div>
    <div class="w-3/4 p-6">
        <h2 class="text-2xl font-bold mb-4" id="title">Pilih Divisi</h2>

        <!-- Form Tambah Staff -->
        <div id="form-container" class="hidden mb-4">
            <input type="text" id="staff-name" placeholder="Nama Staff" class="border p-2 rounded">
            <button onclick="addStaff()" class="bg-green-500 text-white p-2 rounded">Tambah Staff</button>
        </div>

        <table class="w-full bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Nama Staff</th>
                </tr>
            </thead>
            <tbody id="staff-list">
                <!-- Daftar staff akan dimuat di sini -->
            </tbody>
        </table>
    </div>

    <script>
        let selectedDivisi = null; // Simpan ID divisi yang dipilih

        document.addEventListener("DOMContentLoaded", function () {
            fetch("../get_divisi.php")
                .then(response => response.json())
                .then(data => {
                    let divisiList = document.getElementById("divisi-list");
                    divisiList.innerHTML = "";
                    data.forEach(divisi => {
                        let li = document.createElement("li");
                        li.className = "p-2 bg-blue-500 text-white rounded cursor-pointer";
                        li.textContent = divisi.nama_divisi;
                        li.onclick = () => loadStaff(divisi.id_divisi, divisi.nama_divisi);
                        divisiList.appendChild(li);
                    });
                })
                .catch(error => console.error("Error mengambil divisi:", error));
        });

        function loadStaff(idDivisi, namaDivisi) {
            selectedDivisi = idDivisi; // Simpan ID divisi
            document.getElementById("title").textContent = "Staff - " + namaDivisi;
            document.getElementById("form-container").classList.remove("hidden"); // Tampilkan form tambah staff

            fetch("../get_staff.php?id_divisi=" + idDivisi)
                .then(response => response.json())
                .then(data => {
                    let staffList = document.getElementById("staff-list");
                    staffList.innerHTML = "";
                    data.forEach(staff => {
                        let tr = document.createElement("tr");
                        tr.className = "border-b";
                        let td = document.createElement("td");
                        td.className = "p-2 cursor-pointer text-blue-500";
                        td.textContent = staff.nama_staff;
                        td.onclick = () => window.location.href = "../skill.php?id_staff=" + staff.id_staff;
                        tr.appendChild(td);
                        staffList.appendChild(tr);
                    });
                })
                .catch(error => console.error("Error mengambil staff:", error));
        }

        function addStaff() {
            let staffName = document.getElementById("staff-name").value;
            if (!staffName || !selectedDivisi) {
                alert("Masukkan nama staff dan pilih divisi!");
                return;
            }

            fetch("../add_staff.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `nama_staff=${staffName}&id_divisi=${selectedDivisi}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Staff berhasil ditambahkan!");
                        document.getElementById("staff-name").value = ""; // Reset input
                        loadStaff(selectedDivisi, document.getElementById("title").textContent.split(" - ")[1]); // Refresh staff list
                    } else {
                        alert("Gagal menambahkan staff: " + data.error);
                    }
                })
                .catch(error => console.error("Error menambahkan staff:", error));
        }
    </script>
</body>

</html>