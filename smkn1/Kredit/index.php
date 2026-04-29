<!DOCTYPE html>
<html>
<head>
	<title>Halaman Kredit | SMKN 1 Kota Bengkulu</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- link ke bootstrap css -->
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/custom.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../style/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="../style/css/style.css">
	
</head>
<body>

	<!-- top navbar admin -->
	<?php include '../include/navbar-admin.php'; ?>

	<div class="kotak-konten pt-4">
		<div class="row pt-3 justify-content-end">
			<div class="sidebar col bg-primary" id="sidebar">
				<?php include '../include/sidebar-admin.php'; ?>
			</div>
			<div class="konten pt-5 pl-5 pr-5" id="konten">
				
					
					<!-- Page Heading -->
					<div class="page-heading">
						<div>
							<h2><i class="fas fa-users"></i> Kredit</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / Kredit
							</div>
						</div>
					</div>

					<!-- Credit Content -->
					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-user-friends"></i> Tim Pengembang</h5>
							<span class="badge-count">4 Anggota</span>
						</div>
						
						<p class="text-center text-muted mb-4">Aplikasi Pengolahan Nilai Siswa<br>SMKN 1 Kota Bengkulu</p>
							
						<table class="table-custom display" width="100%">
							<thead>
								<tr>
									<th width="10%">No</th>
									<th>Nama Anggota</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center">1</td>
									<td>Ataya Firjatullah Sihite</td>
								</tr>
								<tr>
									<td class="text-center">2</td>
									<td>Michael Trisatrio Mukti</td>
								</tr>
								<tr>
									<td class="text-center">3</td>
									<td>Ramadhan Eko Setianto</td>
								</tr>
								<tr>
									<td class="text-center">4</td>
									<td>Zakia Kurnia Putri</td>
								</tr>
							</tbody>
						</table>
						
						<div class="text-center mt-4">
							<small class="text-muted">Made from SMKN 1 Kota Bengkulu 2026 ©</small>
						</div>
					</div>

				
			</div>
		</div>
	</div>

	<!-- memanggil jquery, popper, dan bootstrap js -->
	<script type="text/javascript" src="../bootstrap/js/jquery.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/popper.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../style/js/style.js"></script>

</body>
</html>