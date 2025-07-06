<?php
$pageTitle = ' Modul Praktikum';
$activePage = 'modul';
require_once 'templates/header.php';
require_once '../config.php';

// Variabel untuk menyimpan pesan status/error
$upload_message = '';
$upload_message_type = '';

// --- LOGIKA UNTUK MENAMBAHKAN MODUL BARU ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_modul'])) {
    $id_praktikum = $_POST['id_praktikum'];
    $nama_modul = $_POST['nama_modul'];
    $deskripsi = $_POST['deskripsi'];
    $file_materi_path = '';
    
    // --- PENGECEKAN DEBUGGING DIMULAI DI SINI ---
    $target_dir = "../uploads/materi/";

    // 1. Cek apakah direktori uploads/materi ada
    if (!file_exists($target_dir)) {
        $upload_message = "<strong>Error Kritis:</strong> Folder <code>uploads/materi/</code> tidak ditemukan! Harap buat folder tersebut.";
        $upload_message_type = 'error';
    } 
    // 2. Cek apakah direktori bisa ditulis (writable) oleh server
    elseif (!is_writable($target_dir)) {
        $upload_message = "<strong>Error Izin (Permission):</strong> Server tidak diizinkan untuk menulis file ke dalam folder <code>uploads/materi/</code>. Harap ubah izin folder (misalnya: chmod 775).";
        $upload_message_type = 'error';
    } 
    // 3. Jika direktori aman, lanjutkan proses upload
    else {
        if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
            $file_materi_path = time() . '_' . basename($_FILES["file_materi"]["name"]);
            
            // Coba pindahkan file
            if (move_uploaded_file($_FILES["file_materi"]["tmp_name"], $target_dir . $file_materi_path)) {
                // Jika berhasil, simpan ke DB
                $stmt = $conn->prepare("INSERT INTO modul (id_praktikum, nama_modul, deskripsi, file_materi) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $id_praktikum, $nama_modul, $deskripsi, $file_materi_path);
                if ($stmt->execute()) {
                    $upload_message = "<strong>Berhasil!</strong> Modul baru telah berhasil ditambahkan.";
                    $upload_message_type = 'sukses';
                }
                $stmt->close();
            } else {
                // Jika gagal memindahkan file
                $upload_message = "<strong>Error Upload:</strong> Terjadi kesalahan saat memindahkan file. Silakan cek konfigurasi server.";
                $upload_message_type = 'error';
            }
        } else {
            // Jika tidak ada file yang di-upload, tetap simpan datanya
            $stmt = $conn->prepare("INSERT INTO modul (id_praktikum, nama_modul, deskripsi) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $id_praktikum, $nama_modul, $deskripsi);
            if ($stmt->execute()) {
                $upload_message = "<strong>Berhasil!</strong> Modul baru (tanpa file) telah berhasil ditambahkan.";
                $upload_message_type = 'sukses';
            }
            $stmt->close();
        }
    }
}
// Logika Hapus (tidak berubah)
// ... (Kode hapus Anda bisa diletakkan di sini) ...
?>

<div class="bg-white p-8 rounded-2xl shadow-lg mb-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Modul Baru</h2>
    
    <?php if (!empty($upload_message)): ?>
        <div class="p-4 mb-6 rounded-lg <?php echo ($upload_message_type == 'sukses') ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700'; ?>" role="alert">
            <?php echo $upload_message; ?>
        </div>
    <?php endif; ?>

    <form action="modul.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="id_praktikum" class="block font-semibold text-gray-700 mb-1">Pilih Mata Praktikum</label>
            <select name="id_praktikum" id="id_praktikum" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#8E44AD]" required>
                <option value="">-- Pilih Praktikum --</option>
                <?php
                $praktikum_list = $conn->query("SELECT id, nama_praktikum FROM mata_praktikum ORDER BY nama_praktikum");
                while($p = $praktikum_list->fetch_assoc()) {
                    echo "<option value='{$p['id']}'>".htmlspecialchars($p['nama_praktikum'])."</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="nama_modul" class="block font-semibold text-gray-700 mb-1">Nama Modul</label>
            <input type="text" name="nama_modul" id="nama_modul" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8E44AD]" required>
        </div>
        <div>
            <label for="file_materi" class="block font-semibold text-gray-700 mb-1">File Materi</label>
            <input type="file" name="file_materi" id="file_materi" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
        </div>
        <div>
            <label for="deskripsi" class="block font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8E44AD]"></textarea>
        </div>
        <button type="submit" name="submit_modul" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                Simpan
        </button>
    </form>
</div>