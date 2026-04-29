<?php
require_once '../action/config.php';
include 'proses_hapus.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hapus Nilai | SMKN 1 Kota Bengkulu</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/custom.css">
	<link rel="stylesheet" type="text/css" href="../style/css/style.css">
</head>
<body>

	<?php include '../include/navbar-admin.php'; ?>

	<div class="kotak-konten pt-4">
		<div class="row pt-3 justify-content-end">
			<div class="sidebar col bg-primary" id="sidebar">
				<?php include '../include/sidebar-admin.php'; ?>
			</div>
			<div class="konten pt-5 pl-5 pr-5" id="konten">
				
					
					<div class="page-heading">
						<div>
							<h2><i class="fas fa-trash-alt"></i> Hapus Nilai</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / <a href="index.php">Data Nilai</a> / Hapus
							</div>
						</div>
					</div>

					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-list"></i> Pilih Nilai yang Akan Dihapus</h5>
						</div>

						<form class="form" action="" method="POST">
							<table class="table-custom display" id="dataTables" width="100%">
								<thead>
									<tr class="text-center">
										<th>No</th>
										<th>ID Nilai</th>
										<th>Keterangan</th>
										<th>Pelajaran</th>
										<th>Kelas</th>
										<th>Semester</th>
										<th>Tahun Ajaran</th>
										<th>Tools</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$dataNilai = $hnilai->getAll();
									$no = 1;
									foreach ($dataNilai as $row) {
										// Ambil nama pelajaran
										$dataPelajaran = $pelajaran->getById('kode_pelajaran', $row['kode_pelajaran']);
										$namaPelajaran = $dataPelajaran ? $dataPelajaran['pelajaran'] : '-';
										
										// Ambil nama kelas
										$dataKelas = $kelas->getById('kode_kelas', $row['kode_kelas']);
										$namaKelas = $dataKelas ? $dataKelas['tingkat'] . ' ' . $dataKelas['kelas'] : '-';
										
										echo "<tr class='text-center'>";
										echo "<td>" . $no++ . "</td>";
										echo "<td>" . $row['id_nilai'] . "</td>";
										echo "<td>" . $row['keterangan_nilai'] . "</td>";
										echo "<td>" . $namaPelajaran . "</td>";
										echo "<td>" . $namaKelas . "</td>";
										echo "<td>" . $row['semester'] . "</td>";
										echo "<td>" . $row['tahun_ajaran'] . "</td>";
										echo "<td>";
										echo "<div class='form-check'>";
										echo "<input class='form-check-input' type='checkbox' name='id_nilai[]' value='" . $row['id_nilai'] . "'>";
										echo "</div>";
										echo "</td>";
										echo "</tr>";
									}
									?>
								</tbody>
							</table>
							<div>
								<input class="btn btn-outline-danger font-weight-bold" type="submit" name="hapus-nilai" value="Hapus">
							</div>
						</form>

					</div>
				
			</div>
		</div>
	</div>

	<script type="text/javascript" src="../bootstrap/js/jquery.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/popper.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/dataTables.min.js"></script>
	<script type="text/javascript" src="../style/js/style.js"></script>
</body>
</html>