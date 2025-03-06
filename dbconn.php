<?php
$servername = "localhost";
$username = "root";
$password = "123";
$dbname = "liekuang_samuel";

try {
    // Koneksi ke MySQL
    $conn = new mysqli($servername, $username, $password);

    // Buat database jika belum ada
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (!$conn->query($sql))
        throw new Exception("Gagal membuat database");

    if (!$conn->select_db($dbname)) {
        throw new Exception("Gagal memilih database: " . $conn->error);
    }

    // Hapus tabel lama jika ingin reset ulang (opsional)
    $conn->query("DROP TABLE IF EXISTS skill_matrix, skill, staff, divisi, cabang");

    // Buat tabel cabang
    $sql = "CREATE TABLE cabang (
        id_cabang INT AUTO_INCREMENT PRIMARY KEY,
        nama_cabang VARCHAR(100) NOT NULL
    )";
    $conn->query($sql);

    // Buat tabel divisi
    $sql = "CREATE TABLE divisi (
        id_divisi INT AUTO_INCREMENT PRIMARY KEY,
        nama_divisi VARCHAR(100) NOT NULL,
        id_cabang INT,
        FOREIGN KEY (id_cabang) REFERENCES cabang(id_cabang) ON DELETE CASCADE
    )";
    $conn->query($sql);

    // Buat tabel skill
    $sql = "CREATE TABLE skill (
        id_skill INT AUTO_INCREMENT PRIMARY KEY,
        nama_skill VARCHAR(100) NOT NULL,
        id_divisi INT,
        id_cabang INT,
        rata_rata_skill FLOAT DEFAULT 0,
        FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi) ON DELETE CASCADE,
        FOREIGN KEY (id_cabang) REFERENCES cabang(id_cabang) ON DELETE CASCADE
    )";
    $conn->query($sql);

    // Buat tabel skill matrix
    $sql = "CREATE TABLE skill_matrix (
        id_skill_matrix INT AUTO_INCREMENT PRIMARY KEY,
        id_skill INT,
        id_divisi INT,
        id_cabang INT,
        total_look FLOAT DEFAULT 0,
        konsultasi_komunikasi FLOAT DEFAULT 0,
        teknik FLOAT DEFAULT 0,
        kerapian_kebersihan FLOAT DEFAULT 0,
        produk_knowledge FLOAT DEFAULT 0,
        rata_rata FLOAT GENERATED ALWAYS AS (
            (total_look + konsultasi_komunikasi + teknik + kerapian_kebersihan + produk_knowledge) / 5
        ) STORED,
        FOREIGN KEY (id_skill) REFERENCES skill(id_skill) ON DELETE CASCADE,
        FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi) ON DELETE CASCADE,
        FOREIGN KEY (id_cabang) REFERENCES cabang(id_cabang) ON DELETE CASCADE
    )";
    $conn->query($sql);

    // Buat tabel staff
    $sql = "CREATE TABLE staff (
        id_staff INT AUTO_INCREMENT PRIMARY KEY,
        nama_staff VARCHAR(100) NOT NULL,
        id_divisi INT,
        id_cabang INT,
        FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi) ON DELETE CASCADE,
        FOREIGN KEY (id_cabang) REFERENCES cabang(id_cabang) ON DELETE CASCADE
    )";
    $conn->query($sql);

    // Insert data cabang
    $cabang_list = ['Saidan', 'Solo', 'Sora', 'Grand Edge', 'Soal Rambut'];
    foreach ($cabang_list as $cabang) {
        $conn->query("INSERT INTO cabang (nama_cabang) 
                      SELECT '$cabang' FROM DUAL 
                      WHERE NOT EXISTS (SELECT 1 FROM cabang WHERE nama_cabang = '$cabang')");
    }

    // Insert data divisi untuk setiap cabang
    $divisi_list = [
        'Treatment',
        'Meni Pedi',
        'Nail Art',
        'Blow Dry',
        'Smothing',
        'Perming',
        'Color',
        'Cutting',
        'Hair Do',
        'Make Up',
        'Waxing',
        'Hair Extension'
    ];

    foreach ($cabang_list as $cabang) {
        $result = $conn->query("SELECT id_cabang FROM cabang WHERE nama_cabang = '$cabang'");

        if ($result->num_rows > 0) {
            $id_cabang = $result->fetch_assoc()['id_cabang'];

            foreach ($divisi_list as $divisi) {
                $insert_divisi = "INSERT INTO divisi (nama_divisi, id_cabang) 
                                  SELECT '$divisi', $id_cabang FROM DUAL 
                                  WHERE NOT EXISTS (
                                      SELECT 1 FROM divisi WHERE nama_divisi = '$divisi' AND id_cabang = $id_cabang
                                  )";
                if ($conn->query($insert_divisi) === TRUE) {
                } else {
                    echo "❌ Gagal insert divisi '$divisi' untuk cabang '$cabang': " . $conn->error . "<br>";
                }
            }
        } else {
            echo "❌ ID Cabang untuk '$cabang' tidak ditemukan! <br>";
        }
    }


    // Data skill per divisi
    $skill_data = [
        'Treatment' => [
            'Shampoo Basic',
            'Creambath',
            'Scalp & Hair Theory',
            'Hair Keratine',
            'Produk Knowledge Keune',
            'Perawatan Scalp & Hair',
            'Diagnosa & Konsultasi Manual / Alat',
            'Shampoo LK PRO',
            'Mini Detox Premium',
            'Produk Knowledge Kerastase',
            'Diagnosa & Konsultasi Manual',
            'Teknik Full Treatment',
            'All Tools Treatment',
            'Produk Knowledge Olaplex',
            'LK Express Keratine',
            'Hair Gloss Color Treatment',
            'Teknik Full Treatment BOTANICA',
            'Diagnosa Dengan ALAT',
            'LK GLOSS',
            'Produk Knowledge Davines',
            'Produk Knowledge Milbon',
            'Produk Knowledge K18'
        ],
        'Meni Pedi' => [
            'Pemahaman Dasar Meni Pedi',
            'NAIL TREATMENT',
            'REMOVE GEL BASIC',
            'NAIL POLISH',
            'MENI PEDI CLASIC',
            'MENI PEDI ADVANCED (Credo)',
            'TEORI KULIT & KUKU',
            'MENI PEDI SPA',
            'MENI PEDI LUXURY'
        ],
        'Nail Art' => [
            'Pengenalan Lempengan Kuku & Fungsinya',
            'KONSULTASI',
            'CARA FILE KUKU',
            'GEL POLISH 1 WARNA',
            'GEL POLISH FRENCH MANICURE',
            'NAIL ART SIMPLE BAB 1',
            'RUSSIAN MANICURE',
            'OVERLAY KUKU',
            'GEL POLISH GRADASI',
            'GEL POLISH GRADASI GLITER',
            'GEL POLISH CAT EYE',
            'NAIL ART SIMPLE BAB 2',
            'TEMPEL KUKU',
            'EXTENTION KUKU'
        ],
        'Blow Dry' => [
            'MEN\'S STYLE',
            'NATURAL BLOWDRY',
            'BASIC FINISHING IRON',
            'BASIC ROLL PONI',
            'MED C-CURL',
            'MED S-CURL',
            'LONG C-CURL',
            'LONG S-CURL',
            'LONG DOUBLE S-CURL',
            'ROOT VOLUME TEHNIK',
            'FINISHING',
            'S-CURL FROM ROOT',
            'S-CURL KOMBINASI',
            'MEN\'S STYLE LEAF',
            'FLAT IRON S',
            'DOUBLE S LONG'
        ],
        'Smoothing' => [
            'Smoothing Rambut Virgin BASIC LO',
            'Smoothing Rambut Rusak BASIC LO',
            'Smoothing Sambung Akar LO',
            'Steam Smooth Blow LO',
            'Steam Smooth Blow Rambut Layer LO',
            'Steam Smooth Blow Rambut Bob LO',
            'Smoothing Cowok LO',
            'Smoothing Shaping',
            'Smoothing & Steam Smooth Organic'
        ],
        'Perming' => [
            'Basic Perm Parting 9',
            'Basic Perm Standart',
            'Basic Perm Batu Bata',
            'Basic Perm Vertikal',
            'Perming Kerucut',
            'Classic Men\'s Perm',
            'Permanen Blow Dry S-Curl',
            'Permanen Blow Dry Double S-Curl',
            'Double Rotto',
            'Perm Akar Nano Perm',
            'Perm Akar RootLift',
            'Perm Akar Volume Lift',
            'Perm Cowok Catok Clinton',
            'Perm Cowok Down Perm',
            'Permanen Blow Dry C-Curl',
            'Korean Perm',
            'Permanen Blow Dry Kombinasi Smoothing Akar C Curl',
            'Permanen Blow Dry Kombinasi Smoothing Akar S Curl',
            'Permanen Blow Dry Kombinasi Smoothing Akar Double S Curl',
            'Permanen Blow Dry Kombinasi Perm Akar C Curl',
            'Permanen Blow Dry Kombinasi Perm Akar S Curl',
            'Permanen Blow Dry Kombinasi Perm Akar Double S Curl',
            'REJUVA PERM Bob',
            'REJUVA PERM Low Bob',
            'REJUVA PERM LONG'
        ],
        'Color' => [
            'BASIC APPLICATION',
            'UNDERSTANDING GREY COVERAGE',
            'CREATIVE CONSULTATION',
            'COMMERCIAL COLOR CHANGE',
            'ESSENTIAL BLONDE',
            'ULTIMATE HIGHLIGHT',
            'CLASSIC BALAYAGE',
            'TEASE BALAYAGE',
            'AUX BALAYAGE',
            'AIRTOUCH',
            'FAUX BALAYAGE',
            'FAUX HIGHLIGHT'
        ],
        'Cutting' => [
            'LONG ONE LENGTH',
            'LONG GRADUATION',
            'LONG LAYER',
            'MEDIUM ONE LENGTH',
            'MEDIUM GRADUATION',
            'MEDIUM LAYER',
            'SHORT MEDIUM GRADUATION',
            'SHORT MEDIUM LAYER',
            'KOREAN CUTTING (Hush Cut, Hime Cut, Wolf Cut)',
            'CUTTING HAIR EXTENSION'
        ],
        'Hair Do' => [
            'KEPANG',
            'PONY TAIL',
            'HALF UP',
            'HAIR DO CEPOL',
            'HAIR DO PESTA',
            'HAIR DO MOM BRIDE',
            'HAIR DO WEDDING'
        ],
        'Make Up' => [
            'MAKE UP BASIC',
            'MAKE UP SWEET 17',
            'ENGAGEMENT',
            'FACE LIFT MAKE UP',
            'WEDDING'
        ],
        'Waxing' => [
            'Pemahaman Dasar Waxing',
            'UPPER LIP',
            'UNDER ARMS',
            'PLAYBOY BIKINI',
            'HALF LEG',
            'HALF ARM',
            'FULL MONTY',
            'FULL LEG',
            'FULL FACE',
            'FULL BEARD',
            'FULL ARM',
            'EYEBROW',
            'BIKINI',
            'BIKINI LOW',
            'BACK HALF',
            'BACK FULL',
            'ANTI INGROWN HAIR OIL'
        ],
        'Hair Extension' => [
            'COMMUNICATION SKILL',
            'SECTION',
            'PEMASANGAN RING',
            'TEHNIK JAHIT',
            'K-TIP',
            'TEHNIK JAHIT 1-3 BARIS',
            'TEHNIK STYLING',
            'TEHNIK JAHIT 4 BARIS'
        ]
    ];
    


    // Insert skill berdasarkan divisi
  $stmt = $conn->prepare("INSERT INTO skill (nama_skill, id_divisi, id_cabang) 
                        SELECT ?, ?, ? FROM DUAL
                        WHERE NOT EXISTS (
                            SELECT 1 FROM skill WHERE nama_skill = ? AND id_divisi = ? AND id_cabang = ?
                        )");

if (!$stmt) {
    die("Prepare statement gagal: " . $conn->error);
}

foreach ($cabang_list as $cabang) {
    // Mengambil id_cabang berdasarkan nama_cabang
    $result = $conn->query("SELECT id_cabang FROM cabang WHERE nama_cabang = '" . $conn->real_escape_string($cabang) . "'");
    if ($result && $result->num_rows > 0) {
        $id_cabang = $result->fetch_assoc()['id_cabang'];

        foreach ($skill_data as $nama_divisi => $skills) {
            // Mengambil id_divisi berdasarkan nama_divisi dan id_cabang
            $divisi_result = $conn->query("SELECT id_divisi FROM divisi WHERE nama_divisi = '" . $conn->real_escape_string($nama_divisi) . "' AND id_cabang = $id_cabang");

            while ($row = $divisi_result->fetch_assoc()) {
                $id_divisi = $row['id_divisi'];

                foreach ($skills as $skill) {
                    // Escape karakter petik tunggal agar tidak error SQL
                    $escaped_skill = str_replace("'", "''", $skill); 
                    
                    // Bind parameter
                    $stmt->bind_param("siisii", $escaped_skill, $id_divisi, $id_cabang, $escaped_skill, $id_divisi, $id_cabang);
                    if (!$stmt->execute()) {
                        echo "❌ Terjadi kesalahan: " . $stmt->error;
                    }
                }
            }
        }
    } else {
        echo "❌ Cabang '$cabang' tidak ditemukan di database.";
    }
}

} catch (Exception $e) {
    echo "❌ Terjadi kesalahan: " . $e->getMessage();
}

?>