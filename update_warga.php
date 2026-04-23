<?php
include "config.php";

$id = intval($_POST['id']);
$field = $_POST['field'];
$value = $_POST['value'];

// validasi field
$allowed = ["nama", "umur", "jk"];
if (!in_array($field, $allowed))
    exit("Field tidak valid");

$stmt = $conn->prepare("UPDATE warga SET $field=? WHERE id=?");
$stmt->bind_param("si", $value, $id);
$stmt->execute();
$stmt->close();
?>