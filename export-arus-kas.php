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
        margin-bottom: 20px;
        border: 0;
        border-top: 1px solid #000;
    }

    .right-info {
        float: right;
        text-align: right;
        padding-top: 100px;
    }

    /* Tambahkan gaya untuk tabel */
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px 0;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }
</style>

<h1>SD N 14 Hiliran Gumanti</h1>
<h4>Jalan Proklamasi No.mor 05, Talang Babungo <br> Kec. Hiliran Gumanti, Kabupaten Solok, Sumatera Barat</h4>

<hr class="custom-line">

<!-- Tabel data -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Deskripsi</th>
            <th>Uang Masuk</th>
            <th>Uang Keluar</th>
            <th>Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require 'koneksi.php';

        // Query untuk mengambil data pemasukan
        $query_pemasukan = "SELECT tgl_pemasukan AS tanggal, sumber AS deskripsi, jumlah AS uang_masuk, '' AS uang_keluar FROM pemasukan";
        $result_pemasukan = mysqli_query($koneksi, $query_pemasukan);

        // Query untuk mengambil data pengeluaran
        $query_pengeluaran = "SELECT tgl_pengeluaran AS tanggal, sumber AS deskripsi, '' AS uang_masuk, jumlah AS uang_keluar FROM pengeluaran";
        $result_pengeluaran = mysqli_query($koneksi, $query_pengeluaran);

        // Menggabungkan hasil query pemasukan dan pengeluaran
        $result_combined = array();
        while ($row_pemasukan = mysqli_fetch_assoc($result_pemasukan)) {
            $result_combined[] = $row_pemasukan;
        }
        while ($row_pengeluaran = mysqli_fetch_assoc($result_pengeluaran)) {
            $result_combined[] = $row_pengeluaran;
        }

        // Fungsi untuk mengurutkan array multidimensi berdasarkan tanggal secara descending
        usort($result_combined, function ($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        // Inisialisasi variabel saldo akhir
        $saldo_akhir = 0;

        // Tampilkan data pada tabel
        $no = 1;
        foreach ($result_combined as $row) {
            echo '<tr>';
            echo '<td>' . $no . '</td>';
            echo '<td>' . date('d F Y', strtotime($row['tanggal'])) . '</td>';
            echo '<td>' . $row['deskripsi'] . '</td>';
            echo '<td>' . ($row['uang_masuk'] !== '' ? $row['uang_masuk'] : '-') . '</td>';
            echo '<td>' . ($row['uang_keluar'] !== '' ? $row['uang_keluar'] : '-') . '</td>';

            // Menghitung saldo akhir
            $saldo_akhir += (is_numeric($row['uang_masuk']) ? $row['uang_masuk'] : 0) - (is_numeric($row['uang_keluar']) ? $row['uang_keluar'] : 0);

            echo '<td>' . $saldo_akhir . '</td>';
            echo '</tr>';
            $no++;
        }

        // Hitung total
        $total_masuk = array_sum(array_column($result_combined, 'uang_masuk'));
        $total_keluar = array_sum(array_column($result_combined, 'uang_keluar'));

        // Tampilkan total di bawah tabel
        echo '<tr>';
        echo '<td colspan="3" style="text-align:center;">Total</td>';
        echo '<td>' . $total_masuk . '</td>';
        echo '<td>' . $total_keluar . '</td>';
        echo '<td>' . $saldo_akhir . '</td>';
        echo '</tr>';

        mysqli_close($koneksi);
        ?>
    </tbody>
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
$mpdf->Output('Laporan Arus Kas.pdf', 'D'); // 'D' option will force a download

// Exit to prevent any additional output
exit;
?>
