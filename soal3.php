
<?php
// --- Konfigurasi Database ---
$dbConfig = [
	'host' => 'localhost',
	'user' => 'root',
	'pass' => '',
	'db'   => 'testdb'
];

$conn = mysqli_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['db']);
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

// Ambil data pencarian
$namaCari   = isset($_GET['nama']) ? trim($_GET['nama']) : '';
$alamatCari = isset($_GET['alamat']) ? trim($_GET['alamat']) : '';

// Query pencarian dinamis
$where = [];
if ($namaCari !== '') $where[] = "person.nama LIKE '%" . mysqli_real_escape_string($conn, $namaCari) . "%'";
if ($alamatCari !== '') $where[] = "person.alamat LIKE '%" . mysqli_real_escape_string($conn, $alamatCari) . "%'";
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT person.nama, person.alamat, hobi.hobi
		FROM person 
		INNER JOIN hobi ON hobi.person_id = person.id
		$whereSql
		ORDER BY person.nama ASC";
$result = mysqli_query($conn, $sql);

// Fungsi render baris tabel
function renderRow($nama, $alamat, $hobi, $bg) {
	$hobiUtama = '';
	if ($hobi) {
		$hobiList = explode(',', $hobi);
		$hobiUtama = trim($hobiList[0]);
	}
	echo '<tr style="background:' . $bg . ';">';
	echo '<td style="padding:10px 8px;border:1px solid #eee;">' . htmlspecialchars($nama) . '</td>';
	echo '<td style="padding:10px 8px;border:1px solid #eee;">' . htmlspecialchars($alamat) . '</td>';
	echo '<td style="padding:10px 8px;border:1px solid #eee;">' . htmlspecialchars($hobiUtama) . '</td>';
	echo '</tr>';
}

// Output Tabel
echo '<div style="max-width:600px;margin:40px auto 0 auto;">';
echo '<h2 style="text-align:center;font-family:Segoe UI,Arial,sans-serif;color:#333;margin-bottom:18px;">Daftar Person dan Hobi</h2>';
echo '<table style="width:100%;border-collapse:collapse;box-shadow:0 2px 8px #ccc;font-size:18px;background:#fff;">';
echo '<tr style="background:#f5f5f5;font-weight:bold;text-align:center;">';
echo '<td style="padding:12px 8px;border:1px solid #bbb;">Nama</td>';
echo '<td style="padding:12px 8px;border:1px solid #bbb;">Alamat</td>';
echo '<td style="padding:12px 8px;border:1px solid #bbb;">Hobi</td>';
echo '</tr>';

$rowNum = 0;
if ($result && mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		if (trim($row['nama']) !== '' && trim($row['alamat']) !== '') {
			$rowNum++;
			$bg = $rowNum % 2 == 0 ? '#f9f9f9' : '#fff';
			renderRow($row['nama'], $row['alamat'], $row['hobi'], $bg);
		}
	}
}
if ($rowNum == 0) {
	echo '<tr><td colspan="3" style="text-align:center;padding:16px;color:#888;">Tidak ada data</td></tr>';
}
echo '</table>';

// Form pencarian
?>
<div style="margin-top:32px;display:flex;justify-content:center;">
	<form method="get" style="border:2px solid #bbb;border-radius:12px;padding:28px 36px;background:#fafbfc;box-shadow:0 2px 8px #eee;display:inline-block;min-width:340px;">
		<div style="margin-bottom:18px;display:flex;align-items:center;">
			<label style="font-weight:600;font-size:20px;font-family:Segoe UI,Arial,sans-serif;width:90px;">Nama :</label>
			<input type="text" name="nama" value="<?= htmlspecialchars($namaCari) ?>" style="font-size:20px;width:180px;font-family:Segoe UI,Arial,sans-serif;border:1.5px solid #bbb;border-radius:6px;padding:6px 10px;margin-left:5px;">
		</div>
		<div style="margin-bottom:18px;display:flex;align-items:center;">
			<label style="font-weight:600;font-size:20px;font-family:Segoe UI,Arial,sans-serif;width:90px;">Alamat :</label>
			<input type="text" name="alamat" value="<?= htmlspecialchars($alamatCari) ?>" style="font-size:20px;width:220px;font-family:Segoe UI,Arial,sans-serif;border:1.5px solid #bbb;border-radius:6px;padding:6px 10px;margin-left:5px;">
		</div>
		<div style="text-align:center;">
			<button type="submit" style="font-size:22px;font-family:Segoe UI,Arial,sans-serif;font-weight:bold;border:2px solid #333;border-radius:8px;padding:6px 36px;background:#fff;cursor:pointer;transition:background 0.2s;">SEARCH</button>
		</div>
	</form>
</div>
<?php
// Tutup koneksi
mysqli_close($conn);
?>
