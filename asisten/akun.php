<?php
$pageTitle = 'Kelola Akun Pengguna';
$activePage = 'akun';
require_once 'templates/header.php';
require_once '../config.php';

// ... (Logika PHP tidak berubah, hanya tampilannya saja) ...
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if ($id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: kelola_akun.php");
    exit();
}
$result = $conn->query("SELECT id, nama, email, role, created_at FROM users ORDER BY role, created_at DESC");
?>

<div class="bg-white p-8 rounded-2xl shadow-lg">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Akun Pengguna</h2>
        <a href="../register.php" target="_blank" class="btn-primary text-white font-semibold py-2 px-6 rounded-lg hover:opacity-90">
            Tambah Akun Baru
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="border-b-2 border-gray-200">
                <tr>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Nama Pengguna</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Email</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Peran</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-100 hover:bg-purple-50">
                        <td class="py-4 px-4 font-semibold"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="py-4 px-4 text-gray-600"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="py-4 px-4">
                            <?php if($row['role'] == 'asisten'): ?>
                                <span class="bg-purple-200 text-purple-800 py-1 px-3 rounded-full text-xs font-semibold">Asisten</span>
                            <?php else: ?>
                                <span class="bg-pink-200 text-pink-800 py-1 px-3 rounded-full text-xs font-semibold">Mahasiswa</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                <a href="kelola_akun.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus akun ini?')" class="bg-red-500 text-white py-1 px-3 rounded-md text-xs font-semibold hover:bg-red-600">Hapus</a>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs italic">Ini Akun Anda</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile;
                else: ?>
                    <tr><td colspan="4" class="text-center py-6 text-gray-500">Belum ada akun terdaftar.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>