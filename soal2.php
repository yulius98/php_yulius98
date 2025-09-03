
<?php
session_start();

// Reset session jika tombol reset ditekan
if (isset($_POST['reset'])) {
	session_destroy();
	header('Location: ' . $_SERVER['PHP_SELF']);
	exit;
}

// Proses input dinamis
$steps = [
	[
		'key' => 'nama',
		'label' => 'Nama Anda',
		'type' => 'text',
	],
	[
		'key' => 'umur',
		'label' => 'Umur Anda',
		'type' => 'number',
	],
	[
		'key' => 'hobi',
		'label' => 'Hobi Anda',
		'type' => 'text',
	],
];

// Simpan data yang diinput
foreach ($steps as $step) {
	if (isset($_POST[$step['key']]) && !empty($_POST[$step['key']])) {
		$_SESSION[$step['key']] = $_POST[$step['key']];
		break;
	}
}

// Fungsi form dinamis
function form_input($label, $name, $type) {
	echo '<form class="form-box" method="post">';
	echo '<label class="form-label">' . htmlspecialchars($label) . ' :</label>';
	echo '<input type="' . $type . '" name="' . $name . '" class="form-input">';
	echo '<br><br>';
	echo '<button type="submit" class="form-submit">SUBMIT</button>';
	echo '</form>';
}

// Tentukan step aktif
$currentStep = 0;
foreach ($steps as $i => $step) {
	if (!isset($_SESSION[$step['key']])) {
		$currentStep = $i;
		break;
	} else {
		$currentStep = $i + 1;
	}
}

// Judul dinamis
$titles = ['Form Nama', 'Form Umur', 'Form Hobi', 'Data Lengkap'];
$pageTitle = $titles[$currentStep] ?? 'Form Data';
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title><?= htmlspecialchars($pageTitle) ?></title>
	<style>
		.form-box {
			border: 2px solid #000;
			width: 500px;
			height: 200px;
			padding: 20px;
			box-sizing: border-box;
			position: relative;
		}
		.form-label {
			font-size: 2em;
			font-family: Arial, sans-serif;
			font-weight: bold;
		}
		.form-input {
			width: 150px;
			height: 30px;
			font-size: 1.2em;
			margin-left: 20px;
		}
		.form-submit {
			margin-top: 40px;
			margin-left: 60px;
			width: 170px;
			height: 60px;
			font-size: 2em;
			font-family: Times New Roman, serif;
			border: 2px solid #000;
			background: #fff;
			cursor: pointer;
		}
	</style>
</head>
<body>
	<?php
	if ($currentStep < count($steps)) {
		$step = $steps[$currentStep];
		form_input($step['label'], $step['key'], $step['type']);
	} else {
		echo '<div class="form-box">';
		echo '<p style="font-size:1.5em;">';
		echo 'Nama : <b>' . htmlspecialchars($_SESSION['nama']) . '</b><br>';
		echo 'Umur : <b>' . htmlspecialchars($_SESSION['umur']) . '</b><br>';
		echo 'Hobi : <b>' . htmlspecialchars($_SESSION['hobi']) . '</b>';
		echo '</p>';
		echo '</div>';
		echo '<form method="post"><button name="reset" class="form-submit" style="margin-top:10px;">RESET</button></form>';
	}
	?>
</body>
</html>
