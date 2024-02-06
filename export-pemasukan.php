<?php
// Include the mpdf library
require_once 'vendor/vendor/autoload.php'; // Sesuaikan path sesuai struktur proyek Anda

// Create an instance of the mPDF class
$mpdf = new \Mpdf\Mpdf();

// Start buffering the output
ob_start();

// HTML content for the PDF
?>
<style>
    h1,
    h4 {
        text-align: center;
    }

    hr.custom-line {
        margin-top: 0px;
        /* Atur jarak di atas garis */
        margin-bottom: 10px;
        /* Atur jarak di bawah garis */
        border: 0;
        border-top: 1px solid #000;
        /* Ganti warna dan tipe garis sesuai kebutuhan */
    }

    .right-info {
        float: right;
        text-align: right;
        padding-top: 100px;
    }
</style>

<h1>SD N 14 Hiliran Gumanti</h1>
<h4>Jalan Proklamasi No.mor 05, Talang Babungo <br> Kec. Hiliran Gumanti, Kabupaten Solok, Sumatera Barat</h4>

<hr class="custom-line">

<table border="1" cellpadding="5" style="margin: 0 auto;">
    <tr>
        <th>No</th>
        <th>Tgl Pemasukan</th>
        <th>Jumlah</th>
        <th>Sumber</th>
    </tr>
    <?php
    // Load file koneksi.php
    include "koneksi.php";
    // Buat query untuk menampilkan semua data siswa
    $query = mysqli_query($koneksi, "SELECT * FROM pemasukan");
    // Untuk penomoran tabel, di awal set dengan 1
    while ($data = mysqli_fetch_array($query)) {
        // Ambil semua data dari hasil eksekusi $sql
        echo "<tr>";
        echo "<td>" . $data['id_pemasukan'] . "</td>";
        echo "<td>" . $data['tgl_pemasukan'] . "</td>";
        echo "<td>" . $data['jumlah'] . "</td>";
        echo "<td>" . $data['sumber'] . "</td>";
        echo "</tr>";
    } ?>
</table>

<div class="right-info" style="text-align: right;">
    <p style="text-align: right; padding-right:108px;">Solok, <?php echo date('d F Y'); ?></p>
    <p style="text-align: right;">Kepala Sekolah SDN 14 Hiliran Gumanti</p>
    <br>
    <p style="text-align: right; padding-right:170px;">Kepala Sekolah</p>
</div>

<?php

// Get the buffered content
$html = ob_get_clean();

// Add the HTML content to the PDF
$mpdf->WriteHTML($html);

// Set PDF headers
$mpdf->Output('Data_Pemasukan.pdf', 'D'); // 'D' option will force a download

// Exit to prevent any additional output
exit;
?>
