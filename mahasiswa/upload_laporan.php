<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_laporan'])) {
    $id_modul = $_POST['id_modul'];
    $id_praktikum = $_POST['id_praktikum'];
    $id_mahasiswa = $_SESSION['user_id'];

    if ($_FILES['file_laporan']['error'] == 0) {
        $target_dir = "../uploads/laporan/";
        $file_extension = pathinfo($_FILES["file_laporan"]["name"], PATHINFO_EXTENSION);
        $file_laporan = "laporan_" . $id_modul . "_" . $id_mahasiswa . "_" . time() . "." . $file_extension;
        
        if (move_uploaded_file($_FILES["file_laporan"]["tmp_name"], $target_dir . $file_laporan)) {
            $stmt = $conn->prepare("INSERT INTO laporan (id_modul, id_mahasiswa, file_laporan) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $id_modul, $id_mahasiswa, $file_laporan);
            $stmt->execute();
            $stmt->close();
            header("Location: detail_praktikum.php?id=$id_praktikum&upload=sukses");
            exit();
        }
    }
}
$id_praktikum = $_POST['id_praktikum'] ?? 0;
header("Location: detail_praktikum.php?id=$id_praktikum&upload=gagal");
exit();
?>