<?php
require_once '../action/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Data Nilai Siswa | (level admin)</title>
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
							<h2><i class="fas fa-clipboard-check"></i> Data Nilai Siswa</h2>
							<div class="breadcrumb-custom">
								<a href="../dashboard"><i class="fas fa-home"></i> Dashboard</a> / Data Nilai
							</div>
						</div>
					</div>

					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-filter"></i> Filter Data</h5>
						</div>

						<div class="row">
							<div class="col-lg-12">
								<form class="form" action="" method="GET">
									<div class="row">
										<div class="col-lg-2">
											<div class="form-group">
												<input type="text" name="detail_ket" value="DNS" hidden>
												<label for="kode_pelajaran">Pelajaran</label>
												<select class="form-control" name="kode_pelajaran" id="kode_pelajaran" onchange="submit()">
													<?php
														echo "<option value=''>-- Pelajaran --</option>";
														$dataPelajaran = $pelajaran->getAll();
														foreach ($dataPelajaran as $key => $value) {
															if (isset($_GET['kode_pelajaran'])) {if ($_GET['kode_pelajaran']==$value['kode_pelajaran']) {$text = "selected";}else{$text = "";}}
															echo "<option value='$value[kode_pelajaran]' $text>$value[pelajaran]</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-2">
											<div class="form-group">
												<label for="kode_kelas">Kelas</label>
												<select class="form-control" name="kode_kelas" id="kode_kelas" onchange="submit()">
													<?php
														echo "<option value=''>-- Pilih Kelas --</option>";
														$dataKelas = $kelas->getAll();
														foreach ($dataKelas as $key => $value) {
															if (isset($_GET['kode_kelas'])) {if ($_GET['kode_kelas']==$value['kode_kelas']) {$text = "selected";}else{$text = "";}}
															echo "<option value='$value[kode_kelas]' $text>$value[tingkat] ($value[kelas])</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-2">
											<div class="form-group">
												<label for="semester">Semester</label>
												<select class="form-control" name="semester" id="semester" onchange="submit()">
													<?php
														echo "<option value=''>-- Semester --</option>";
														$semester = [1,2];
														for ($i=0; $i < count($semester) ; $i++) { 
															if (isset($_GET['semester'])) {if ($_GET['semester']==$semester[$i]) {$text = "selected";}else{$text = "";}}
															echo "<option value='$semester[$i]' $text>Semester $semester[$i]</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-2">
											<div class="form-group">
												<label for="tahun_ajaran">Tahun Ajaran</label>
												<select class="form-control" name="tahun_ajaran" id="tahun_ajaran" onchange="submit()">
													<?php
														echo "<option value=''>-- Tahun Ajaran --</option>";
														$currentYear = date('Y');
														$startYear = 2018;
														for ($i=$currentYear; $i > $startYear-1 ; $i--) { 
															$n = $i + 1;
															if (isset($_GET['tahun_ajaran'])) {if ($_GET['tahun_ajaran']=="$i / $n") {$text = "selected";}else{$text = "";}}
															echo "<option value='$i / $n' $text>$i / $n</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-2">
											<div class="form-group">
												<label for="kode_jurusan">Jurusan</label>
												<select class="form-control" name="kode_jurusan" id="kode_jurusan" onchange="submit()">
													<?php
														echo "<option value=''>-- Jurusan --</option>";
														$dataJurusan = $jurusan->getAll();
														foreach ($dataJurusan as $key => $value) {
															if (isset($_GET['kode_jurusan'])) {if ($_GET['kode_jurusan']==$value['kode_jurusan']) {$text = "selected";}else{$text = "";}}
															echo "<option value='$value[kode_jurusan]' $text>$value[jurusan]</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-lg-2">
											<div class="form-group">
												<label for="tugas_ke">Tugas Ke</label>
												<select class="form-control" name="tugas_ke" id="tugas_ke" onchange="submit()">
													<?php
														echo "<option value=''>-- Tugas Ke --</option>";
														$totalTugas = $hnilai->countDataCustom('keterangan_nilai',['kode_pelajaran','kode_kelas','semester','tahun_ajaran'],[$_GET['kode_pelajaran'],$_GET['kode_kelas'],$_GET['semester'],$_GET['tahun_ajaran']],'tugas')['keterangan_nilai'];
														for ($i=0; $i < $totalTugas ; $i++) { 
															$h = $i + 1;
															if (isset($_GET['tugas_ke'])) {if ($_GET['tugas_ke']=="tugas $h") {$text = "selected";}else{$text = "";}}
															echo "<option value='tugas $h' $text>tugas $h</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

					<table class="display table-custom display" id="dataTables" width="100%">
						<thead>
							<tr class="text-center">
								<th>No</th>
								<th>Nis</th>
								<th>Nama Lengkap</th>
								<th>Tugas</th>
								<th>UTS</th>
								<th>UAS</th>
								<th>Tools</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$dataSiswa = $siswa->getAll();
							if (isset($_GET['detail_ket'])) {
								$dataSiswa = $siswa->getByCondition(['kode_kelas','tahun_ajaran','kode_jurusan'],[$_GET['kode_kelas'],$_GET['tahun_ajaran'],$_GET['kode_jurusan']]);$getTugasId = $hnilai->getByCondition(['keterangan_nilai','kode_kelas','kode_jurusan','kode_pelajaran','semester','tahun_ajaran'],[$_GET['tugas_ke'],$_GET['kode_kelas'],$_GET['kode_jurusan'],$_GET['kode_pelajaran'],$_GET['semester'],$_GET['tahun_ajaran']]);$getUTSId = $hnilai->getByCondition(['keterangan_nilai','kode_kelas','kode_jurusan','kode_pelajaran','semester','tahun_ajaran'],['UTS',$_GET['kode_kelas'],$_GET['kode_jurusan'],$_GET['kode_pelajaran'],$_GET['semester'],$_GET['tahun_ajaran']]);$getUASId = $hnilai->getByCondition(['keterangan_nilai','kode_kelas','kode_jurusan','kode_pelajaran','semester','tahun_ajaran'],['UAS',$_GET['kode_kelas'],$_GET['kode_jurusan'],$_GET['kode_pelajaran'],$_GET['semester'],$_GET['tahun_ajaran']]);
								$tugasId = "";$UTSId = "";$UASId = "";
								foreach ($getTugasId as $key => $value) {$tugasId = $value['id_nilai'];}foreach ($getUTSId as $key => $value) {$UTSId = $value['id_nilai'];}foreach ($getUASId as $key => $value) {$UASId = $value['id_nilai'];}
							}
							$no = 1;
							foreach ($dataSiswa as $row) {
								if (isset($_GET['detail_ket'])) {
									$getNilaiTugas = $dnilai->getByCondition(['id_nilai','nis'],[$tugasId,$row['nis']]);$getNilaiUTS = $dnilai->getByCondition(['id_nilai','nis'],[$UTSId,$row['nis']]);$getNilaiUAS = $dnilai->getByCondition(['id_nilai','nis'],[$UASId,$row['nis']]);
									$nilaiTugas = "";$nilaiUTS = "";$nilaiUAS = "";
									foreach ($getNilaiTugas as $key => $value) {$nilaiTugas = $value['nilai'];}foreach ($getNilaiUTS as $key => $value) {$nilaiUTS = $value['nilai'];}foreach ($getNilaiUAS as $key => $value) {$nilaiUAS = $value['nilai'];}
								}else{
									$nilaiTugas = 0;$nilaiUTS = 0;$nilaiUAS = 0;}
								echo "<tr class='text-center'>";
								echo "<td>" . $no++ . "</td>";
								echo "<td>" . $row['nis'] . "</td>";
								echo "<td>" . $row['nama_depan'] . " " . $row['nama_belakang'] . "</td>";
								echo "<td>" . $nilaiTugas . "</td>";
								echo "<td>" . $nilaiUTS . "</td>";
								echo "<td>" . $nilaiUAS . "</td>";
								echo "<td><a class='btn btn-outline-info' type='button' href='edit.php?nis=" . $row['nis'] . "'>Edit</a></td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>

					<div>
						<a class="btn btn-outline-primary font-weight-bold" type="button" href="input.php">Input Siswa</a>
						<a class="btn btn-outline-danger font-weight-bold" type="button" href="hapus.php">Hapus</a>
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