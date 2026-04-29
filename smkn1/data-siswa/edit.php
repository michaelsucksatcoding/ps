<?php
require_once "../action/config.php";
include "proses_edit.php";

$dataSiswa = $siswa->getById('nis',$_GET['nis']);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Siswa | SMKN 1 Kota Bengkulu</title>
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
							<h2><i class="fas fa-user-edit"></i> Edit Siswa</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / <a href="index.php">Data Siswa</a> / Edit
							</div>
						</div>
					</div>

					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-edit"></i> Form Edit Siswa</h5>
						</div>

						<form class="form" action="" method="POST">
							<div class="row justify-content-center">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="nis">NIS :</label>
										<input class="form-control" type="text" name="nis" id="nis" value="<?php echo $dataSiswa['nis']?>">
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-3">
									<div class="form-group">
										<label for="nama_depan">Nama Depan :</label>
										<input class="form-control" type="text" name="nama_depan" id="nama_depan" value="<?php echo $dataSiswa['nama_depan']?>">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="nama_belakang">Nama Belakang :</label>
										<input class="form-control" type="text" name="nama_belakang" id="nama_belakang" value="<?php echo $dataSiswa['nama_belakang']?>">
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-3">
									<div class="form-group">
										<label for="kode_kelas">Kelas :</label>
										<select class="form-control" name="kode_kelas" id="kode_kelas">
											<?php
											foreach ($kelas->getAll() as $row) {
												if ($dataSiswa['kode_kelas']==$row['kode_kelas']) {$selected = "selected";}else{$selected = "";}
												echo "<option value='$row[kode_kelas]' $selected>$row[tingkat] ($row[kelas])</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="kode_jurusan">Jurusan :</label>
										<select class="form-control" name="kode_jurusan" id="kode_jurusan">
											<?php
											foreach ($jurusan->getAll() as $row) {
												if ($dataSiswa['kode_kelas']==$row['kode_jurusan']) {$selected = "selected";}else{$selected = "";}
												echo "<option value='$row[kode_jurusan]' $selected>$row[jurusan]</option>";
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-3">
									<div class="form-group">
										<label for="tempat">Tempat :</label>
										<input class="form-control" type="text" name="tempat" id="tempat" value="<?php echo $dataSiswa['tempat']?>">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="tgl_lahir">Tanggal Lahir :</label>
										<input class="form-control" type="date" name="tgl_lahir" id="tgl_lahir" value="<?php echo $dataSiswa['tgl_lahir']?>">
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
											$checked = "";
											if ($dataSiswa['jenis_kelamin'] == $jkValue[$i]) {$checked = "checked";}
											echo "<div class='form-check-inline ml-3'>";
											echo "<label class='form-check-label'>";
											echo "<input class='form-check-input' type='radio' name='jenis_kelamin' value='$jkValue[$i]' $checked>$jkLabel[$i]";
											echo "</label></div>";
										}
										?>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="nama_ortu">Nama Orang Tua :</label>
										<input class="form-control" type="text" name="nama_ortu" id="nama_ortu" value="<?php echo $dataSiswa['nama_ortu']?>">
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="agama">Agama :</label>
										<?php
										$agama = ['Islam','Protestan','Katolik','Hindu','Budha'];
										for ($i=0; $i < count($agama) ; $i++) { 
											if ($dataSiswa['agama']==$agama[$i]) {$checked="checked";}else{$checked="";}
											echo "<div class='form-check-inline ml-2'>";
											echo "<label class='form-check-label'>";
											echo "<input class='form-check-input' type='radio' name='agama' value='$agama[$i]' $checked>$agama[$i]";
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
										<textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5"><?php echo $dataSiswa['alamat']?></textarea>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-3">
									<div class="form-group">
										<label for="password">Password :</label>
										<input class="form-control" type="text" name="password" id="password" value="<?php echo $dataSiswa['password']?>">
										<p style="color:red;font-size:70%;">!!! Password Di Encrypt</p>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="tahun_ajaran">Tahun Ajaran :</label>
										<select class="form-control" name="tahun_ajaran">
											<?php
											$awaltahun = 2018;
											$tahunsekarang = Date('Y');
											for ($i=$tahunsekarang+1; $i > $awaltahun ; $i--) {
												$h = $i-1;
												if ($dataSiswa['tahun_ajaran']=="$h / $i") {$selected = "selected";}else{$selected = "";}
												echo "<option value='$h / $i' $selected>$h / $i</option>";
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="row justify-content-center mb-5">
								<div class="col-lg-6">
									<div class="form-group">
										<input class="btn btn-outline-primary font-weight-bold" type="submit" name="edit-siswa" value="Simpan">
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