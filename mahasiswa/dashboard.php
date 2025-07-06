<?php

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once 'templates/header_mahasiswa.php'; 

?>


<div class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white p-8 rounded-xl shadow-lg mb-8">
    <h1 class="text-3xl font-bold">Selamat Datang Kembali, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
    <p class="mt-2 opacity-90">Terus semangat dalam menyelesaikan semua modul praktikummu.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-blue-600">3</div>
        <div class="mt-2 text-lg text-gray-600">Praktikum Diikuti</div>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-green-500">1</div>
        <div class="mt-2 text-lg text-gray-600">Tugas Selesai</div>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-yellow-500">2</div>
        <div class="mt-2 text-lg text-gray-600">Tugas Menunggu</div>
    </div>
    
</div>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Notifikasi Terbaru</h3>
    <ul class="space-y-4">
        
        <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
            <span class="text-xl mr-4">🔔</span>
            <div>
                Nilai untuk <a href="#" class="font-semibold text-blue-600 hover:underline">UAS Pembangan Web Desain telah diberikan.
            </div>
        </li>

        <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
            <span class="text-xl mr-4">⏳</span>
            <div>
                Batas waktu pengumpulan Tugas <a href="#" class="font-semibold text-blue-600 hover:underline">PICOCTF FINDME
            </div>
        </li>

        <li class="flex items-start p-3">
            <span class="text-xl mr-4">✅</span>
            <div>
                Anda berhasil mendaftar pada mata praktikum <a href="#" class="font-semibold text-blue-600 hover:underline">Pengembangan Web Desain</a>.
            </div>
        </li>
        
    </ul>
</div>


<?php
// Panggil Footer
require_once 'templates/footer_mahasiswa.php';
?>