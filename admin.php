<?php
include "config.php";

// Tambah Warga
if (isset($_POST['tambahWarga'])) {
  $stmt = $conn->prepare("INSERT INTO warga (nama, umur, jk) VALUES (?, ?, ?)");
  $stmt->bind_param("sis", $_POST['nama'], $_POST['umur'], $_POST['jk']);
  if ($stmt->execute()) {
    header("Location: admin.php?notif=Data warga berhasil ditambahkan");
    exit;
  }
  $stmt->close();
}

// Tambah Kegiatan
if (isset($_POST['tambahKegiatan'])) {
  $nama = $_POST['kegiatan'];
  $foto = $_FILES['fotoKegiatan'];
  $allowedTypes = ['image/jpeg', 'image/png'];
  if (in_array($foto['type'], $allowedTypes) && $foto['size'] < 2000000) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir))
      mkdir($targetDir, 0777, true);
    $targetFile = $targetDir . uniqid() . "_" . basename($foto["name"]);
    move_uploaded_file($foto["tmp_name"], $targetFile);

    $stmt = $conn->prepare("INSERT INTO kegiatan (nama,foto) VALUES (?,?)");
    $stmt->bind_param("ss", $nama, $targetFile);
    if ($stmt->execute()) {
      header("Location: admin.php?notif=Kegiatan baru berhasil ditambahkan");
      exit;
    }
    $stmt->close();
  } else {
    header("Location: admin.php?notif=File tidak valid (hanya JPG/PNG, max 2MB)");
    exit;
  }
}

// Edit Kegiatan (ganti foto)
if (isset($_POST['editKegiatan'])) {
  $id = intval($_POST['id']);
  $nama = $_POST['nama'];
  $foto = $_FILES['fotoKegiatan'];
  $targetFile = $_POST['fotoLama'];

  if ($foto['size'] > 0) {
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (in_array($foto['type'], $allowedTypes) && $foto['size'] < 2000000) {
      if (file_exists($targetFile))
        unlink($targetFile);
      $targetDir = "uploads/";
      if (!is_dir($targetDir))
        mkdir($targetDir, 0777, true);
      $targetFile = $targetDir . uniqid() . "_" . basename($foto["name"]);
      move_uploaded_file($foto["tmp_name"], $targetFile);
    } else {
      header("Location: admin.php?notif=File tidak valid (hanya JPG/PNG, max 2MB)");
      exit;
    }
  }

  $stmt = $conn->prepare("UPDATE kegiatan SET nama=?, foto=? WHERE id=?");
  $stmt->bind_param("ssi", $nama, $targetFile, $id);
  if ($stmt->execute()) {
    header("Location: admin.php?notif=Data kegiatan berhasil diubah");
    exit;
  }
  $stmt->close();
}


// Hapus Warga
if (isset($_GET['hapusWarga'])) {
  $id = intval($_GET['hapusWarga']);
  if ($conn->query("DELETE FROM warga WHERE id=$id")) {
    header("Location: admin.php?notif=Data warga berhasil dihapus");
    exit;
  }
}

