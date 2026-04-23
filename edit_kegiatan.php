<?php
include "config.php";

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM kegiatan WHERE id=$id");
$kegiatan = $res->fetch_assoc();

if (isset($_POST['updateKegiatan'])) {
  $nama = $_POST['nama'];
  $foto = $_FILES['fotoKegiatan'];

  $targetFile = $kegiatan['foto']; // default foto lama

  // Jika ada file baru
  if ($foto['size'] > 0) {
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (in_array($foto['type'], $allowedTypes) && $foto['size'] < 2000000) {
      if (file_exists($kegiatan['foto'])) unlink($kegiatan['foto']); // hapus foto lama
      $targetDir = "uploads/";
      if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
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
    // Redirect ke admin dan index dengan notif
    header("Location: admin.php?notif=Data kegiatan berhasil diubah");
    exit;
  }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Kegiatan</title></head>
<body>
  <h2>Edit Kegiatan</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" value="<?= $kegiatan['nama'] ?>" required>
    <input type="file" name="fotoKegiatan" accept="image/*">
    <button type="submit" name="updateKegiatan">Update</button>
  </form>
</body>
</html>
