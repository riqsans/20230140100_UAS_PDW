<?php
$pageTitle = 'Laporan';
$activePage = 'laporan';
require_once 'templates/header.php';
require_once '../config.php';

// ... (Logika PHP tidak berubah, hanya tampilannya saja) ...
$whereClauses = [];
$filter_modul = $_GET['filter_modul'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
if (!empty($filter_modul)) {
    $whereClauses[] = "m.id = " . intval($filter_modul);
}
if ($filter_status === 'dinilai') {
    $whereClauses[] = "l.nilai IS NOT NULL";
} elseif ($filter_status === 'belum_dinilai') {
    $whereClauses[] = "l.nilai IS NULL";
}
$sql = "SELECT l.id, l.tanggal_kumpul, l.nilai, u.nama as nama_mahasiswa, m.nama_modul, mp.nama_praktikum, l.file_laporan
        FROM laporan l
        JOIN users u ON l.id_mahasiswa = u.id
        JOIN modul m ON l.id_modul = m.id
        JOIN mata_praktikum mp ON m.id_praktikum = mp.id";
if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}
$sql .= " ORDER BY l.tanggal_kumpul DESC";
$result = $conn->query($sql);
?>
<div class="bg-white p-8 rounded-2xl shadow-lg">
    <div class="flex flex-wrap gap-4 justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Filter Laporan</h2>
        <form action="laporan.php" method="GET" class="flex flex-wrap gap-4">
            <div>
                <select name="filter_modul" id="filter_modul" class="px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#8E44AD]">
                    <option value="">Semua Modul</option>
                    <?php
                    $modul_list = $conn->query("SELECT id, nama_modul FROM modul ORDER BY nama_modul");
                    while ($modul = $modul_list->fetch_assoc()) {
                        $selected = ($filter_modul == $modul['id']) ? 'selected' : '';
                        echo "<option value='{$modul['id']}' {$selected}>" . htmlspecialchars($modul['nama_modul']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <select name="filter_status" id="filter_status" class="px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#8E44AD]">
                    <option value="">Semua Status</option>
                    <option value="dinilai" <?php echo ($filter_status == 'dinilai') ? 'selected' : ''; ?>>Sudah Dinilai</option>
                    <option value="belum_dinilai" <?php echo ($filter_status == 'belum_dinilai') ? 'selected' : ''; ?>>Belum Dinilai</option>
                </select>
            </div>
            <button type="submit" class="btn-primary text-white font-semibold py-2 px-6 rounded-lg hover:opacity-90">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
             <thead class="border-b-2 border-gray-200">
                <tr>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Mahasiswa</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Modul</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Tanggal Kumpul</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Status</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php if ($result && $result->num_rows > 0):
                    while($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-100 hover:bg-purple-50">
                        <td class="py-4 px-4 font-semibold"><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                        <td class="py-4 px-4">
                            <p class="font-semibold"><?php echo htmlspecialchars($row['nama_modul']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($row['nama_praktikum']); ?></p>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-500"><?php echo date('d M Y, H:i', strtotime($row['tanggal_kumpul'])); ?></td>
                        <td class="py-4 px-4">
                            <?php if (is_null($row['nilai'])): ?>
                                <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold">Belum Dinilai</span>
                            <?php else: ?>
                                <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Dinilai (<?php echo $row['nilai']; ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4 flex items-center space-x-2">
                             <a href="proses_nilai.php?id=<?php echo $row['id']; ?>" class="bg-purple-500 text-white py-1 px-3 rounded-md text-xs font-semibold hover:bg-purple-600">
                                <?php echo is_null($row['nilai']) ? 'Nilai' : 'Edit'; ?>
                            </a>
                            <a href="../uploads/laporan/<?php echo $row['file_laporan']; ?>" target="_blank" class="bg-gray-200 text-gray-700 py-1 px-3 rounded-md text-xs font-semibold hover:bg-gray-300">Unduh</a>
                        </td>
                    </tr>
                    <?php endwhile;
                else: ?>
                    <tr><td colspan="5" class="text-center py-6 text-gray-500">Tidak ada laporan yang cocok dengan filter.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$conn->close();
require_once 'templates/footer.php';
?>