<?php
require_once '../action/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Data Jurusan | (level admin)</title>
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
							<h2><i class="fas fa-building"></i> Data Jurusan</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / Data Jurusan
							</div>
						</div>
					</div>

					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-list"></i> Daftar Jurusan</h5>
						</div>

						<table class="display table-custom display" id="dataTables" width="100%">
							<thead>
								<tr class="text-center">
									<th>No</th>
									<th>Kode Jurusan</th>
									<th>Jurusan</th>
									<th>Tools</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$dataJurusan = $jurusan->getAll();
								$no = 1;
								foreach ($dataJurusan as $row) {
									echo "<tr class='text-center'>";
									echo "<td>" . $no++ . "</td>";
									echo "<td>" . $row['kode_jurusan'] . "</td>";
									echo "<td>" . $row['jurusan'] . "</td>";
									echo "<td><a class='btn btn-outline-info' type='button' href='edit.php?kode_jurusan=" . $row['kode_jurusan'] . "'>Edit</a></td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>

						<div>
							<a class="btn btn-outline-primary font-weight-bold" type="button" href="input.php">Input Jurusan</a>
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