// Hapus Kegiatan
if (isset($_GET['hapusKegiatan'])) {
  $id = intval($_GET['hapusKegiatan']);
  $res = $conn->query("SELECT foto FROM kegiatan WHERE id=$id");
  if ($row = $res->fetch_assoc()) {
    if (file_exists($row['foto']))
      unlink($row['foto']);
  }
  if ($conn->query("DELETE FROM kegiatan WHERE id=$id")) {
    header("Location: admin.php?notif=Data kegiatan berhasil dihapus");

    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Muda Bakti RT 8 RW 11</title>
  <style>
    body {
      font-family: 'Orbitron', sans-serif;
      background: #0d0d0d;
      color: #e0e0e0;
      padding: 20px;
    }

    h1 {
      color: #00f0ff;
      text-shadow: 0 0 10px #00f0ff;
    }

    .form-box,
    .data-box {
      background: #1a1a1a;
      padding: 20px;
      border-radius: 12px;
      border: 1px solid #00f0ff;
      margin-bottom: 20px;
      box-shadow: 0 0 15px rgba(0, 240, 255, .5);
    }

    input,
    select,
    button {
      margin: 5px 0;
      padding: 10px;
      border-radius: 6px;
      border: none;
      outline: none;
    }

    button {
      background: #00f0ff;
      cursor: pointer;
      transition: .3s;
    }

    button:hover {
      background: #ff00ff;
      color: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th,
    td {
      border: 1px solid #333;
      padding: 10px;
      text-align: center;
    }

    th {
      background: #111;
      color: #00f0ff;
    }

    #logout {
      background: #ff00ff;
      color: #fff;
      padding: 8px 15px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      box-shadow: 0 0 10px #ff00ff;
      transition: .3s;
    }

    #logout:hover {
      background: #00f0ff;
      color: #0d0d0d;
      box-shadow: 0 0 20px #ff00ff;
    }

    .kegiatan-item {
      margin-bottom: 15px;
      padding: 10px;
      border: 1px solid #00f0ff;
      border-radius: 8px;
      background: #111;
      box-shadow: 0 0 10px rgba(0, 240, 255, .5);
    }

    .kegiatan-item img {
      max-width: 200px;
      display: block;
      margin: 10px auto;
      border-radius: 6px;
      box-shadow: 0 0 10px #ff00ff;
    }
  </style>
</head>

<body>
  <?php if (isset($_GET['notif'])): ?>
    <div id="notif" style="background:#00f0ff;color:#0d0d0d;padding:10px;border-radius:6px;margin-bottom:15px;">
      <?= htmlspecialchars($_GET['notif']); ?>
    </div>
  <?php endif; ?>

  <h1>Panel Admin</h1>
  <button id="logout" onclick="window.location.href='logout.php'">Logout</button>

  <!-- Form Tambah Warga -->
  <div class="form-box">
    <h2>Tambah Warga</h2>
    <form method="POST">
      <input type="text" name="nama" placeholder="Nama" required>
      <input type="number" name="umur" placeholder="Umur" min="1" required>
      <select name="jk">
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
      </select>
      <button type="submit" name="tambahWarga">Tambah</button>
    </form>
  </div>

  <!-- Form Tambah Kegiatan -->
  <div class="form-box">
    <h2>Tambah Kegiatan</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="kegiatan" placeholder="Nama Kegiatan" required>
      <input type="file" name="fotoKegiatan" accept="image/*" required>
      <button type="submit" name="tambahKegiatan">Tambah</button>
    </form>
  </div>

  <!-- Data Warga -->
  <div class="data-box">
    <h2>Data Warga</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Umur</th>
          <th>Jenis Kelamin</th>
          <th>Aksi</th>
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
            <td><a href='admin.php?hapusWarga={$w['id']}'>Hapus</a></td>
          </tr>";
        }
        ?>
        <script>
          // Jika ada elemen notif
          const notif = document.getElementById("notif");
          if (notif) {
            setTimeout(() => {
              notif.style.transition = "opacity 0.5s ease";
              notif.style.opacity = "0";
              setTimeout(() => notif.remove(), 500); // hapus elemen setelah fade out
            }, 2000); // 2000ms = 2 detik
          }
        </script>

      </tbody>
    </table>
  </div>

  <!-- Data Kegiatan -->
  <div class="data-box">
    <h2>Data Kegiatan</h2>
    <div id="listKegiatan">
      <?php
      $result = $conn->query("SELECT * FROM kegiatan ORDER BY id DESC");
      while ($k = $result->fetch_assoc()) {
        echo "<div class='kegiatan-item'>
          <h3>{$k['nama']}</h3>
          <img src='{$k['foto']}' alt='Foto Kegiatan'>
          <a href='admin.php?hapusKegiatan={$k['id']}'>Hapus</a>
        </div>";
      }
      ?>
    </div>
  </div>
</body>

</html>