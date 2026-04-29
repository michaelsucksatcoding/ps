<?php
require_once "model.php";

/** @var mysqli $koneksi */
if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
	die("Koneksi database tidak tersedia.");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard Admin | SMKN 1 Kota Bengkulu</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/custom.css">
	
	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	
	<!-- Custom Dashboard CSS -->
	<link rel="stylesheet" type="text/css" href="../style/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="../style/css/style.css">
</head>
<body>

	<?php include '../include/navbar-admin.php'; ?>

	<div class="kotak-konten pt-4">
		<div class="row pt-3 justify-content-end">
			
			<!-- Sidebar -->
			<div class="sidebar col bg-primary" id="sidebar">
				<?php include '../include/sidebar-admin.php'; ?>
			</div>

			<!-- Main Content -->
			<div class="konten bg-kastem pt-5 pl-5" id="konten">
				
					
					<!-- Page Heading -->
					<div class="page-heading">
						<div>
							<h2><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h2>
							<div class="breadcrumb-custom">
								<a href="#"><i class="fas fa-home"></i> Home</a> / Dashboard
							</div>
						</div>
					</div>
					
					<!-- Statistics Cards -->
					<div class="stat-cards">
						<!-- Guru -->
						<div class="stat-card guru fade-in-up">
							<div class="icon-circle"><i class="fas fa-chalkboard-teacher"></i></div>
							<div class="stat-label">Total Guru</div>
							<div class="stat-value"><?= getTotalGuru($koneksi) ?></div>
							<div class="stat-desc"><i class="fas fa-users"></i> Tenaga Pengajar</div>
						</div>
						
						<!-- Siswa -->
						<div class="stat-card siswa fade-in-up">
							<div class="icon-circle"><i class="fas fa-user-graduate"></i></div>
							<div class="stat-label">Total Siswa</div>
							<div class="stat-value"><?= getTotalSiswa($koneksi) ?></div>
							<div class="stat-desc"><i class="fas fa-school"></i> Peserta Didik</div>
						</div>
						
						<!-- Mapel -->
						<div class="stat-card mapel fade-in-up">
							<div class="icon-circle"><i class="fas fa-book"></i></div>
							<div class="stat-label">Mata Pelajaran</div>
							<div class="stat-value"><?= getTotalMapel($koneksi) ?></div>
							<div class="stat-desc"><i class="fas fa-clipboard-list"></i> Total Mapel</div>
						</div>
						
						<!-- Rata-rata -->
						<?php $stats = getStatistik(); ?>
						<div class="stat-card rata fade-in-up">
							<div class="icon-circle"><i class="fas fa-chart-line"></i></div>
							<div class="stat-label">Rata-rata Nilai</div>
							<div class="stat-value"><?= number_format($stats['rata_rata'], 1) ?></div>
							<div class="stat-desc"><i class="fas fa-star"></i> Keseluruhan</div>
						</div>
					</div>

					<!-- Tabel Data Siswa -->
					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-user-graduate"></i> Data Siswa</h5>
							<span class="badge-count"><?= getTotalSiswa($koneksi) ?> Siswa</span>
						</div>
						<div class="table-responsive">
							<table id="tabelSiswa" class="table-custom display" width="100%">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th width="12%">NIS</th>
										<th width="25%">Nama Siswa</th>
										<th width="15%">Kelas</th>
										<th width="15%">Jurusan</th>
										<th width="15%">Gender</th>
										<th width="13%">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$siswa = getSemuaSiswa();
									$no = 1;
									foreach($siswa as $s): 
									?>
									<tr>
										<td class="text-center"><?= $no++ ?></td>
										<td><strong><?= htmlspecialchars($s['nis']) ?></strong></td>
										<td>
											<span class="avatar-circle <?= $s['jenis_kelamin'] == 'L' ? 'avatar-male' : 'avatar-female' ?>">
												<?= strtoupper(substr($s['nama_siswa'], 0, 1)) ?>
											</span>
											<?= htmlspecialchars($s['nama_siswa']) ?>
										</td>
										<td><?= htmlspecialchars($s['kelas']) ?></td>
										<td><?= htmlspecialchars($s['jurusan']) ?></td>
										<td>
											<span class="<?= $s['jenis_kelamin'] == 'L' ? 'gender-male' : 'gender-female' ?>">
												<i class="fas <?= $s['jenis_kelamin'] == 'L' ? 'fa-male' : 'fa-female' ?>"></i>
												<?= $s['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
											</span>
										</td>
										<td>
											<a href="../data-nilai?nis=<?= $s['nis'] ?>"
												class="btn-action">
												<i class="fas fa-eye"></i> Nilai
											</a>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php if(empty($siswa)): ?>
									<tr>
										<td colspan="7">
											<div class="empty-state">
												<i class="fas fa-inbox"></i>
												<p>Belum ada data siswa</p>
											</div>
										</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- Tabel Rekapan Nilai -->
					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-chart-bar"></i> Rekapan Nilai</h5>
							<span class="badge-count">Status Ketuntasan</span>
						</div>
						<div class="table-responsive">
							<table id="tabelRekap" class="table-custom display" width="100%">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th width="10%">NIS</th>
										<th width="20%">Nama</th>
										<th width="10%">Kelas</th>
										<th width="10%">Jurusan</th>
										<th width="8%">Mapel</th>
										<th width="12%">Rata²</th>
										<th width="8%">Tuntas</th>
										<th width="8%">Remidi</th>
										<th width="9%">Status</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$rekap = getRekapNilai();
									$no = 1;
									foreach($rekap as $r): 
										if ($r['belum_tuntas'] == 0) {
											$status = 'Lulus Semua';
											$badgeClass = 'badge-tuntas';
										} elseif ($r['belum_tuntas'] <= 2) {
											$status = 'Perlu Remidi';
											$badgeClass = 'badge-warning-status';
										} else {
											$status = 'Butuh Perhatian';
											$badgeClass = 'badge-remidi';
										}
										$progressClass = $r['rata_rata'] >= 80 ? 'progress-high' : ($r['rata_rata'] >= 70 ? 'progress-medium' : 'progress-low');
									?>
									<tr>
										<td class="text-center"><?= $no++ ?></td>
										<td><strong><?= htmlspecialchars($r['nis']) ?></strong></td>
										<td><?= htmlspecialchars($r['nama_siswa']) ?></td>
										<td><?= htmlspecialchars($r['kelas']) ?></td>
										<td><?= htmlspecialchars($r['jurusan']) ?></td>
										<td class="text-center"><?= $r['jumlah_mapel'] ?></td>
										<td>
											<div style="display: flex; align-items: center; gap: 8px;">
												<div class="progress-mini">
													<div class="progress-bar <?= $progressClass ?>" 
													     style="width: <?= min(100, $r['rata_rata']) ?>%"></div>
												</div>
												<strong style="font-size: 12px;"><?= number_format($r['rata_rata'], 1) ?></strong>
											</div>
										</td>
										<td class="text-center"><span class="badge-custom badge-tuntas"><?= $r['tuntas'] ?></span></td>
										<td class="text-center"><span class="badge-custom badge-remidi"><?= $r['belum_tuntas'] ?></span></td>
										<td><span class="badge-custom <?= $badgeClass ?>"><?= $status ?></span></td>
									</tr>
									<?php endforeach; ?>
									<?php if(empty($rekap)): ?>
									<tr>
										<td colspan="10">
											<div class="empty-state">
												<i class="fas fa-chart-pie"></i>
												<p>Belum ada data nilai</p>
											</div>
										</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- Tabel Rekapan Guru & Mapel -->
					<div class="section-card">
						<div class="section-header">
							<h5><i class="fas fa-chalkboard-teacher"></i> Rekapan Guru & Mapel Diampu</h5>
							<span class="badge-count"><?= getTotalGuru($koneksi) ?> Guru</span>
						</div>
						<div class="table-responsive">
							<table id="tabelGuruMapel" class="table-custom display" width="100%">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th width="10%">Kode</th>
										<th width="25%">Nama Guru</th>
										<th width="10%">Gender</th>
										<th width="30%">Mapel Diampu</th>
										<th width="10%">Jumlah</th>
										<th width="10%">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Query guru dan mapel yang diampu
									$queryGuru = "SELECT g.kode_guru, 
									                     CONCAT(g.nama_depan, ' ', g.nama_belakang) as nama_guru,
									                     g.jenis_kelamin,
									                     GROUP_CONCAT(p.pelajaran SEPARATOR ', ') as mapel_diampu,
									                     COUNT(hn.kode_pelajaran) as jumlah_mapel
									              FROM guru g
									              LEFT JOIN header_nilai hn ON g.kode_guru = hn.kode_guru
									              LEFT JOIN pelajaran p ON hn.kode_pelajaran = p.kode_pelajaran
									              GROUP BY g.kode_guru
									              ORDER BY g.nama_depan";
									$resultGuru = mysqli_query($koneksi, $queryGuru);
									$no = 1;
									while($g = mysqli_fetch_assoc($resultGuru)):
									?>
									<tr>
										<td class="text-center"><?= $no++ ?></td>
										<td><strong><?= htmlspecialchars($g['kode_guru']) ?></strong></td>
										<td>
											<span class="avatar-circle <?= $g['jenis_kelamin'] == 'L' ? 'avatar-male' : 'avatar-female' ?>">
												<?= strtoupper(substr($g['nama_guru'], 0, 1)) ?>
											</span>
											<?= htmlspecialchars($g['nama_guru']) ?>
										</td>
										<td>
											<span class="<?= $g['jenis_kelamin'] == 'L' ? 'gender-male' : 'gender-female' ?>">
												<i class="fas <?= $g['jenis_kelamin'] == 'L' ? 'fa-male' : 'fa-female' ?>"></i>
												<?= $g['jenis_kelamin'] == 'L' ? 'L' : 'P' ?>
											</span>
										</td>
										<td>
											<small><?= htmlspecialchars($g['mapel_diampu'] ?: '-') ?></small>
										</td>
										<td class="text-center">
											<span class="badge-custom badge-primary-status"><?= $g['jumlah_mapel'] ?></span>
										</td>
										<td>
											<a href="../data-guru/edit.php?kode_guru=<?= $g['kode_guru'] ?>" 
											   class="btn-action btn-action-edit">
												<i class="fas fa-edit"></i> Edit
											</a>
										</td>
									</tr>
									<?php endwhile; ?>
									<?php if(mysqli_num_rows($resultGuru) == 0): ?>
									<tr>
										<td colspan="7">
											<div class="empty-state">
												<i class="fas fa-chalkboard"></i>
												<p>Belum ada data guru</p>
											</div>
										</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					
				
			</div>
		</div>
	</div>

	<!-- JavaScript -->
	<script src="../bootstrap/js/jquery.min.js"></script>
	<script src="../bootstrap/js/popper.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
	<script src="../style/js/style.js"></script>

	<script>
	$(document).ready(function() {
		$('#tabelSiswa').DataTable({
			"language": { "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json" },
			"pageLength": 10,
			"lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
			"order": [[2, "asc"]],
			"columnDefs": [{ "orderable": false, "targets": [6] }]
		});
		
		$('#tabelRekap').DataTable({
			"language": { "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json" },
			"pageLength": 10,
			"lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
			"order": [[6, "desc"]],
			"columnDefs": [{ "orderable": false, "targets": [9] }]
		});

		$('#tabelGuruMapel').DataTable({
			"language": { "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json" },
			"pageLength": 10,
			"lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
			"order": [[2, "asc"]],
			"columnDefs": [{ "orderable": false, "targets": [6] }]
		});
	});
	</script>

</body>
</html>