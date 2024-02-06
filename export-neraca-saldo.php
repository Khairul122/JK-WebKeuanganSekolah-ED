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
            <th>Keterangan</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require 'koneksi.php';

        // Query untuk mengambil data pemasukan
        $query_pemasukan = "SELECT sumber, jumlah, status FROM pemasukan";
        $result_pemasukan = mysqli_query($koneksi, $query_pemasukan);

        // Query untuk mengambil data pengeluaran
        $query_pengeluaran = "SELECT sumber, jumlah, status FROM pengeluaran";
        $result_pengeluaran = mysqli_query($koneksi, $query_pengeluaran);

        $no = 1;
        $total_debit = 0;
        $total_kredit = 0;

        // Tampilkan data pada tabel
        while (($row_pemasukan = mysqli_fetch_assoc($result_pemasukan)) || ($row_pengeluaran = mysqli_fetch_assoc($result_pengeluaran))) {
            echo '<tr>';
            echo '<td>' . $no . '</td>';

            if ($row_pemasukan) {
                echo '<td>' . $row_pemasukan['sumber'] . '</td>';
                if ($row_pemasukan['status'] == 1) {
                    echo '<td>' . $row_pemasukan['jumlah'] . '</td>';
                    echo '<td></td>';
                    $total_debit += $row_pemasukan['jumlah'];
                } elseif ($row_pemasukan['status'] == 2) {
                    echo '<td></td>';
                    echo '<td>' . $row_pemasukan['jumlah'] . '</td>';
                    $total_kredit += $row_pemasukan['jumlah'];
                }
            } else {
                echo '<td>' . $row_pengeluaran['sumber'] . '</td>';
                if ($row_pengeluaran['status'] == 1) {
                    echo '<td>' . $row_pengeluaran['jumlah'] . '</td>';
                    echo '<td></td>';
                    $total_debit += $row_pengeluaran['jumlah'];
                } elseif ($row_pengeluaran['status'] == 2) {
                    echo '<td></td>';
                    echo '<td>' . $row_pengeluaran['jumlah'] . '</td>';
                    $total_kredit += $row_pengeluaran['jumlah'];
                }
            }

            echo '</tr>';
            $no++;
        }

        // Tampilkan total pada baris terakhir
        echo '<tr>';
        echo '<td colspan="2" style="text-align:center;">Total</td>';
        echo '<td>' . $total_debit . '</td>';
        echo '<td>' . $total_kredit . '</td>';
        echo '</tr>';

        // Tampilkan status Balance/Tidak Balance
        echo '<tr>';
        echo '<td colspan="4" style="text-align:center;">';
        if ($total_kredit > $total_debit) {
            echo 'Tidak Balance';
        } elseif ($total_debit > $total_kredit) {
            echo 'Balance';
        } else {
            echo 'Balance';
        }
        echo '</td>';
        echo '</tr>';
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
$mpdf->Output('Laporan Neraca Saldo.pdf', 'D'); // 'D' option will force a download

// Exit to prevent any additional output
exit;
?>
