<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "nama_database");
$result = $conn->query("SELECT nama, foto FROM kegiatan");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>