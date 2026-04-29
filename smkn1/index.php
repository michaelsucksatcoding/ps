<?php
session_start();
require_once __DIR__ . '/action/config.php';

if (!($connect instanceof PDO)) {
	die('Koneksi database tidak tersedia.');
}

if (isset($_SESSION['admin_id'])) {
	header('Location: dashboard/');
	exit;
}

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$userid = trim($_POST['userid'] ?? '');
	$passwordInput = $_POST['password'] ?? '';

	if ($userid === '' || $passwordInput === '') {
		$errorMessage = 'User ID dan Password wajib diisi.';
	} else {
		try {
			$stmt = $connect->prepare('SELECT id_admin, password, nama_depan, nama_belakang, level_admin FROM admin WHERE id_admin = :id_admin LIMIT 1');
			$stmt->execute([':id_admin' => $userid]);
			$adminData = $stmt->fetch(PDO::FETCH_ASSOC);

			$isValid = false;
			if ($adminData) {
				// Support both hashed password and legacy plain-text password.
				$isValid = password_verify($passwordInput, $adminData['password']) || hash_equals((string) $adminData['password'], $passwordInput);
			}

			if ($isValid) {
				session_regenerate_id(true);
				$_SESSION['admin_id'] = $adminData['id_admin'];
				$_SESSION['admin_name'] = trim(($adminData['nama_depan'] ?? '') . ' ' . ($adminData['nama_belakang'] ?? ''));
				$_SESSION['admin_level'] = $adminData['level_admin'] ?? null;
				header('Location: dashboard/');
				exit;
			}

			$errorMessage = 'User ID atau Password salah.';
		} catch (PDOException $e) {
			$errorMessage = 'Terjadi kesalahan saat login. Silakan coba lagi.';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Selamat Datang | Login Admin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- link ke bootstrap css -->
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/custom.css">
	
	
	<!-- style -->
	<style>

		body {
			font-family: "Monaco", Monospace;
		}

	</style>

</head>
<body>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-4 pt-5">
				
				<div class="card mt-5">
					
					<div class="card-header bg-primary">
						
						<div class="card-title font-weight-bold text-white text-center">

							<h1><b>LOGIN</b></h1>

						</div>

					</div>

					<div class="card-body">
						
						<?php if ($errorMessage !== ''): ?>
							<div class="alert alert-danger" role="alert">
								<?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
							</div>
						<?php endif; ?>
						<form class="form" method="post" action="">
							
							<div class="form-group">
								
								<label for="userid">User ID :</label>
								<input class="form-control" type="text" name="userid" id="userid" required>

							</div>

							<div class="form-group">
								
								<label for="password">Password :</label>
								<input class="form-control" type="password" name="password" id="password" required>

							</div>

							<p><a href="">Lupa Password ?</a></p>

                            <p><button class="btn btn-primary" type="submit">Login</button></p>

						</form>

					</div>

				</div>

			</div>
		</div>
	</div>

	<!-- memanggil jquery, popper dan bootstrap js -->
	<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/popper.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>