<?php
include "config.php";

// Ambil notifikasi dari URL jika ada
$notif = isset($_GET['notif']) ? htmlspecialchars($_GET['notif']) : "";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Muda Bakti RT 8 RW 11</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Orbitron', sans-serif;
            background: #0d0d0d;
            color: #e0e0e0;
            line-height: 1.6;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #0d0d0d, #1a1a1a);
            color: #00f0ff;
            padding: 20px;
            text-shadow: 0 0 10px #00f0ff, 0 0 20px #00f0ff;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-logo {
            height: 120px;
            border-radius: 100px;
            filter: drop-shadow(0 0 12px #00f0ff);
            transition: transform .3s ease, filter .3s ease;
        }

        .header-logo:hover {
            transform: scale(1.1);
            filter: drop-shadow(0 0 18px #ff00ff);
        }

        .login-btn {
            color: #00f0ff;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #00f0ff;
            padding: 8px 15px;
            border-radius: 6px;
            box-shadow: 0 0 10px #00f0ff;
            transition: .3s;
        }

        .login-btn:hover {
            background: #00f0ff;
            color: #0d0d0d;
            box-shadow: 0 0 20px #ff00ff;
        }

        /* Navigasi */
        nav {
            background: #111;
            display: flex;
            justify-content: center;
            gap: 25px;
            padding: 15px;
            border-bottom: 2px solid #00f0ff;
        }

        nav a {
            color: #ff00ff;
            text-decoration: none;
            font-weight: bold;
            position: relative;
            transition: all .3s ease;
        }

        nav a::after {
            content: "";
            position: absolute;
            width: 0%;
            height: 2px;
            bottom: 0;
            left: 0;
            background: #00f0ff;
            transition: width .3s ease;
        }

        nav a:hover {
            color: #00f0ff;
        }

        nav a:hover::after {
            width: 100%;
        }

        /* Container */
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: #1a1a1a;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            border: 1px solid #00f0ff;
            box-shadow: 0 0 15px rgba(0, 240, 255, .5);
        }

        /* Notifikasi */
        .notif {
            background: #00f0ff;
            color: #0d0d0d;
            padding: 12px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 0 20px #00f0ff;
            font-weight: bold;
            animation: fadeIn .8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tabel Warga */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #333;
            text-align: center;
        }

        th {
            background: #111;
            color: #00f0ff;
            text-transform: uppercase;
        }

        tr:hover {
            background: rgba(255, 0, 255, .1);
        }

        #searchWarga {
            margin: 10px 0;
            padding: 8px;
            width: 50%;
            border-radius: 6px;
            border: 1px solid #00f0ff;
            background: #111;
            color: #00f0ff;
            box-shadow: 0 0 10px rgba(0, 240, 255, .5);
        }

        /* Kegiatan */
        #listKegiatan {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .kegiatan-item {
            background: #111;
            border: 1px solid #00f0ff;
            border-radius: 10px;
            padding: 10px;
            width: 220px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 240, 255, .5);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .kegiatan-item:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #ff00ff;
        }

        .kegiatan-item img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 8px;
            box-shadow: 0 0 10px rgba(255, 0, 255, .5);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background: #111;
            color: #00f0ff;
            border-top: 2px solid #ff00ff;
        }

        .notif {
            background: #00f0ff;
            color: #0d0d0d;
            padding: 12px;
            border-radius: 8px;
            margin: 20px;
            text-align: center;
            box-shadow: 0 0 20px #00f0ff;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="header-left">
                <img src="image/logort8.png" alt="Logo RT" class="header-logo">
                <div>
                    <h1>Muda Bakti RT 8 RW 11</h1>
                    <p>Purwokerto Selatan</p>
                </div>
            </div>
            <a href="login.php" class="login-btn">Login Admin</a>
        </div>
    </header>

    <!-- Navigasi -->
    <nav>
        <a href="#warga">Data Warga</a>
        <a href="#kegiatan">Kegiatan</a>
        <a href="#kontak">Kontak</a>
    </nav>

    <!-- Notifikasi -->
    <?php if ($notif != "")
        echo "<div class='notif'>$notif</div>"; ?>

    <!-- Container -->
    <div class="container">
        <!-- Data Warga -->
        <div class="card" id="warga">
            <h2>Data Warga</h2>
            <input type="text" id="searchWarga" placeholder="Cari nama warga...">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Umur</th>
                        <th>Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM warga ORDER BY id DESC");
                    while ($w = $result->fetch_assoc()) {
                        echo "<tr>
              <td>{$w['id']}</td>
              <td>{$w['nama']}</td>
              <td>{$w['umur']}</td>
              <td>{$w['jk']}</td>
            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Data Kegiatan -->
        <div class="card" id="kegiatan">
            <h2>Kegiatan</h2>
            <div id="listKegiatan">
                <?php
                $result = $conn->query("SELECT * FROM kegiatan ORDER BY id DESC");
                while ($k = $result->fetch_assoc()) {
                    echo "<div class='kegiatan-item'>
            <p>{$k['nama']}</p>
            <img src='{$k['foto']}' alt='Foto kegiatan {$k['nama']}' loading='lazy'>
          </div>";
                }
                ?>
            </div>
        </div>

        <!-- Kontak -->
        <div class="card" id="kontak">
            <h2>Kontak</h2>
            <p>Developer: Yohanes Ferdi</p>
            <p>Alamat: Purwokerto Selatan, Karangklasem, Puri Indah RT 8 RW 11</p>
            <p>Instagram: <a href="https://instagram.com/_yfdy___" target="_blank" style="color:#ffffff;">_yfdy___</a>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2026 Muda Bakti RT 8 RW 11 Puri Indah - Karangklasem, Purwokerto Selatan, Banyumas, Jawa Tengah
    </footer>

    <!-- Script Filter Warga -->
    <script>
        document.getElementById('searchWarga').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#warga table tbody tr");
            rows.forEach(row => {
                let nama = row.cells[1].textContent.toLowerCase();
                row.style.display = nama.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>