<?php
date_default_timezone_set("Asia/Makassar"); // set timezone

// (Opsional) cek secret biar nda sembarang orang bisa trigger
$secret = "jalanrahasia";
$incoming = $_GET['secret'] ?? '';
if ($incoming !== $secret) {
    http_response_code(403);
    exit("Forbidden");
}

// Check if shell_exec is enabled
if (!function_exists('shell_exec') || in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))))) {
    $output = "ERROR: shell_exec() is disabled on this server.\n";
    file_put_contents("deploy.text", date('Y-m-d H:i:s') . " - " . $output . "\n", FILE_APPEND);
    $message = "❌ Deploy gagal!\nFungsi shell_exec() dinonaktifkan di server ini.";
    // Send Telegram message about disabled function
    // (Telegram config will be below)
} else {
    // Test a simple command
    $test_output = shell_exec('echo "Shell exec is enabled!"');
    file_put_contents("deploy.text", date('Y-m-d H:i:s') . " - Test: " . $test_output . "\n", FILE_APPEND);

    // Jalankan perintah git pull menggunakan SSH
    // Anda perlu menyiapkan SSH Key di server hosting dan menambahkannya sebagai Deploy Key di GitHub.
    // Ganti <YOUR_SSH_REPO_URL> dengan URL SSH repositori Anda (misal: git@github.com:mieburungdara/all_apps.git)
    $ssh_repo_url = "git@github.com:mieburungdara/all_apps.git"; // Contoh URL SSH
    $output = shell_exec('cd /home/u1574101/public_html/mymy.my.id && git pull ' . $ssh_repo_url . ' 2>&1');    // Simpan log ke file
    file_put_contents("deploy.text", date('Y-m-d H:i:s') . " - " . $output . "\n", FILE_APPEND);

    $message = "✅ Deploy sukses!\nRepo: *$repoName*\nWaktu: $time\n\nHasil:\n`$output`";
}

// Konfigurasi Telegram
$botToken = "7715036030:AAHlRifKGc-a1Y0b3yPdfhdnNix91C7rtvI"; // ganti dengan token bot
$chatId   = "7602143247"; // ganti dengan chat ID/grup

$repoName = "mymy.my.id";
$time     = date('Y-m-d H:i:s');

// Kirim request dengan cURL
$ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ]
]);
$response = curl_exec($ch);
curl_close($ch);



