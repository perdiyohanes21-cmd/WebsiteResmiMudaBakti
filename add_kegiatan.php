<?php
include "config.php";

$nama = $_POST['nama'];
$foto = $_FILES['foto'];

$targetDir = "uploads/";
if (!is_dir($targetDir))
    mkdir($targetDir);

$targetFile = $targetDir . time() . "_" . basename($foto["name"]);
move_uploaded_file($foto["tmp_name"], $targetFile);

$stmt = $conn->prepare("INSERT INTO kegiatan (nama, foto) VALUES (?, ?)");
$stmt->bind_param("ss", $nama, $targetFile);
$stmt->execute();
$stmt->close();
?>