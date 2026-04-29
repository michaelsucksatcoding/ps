<?php
require_once '../action/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Data Kelas | (level admin)</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/custom.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../style/css/dashboard.css">
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
							<h2><i class="fas fa-door-open"></i> Data Kelas</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / Data Kelas
							</div>
						</div>
					</div>

					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-list"></i> Daftar Kelas</h5>
						</div>

						<table class="display table-custom display" id="dataTables" width="100%">
							<thead>
								<tr class="text-center">
									<th>No </th>
									<th>Kode Kelas</th>
									<th>Tingkat</th>
									<th>Kelas</th>
									<th>Wali Kelas</th>
									<th>Tools</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$dataKelas = $kelas->getAll();
								$no = 1;
								foreach ($dataKelas as $row) {
									echo "<tr class='text-center'>";
									echo "<td>" . $no++ . "</td>";
									echo "<td>" . $row['kode_kelas'] . "</td>";
									echo "<td>" . $row['tingkat'] . "</td>";
									echo "<td>" . $row['kelas'] . "</td>";
									echo "<td>" . $guru->getById('kode_guru', $row['kode_guru'])['nama_depan'] . " " . $guru->getById('kode_guru', $row['kode_guru'])['nama_belakang'] . "</td>";
									echo "<td><a class='btn btn-outline-info' type='button' href='edit.php?kode_kelas=" . $row['kode_kelas'] . "'>Edit</a></td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>

						<div>
							<a class="btn btn-outline-primary font-weight-bold" type="button" href="input.php">Input Kelas</a>
							<a class="btn btn-outline-danger font-weight-bold" type="button" href="hapus.php">Hapus</a>
						</div>
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