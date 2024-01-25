<?php

session_start();

include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_pemasukan = $_GET['id_pemasukan'];
    $tgl_pemasukan = $_GET['tgl_pemasukan'];
    $jumlah = $_GET['jumlah'];
    $sumber = $_GET['sumber'];

    $query_update = "UPDATE pemasukan SET tgl_pemasukan='$tgl_pemasukan', jumlah='$jumlah', sumber='$sumber' WHERE id_pemasukan='$id_pemasukan'";

    $result_update = mysqli_query($koneksi, $query_update);

    if ($result_update) {

        $_SESSION['success_message'] = "Data pemasukan berhasil diperbarui.";
        header("Location: pendapatan.php");
        exit();
    } else {
        // Jik
        $_SESSION['error_message'] = "Gagal memperbarui data pemasukan: " . mysqli_error($koneksi);
        header("Location: pendapatan.php");
        exit();
    }
} else {

    header("Location: pendapatan.php");
    exit();
}
