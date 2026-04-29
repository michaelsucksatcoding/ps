<?php
require_once '../action/config.php';
include "proses_input.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Input Admin | SMKN 1 Kota Bengkulu</title>
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
							<h2><i class="fas fa-user-plus"></i> Input Admin</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / <a href="index.php">Data Admin</a> / Input
							</div>
						</div>
					</div>

					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-plus-circle"></i> Form Input Admin</h5>
						</div>

						<form class="form" action="" method="POST">
							<div class="row justify-content-center">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="id_admin">ID Admin :</label>
										<input class="form-control" type="text" name="id_admin" id="id_admin">
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-3">
									<div class="form-group">
										<label for="nama_depan">Nama Depan :</label>
										<input class="form-control" type="text" name="nama_depan" id="nama_depan">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="nama_belakang">Nama Belakang :</label>
										<input class="form-control" type="text" name="nama_belakang" id="nama_belakang">
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-3">
									<div class="form-group">
										<label for="tempat">Tempat :</label>
										<input class="form-control" type="text" name="tempat" id="tempat">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="tgl_lahir">Tanggal Lahir :</label>
										<input class="form-control" type="date" name="tgl_lahir" id="tgl_lahir">
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-3">
									<div class="form-group">
										<label>Jenis Kelamin :</label><br>
										<?php
										$jkValue = ['L','P'];
										$jkLabel = ['Laki-laki','Perempuan'];
										for ($i=0; $i < count($jkLabel) ; $i++) { 
											echo "<div class='form-check-inline'>";
											echo "<label class='form-check-label'>";
											echo "<input class='form-check-input' type='radio' name='jenis_kelamin' value='$jkValue[$i]'>$jkLabel[$i]";
											echo "</label></div>";
										}
										?>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label>Level Admin :</label><br>
										<?php
										$lvlAdmin = ['Admin','Super Admin'];
										for ($i=0; $i < count($lvlAdmin) ; $i++) { 
											echo "<div class='form-check-inline'>";
											echo "<label class='form-check-label'>";
											echo "<input class='form-check-input' type='radio' name='level_admin' value='$lvlAdmin[$i]'>$lvlAdmin[$i]";
											echo "</label></div>";
										}
										?>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="alamat">Alamat :</label>
										<textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5"></textarea>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="password">Password :</label>
										<input class="form-control" type="text" name="password" id="password">
									</div>
								</div>
							</div>
							<div class="row justify-content-center mb-5">
								<div class="col-lg-6">
									<div class="form-group">
										<input class="btn btn-outline-primary font-weight-bold" type="submit" name="input-admin" value="Simpan">
									</div>
								</div>
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