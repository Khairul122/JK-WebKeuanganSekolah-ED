<?php
require 'cek-sesi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - Admin</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <?php
    require 'koneksi.php';
    require('sidebar.php');

    $sekarang = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE()");
    $sekarang = mysqli_fetch_array($sekarang);

    $satuhari = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE() - INTERVAL 1 DAY");
    $satuhari = mysqli_fetch_array($satuhari);


    $duahari = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE() - INTERVAL 2 DAY");
    $duahari = mysqli_fetch_array($duahari);

    $tigahari = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE() - INTERVAL 3 DAY");
    $tigahari = mysqli_fetch_array($tigahari);

    $empathari = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE() - INTERVAL 4 DAY");
    $empathari = mysqli_fetch_array($empathari);

    $limahari = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE() - INTERVAL 5 DAY");
    $limahari = mysqli_fetch_array($limahari);

    $enamhari = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE() - INTERVAL 6 DAY");
    $enamhari = mysqli_fetch_array($enamhari);

    $tujuhhari = mysqli_query($koneksi, "SELECT jumlah FROM pengeluaran
WHERE tgl_pengeluaran = CURDATE() - INTERVAL 7 DAY");
    $tujuhhari = mysqli_fetch_array($tujuhhari);
    ?>
    <!-- Main Content -->
    <div id="content">

        <?php require('navbar.php'); ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->
            <div class="row">

                <!-- Content Column -->
                <div class="col-lg-6 mb-4">

                    <!-- Project Card Example -->

                </div>



            </div>


            <!-- Area Chart -->

            <!-- DataTales Example -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Arus Kas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
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
                                echo '<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">';
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th>No</th>';
                                echo '<th>Tanggal</th>';
                                echo '<th>Deskripsi</th>';
                                echo '<th>Uang Masuk</th>';
                                echo '<th>Uang Keluar</th>';
                                echo '<th>Saldo Akhir</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';

                                // Tampilkan data pemasukan dan pengeluaran
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

                                echo '</tbody>';
                                echo '</table>';

                                // Tutup koneksi (jika tidak menggunakan persistent connection)
                                mysqli_close($koneksi);
                                ?>


                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <?php require 'footer.php' ?>

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require 'logout-modal.php'; ?>

    <!-- Bootstrap core JavaScript-->

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <!-- Page level custom scripts -->
    <script type="text/javascript">
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["7 hari lalu", "6 hari lalu", "5 hari lalu", "4 hari lalu", "3 hari lalu", "2 hari lalu", "1 hari lalu"],
                datasets: [{
                    label: "Pendapatan",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [<?php echo $tujuhhari['0'] ?>, <?php echo $enamhari['0'] ?>, <?php echo $limahari['0'] ?>, <?php echo $empathari['0'] ?>, <?php echo $tigahari['0'] ?>, <?php echo $duahari['0'] ?>, <?php echo $satuhari['0'] ?>],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return 'Rp.' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': Rp.' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    </script>


    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
