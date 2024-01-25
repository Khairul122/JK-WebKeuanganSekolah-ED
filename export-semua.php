    <?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Data_Pemasukan_Pengeluaran.xls");
	?>
    <style>
    	h1,
    	h4 {
    		text-align: center;
    	}

    	hr.custom-line {
    		margin-top: 0px;
    		/* Atur jarak di atas garis */
    		margin-bottom: 20px;
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

    <h1>Alfara Motor</h1>
    <h4>Jl. Sutan Syahrir No.215, Mata Air</h4>
    <h4>Kec. Padang Selatan., Kota Padang, Sumatera Barat 25121</h4>
    <hr class="custom-line">
    <table border="1" cellpadding="5">
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
		}  ?>
    </table>
    <br>
    <br>
    <h3>Data Pengeluaran</h3>
    <table border="1" cellpadding="5">
    	<tr>
    		<th>No</th>
    		<th>Tgl Pengeluaran</th>
    		<th>Jumlah</th>
    		<th>Sumber</th>
    	</tr>
    	<?php
		// Buat query untuk menampilkan semua data siswa 
		$query = mysqli_query($koneksi, "SELECT * FROM pengeluaran");
		// Untuk penomoran tabel, di awal set dengan 1 
		while ($data = mysqli_fetch_array($query)) {
			// Ambil semua data dari hasil eksekusi $sql 
			echo "<tr>";
			echo "<td>" . $data['id_pengeluaran'] . "</td>";
			echo "<td>" . $data['tgl_pengeluaran'] . "</td>";
			echo "<td>" . $data['jumlah'] . "</td>";
			echo "<td>" . $data['sumber'] . "</td>";
			echo "</tr>";
		}  ?>
    </table>

    <div class="right-info">
    	<p style="padding-right:90px;">Padang, <?php echo date('Y-m-d'); ?></p>
    	<p>Pimpinan Toko Alfara Motor</p>
    	<br>
    	<p style="padding-right:170px;">Pimpinan</p>
    </div>