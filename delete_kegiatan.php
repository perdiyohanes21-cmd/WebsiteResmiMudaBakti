<?php
include "config.php";

$id = intval($_GET['id']);

// hapus file foto juga
$res = $conn->query("SELECT foto FROM kegiatan WHERE id=$id");
if ($row = $res->fetch_assoc()) {
    if (file_exists($row['foto']))
        unlink($row['foto']);
}

$stmt = $conn->prepare("DELETE FROM kegiatan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
?>