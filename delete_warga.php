<?php
include "config.php";

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM warga WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
?>