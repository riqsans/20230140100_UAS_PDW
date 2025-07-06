<?php
$pageTitle = 'Beri Nilai Laporan';
$activePage = 'nilai';
require_once 'templates/header.php';
require_once '../config.php';

// ... (Logika PHP tidak berubah, hanya tampilannya saja) ...
$id_laporan = $_GET['id'] ?? 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_nilai'])) {
    $nilai = $_POST['nilai'];
    $feedback = $_POST['feedback'];
    $id = $_POST['id_laporan'];
    $stmt = $conn->prepare("UPDATE laporan SET nilai = ?, feedback = ? WHERE id = ?");
    $stmt->bind_param("isi", $nilai, $feedback, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: laporan_masuk.php?status=sukses");
    exit();
}
$sql = "SELECT l.*, u.nama as nama_mahasiswa, m.nama_modul
        FROM laporan l
        JOIN users u ON l.id_mahasiswa = u.id
        JOIN modul m ON l.id_modul = m.id
        WHERE l.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_laporan);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "Laporan tidak ditemukan."; exit;
}
$laporan = $result->fetch_assoc();
?>

<div class="bg-white p-8 rounded-2xl shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-2 text-gray-800">Detail Laporan & Penilaian</h2>
    <div class="mb-6 border-b border-gray-200 pb-4">
        <p><strong>Mahasiswa:</strong> <?php echo htmlspecialchars($laporan['nama_mahasiswa']); ?></p>
        <p><strong>Modul:</strong> <?php echo htmlspecialchars($laporan['nama_modul']); ?></p>
        <p><strong>File:</strong> <a href="../uploads/laporan/<?php echo $laporan['file_laporan']; ?>" target="_blank" class="text-purple-600 hover:underline font-semibold">Unduh Laporan Mahasiswa</a></p>
    </div>

    <form action="proses_nilai.php" method="POST" class="space-y-4">
        <input type="hidden" name="id_laporan" value="<?php echo $laporan['id']; ?>">
        <div>
            <label for="nilai" class="block font-semibold text-gray-700 mb-1">Nilai (0-100)</label>
            <input type="number" name="nilai" id="nilai" min="0" max="100" value="<?php echo $laporan['nilai']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8E44AD]" required>
        </div>
        <div>
            <label for="feedback" class="block font-semibold text-gray-700 mb-1">Feedback (Opsional)</label>
            <textarea name="feedback" id="feedback" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8E44AD]"><?php echo htmlspecialchars($laporan['feedback']); ?></textarea>
        </div>
        <button type="submit" name="submit_nilai" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
            Simpan Nilai
        </button>
    </form>
</div>

<?php
$stmt->close();
$conn->close();
require_once 'templates/footer.php';
?>