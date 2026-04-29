<?php
require_once '../action/config.php';
include 'proses_hapus.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hapus Siswa | SMKN 1 Kota Bengkulu</title>
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
							<h2><i class="fas fa-trash-alt"></i> Hapus Siswa</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / <a href="index.php">Data Siswa</a> / Hapus
							</div>
						</div>
					</div>

					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-list"></i> Pilih Siswa yang Akan Dihapus</h5>
						</div>

						<form class="form" action="" method="POST">
							<table class="table-custom display" id="dataTables" width="100%">
								<thead>
									<tr class="text-center">
										<th>No</th>
										<th>NIS</th>
										<th>Nama Lengkap</th>
										<th>Jenis Kelamin</th>
										<th>Tools</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$dataSiswa = $siswa->getAll();
									$no = 1;
									foreach ($dataSiswa as $row) {
										$jk = "";
										if ($row['jenis_kelamin']=="L") {$jk = "Laki-laki";}else{$jk = "Perempuan";}
										echo "<tr class='text-center'>";
										echo "<td>" . $no++ . "</td>";
										echo "<td>" . $row['nis'] . "</td>";
										echo "<td>" . $row['nama_depan'] . " " . $row['nama_belakang'] . "</td>";
										echo "<td>" . $jk . "</td>";
										echo "<td>";
										echo "<div class='form-check'>";
										echo "<input class='form-check-input' type='checkbox' name='nis[]' value='" . $row['nis'] . "'>";
										echo "</div>";
										echo "</td>";
										echo "</tr>";
									}
									?>
								</tbody>
							</table>
							<div>
								<input class="btn btn-outline-danger font-weight-bold" type="submit" name="hapus-siswa" value="Hapus">
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