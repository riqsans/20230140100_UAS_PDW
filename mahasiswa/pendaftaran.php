<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location:../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_praktikum = intval($_GET['id']);
    $id_mahasiswa = $_SESSION['user_id'];

    $stmt_check = $conn->prepare("SELECT id FROM pendaftaran_praktikum WHERE id_mahasiswa = ? AND id_praktikum = ?");
    $stmt_check->bind_param("ii", $id_mahasiswa, $id_praktikum);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows == 0) {
        $stmt_insert = $conn->prepare("INSERT INTO pendaftaran_praktikum (id_mahasiswa, id_praktikum) VALUES (?, ?)");
        $stmt_insert->bind_param("ii", $id_mahasiswa, $id_praktikum);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $stmt_check->close();
    $conn->close();
    header("Location:mycourse.php?status=sukses");
    exit();
} else {
    header("Location:course.php");
    exit();
}
?>