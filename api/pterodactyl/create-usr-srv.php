<?php
// File: api/pterodactyl/create-usr-srv.php

session_start();
require_once __DIR__ . '../config.php';

function generatePassword($username) {
    return $username . bin2hex(random_bytes(2));
}

function sendEmail($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@zizzshop.com";
    mail($to, $subject, $message, $headers);
}

header('Content-Type: application/json');

// Ambil input dari POST
$username = $_POST['username'] ?? null;
$ram = $_POST['ram'] ?? '0';
$disk = $_POST['disk'] ?? '0';
$cpu = $_POST['cpu'] ?? '0';
$egg = $_POST['egg'] ?? 1;
$nestid = $_POST['nestid'] ?? 1;
$location = $_POST['location'] ?? 1;

$domain = $_POST['domain'] ?? PTERO_URL;
$apikey = $_POST['apikey'] ?? PTERO_API;

if (!$username) {
    echo json_encode(['error' => 'Missing username']);
    exit;
}

$email = $username . '@gmail.com';
$password = generatePassword($username);
$name = ucfirst($username) . " Server";
$desc = date('Y-m-d H:i:s');

// Step 1: Create User
$user_payload = [
    "email" => $email,
    "username" => strtolower($username),
    "first_name" => $name,
    "last_name" => "Server",
    "language" => "en",
    "password" => $password
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $domain . "/api/application/users",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apikey,
        "Content-Type: application/json",
        "Accept: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($user_payload)
]);

$user_response = curl_exec($ch);
$user_data = json_decode($user_response, true);

if (!isset($user_data['attributes']['id'])) {
    echo json_encode(['error' => 'Failed to create user', 'detail' => $user_response]);
    exit;
}

$usr_id = $user_data['attributes']['id'];

// Step 2: Ambil Startup Command
$ch_egg = curl_init();
curl_setopt_array($ch_egg, [
    CURLOPT_URL => $domain . "/api/application/nests/$nestid/eggs/$egg",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apikey,
        "Content-Type: application/json",
        "Accept: application/json"
    ]
]);
$egg_response = curl_exec($ch_egg);
curl_close($ch_egg);
$egg_data = json_decode($egg_response, true);
$startup_cmd = $egg_data['attributes']['startup'] ?? 'npm start';

// Step 3: Create Server
$server_payload = [
    "name" => $name,
    "description" => $desc,
    "user" => $usr_id,
    "egg" => (int)$egg,
    "docker_image" => "ghcr.io/parkervcp/yolks:nodejs_18",
    "startup" => $startup_cmd,
    "environment" => [
        "INST" => "npm",
        "USER_UPLOAD" => "0",
        "AUTO_UPDATE" => "0",
        "CMD_RUN" => "npm start"
    ],
    "limits" => [
        "memory" => (int)$ram,
        "swap" => 0,
        "disk" => (int)$disk,
        "io" => 500,
        "cpu" => (int)$cpu
    ],
    "feature_limits" => [
        "databases" => 5,
        "backups" => 5,
        "allocations" => 5
    ],
    "deploy" => [
        "locations" => [(int)$location],
        "dedicated_ip" => false,
        "port_range" => []
    ]
];

curl_setopt_array($ch, [
    CURLOPT_URL => $domain . "/api/application/servers",
    CURLOPT_POSTFIELDS => json_encode($server_payload)
]);

$server_response = curl_exec($ch);
$server_data = json_decode($server_response, true);
curl_close($ch);

if (!isset($server_data['attributes']['id'])) {
    echo json_encode(['error' => 'Failed to create server', 'detail' => $server_response]);
    exit;
}

$message = "<h3>Akun & Server Anda Berhasil Dibuat</h3>"
         . "<p><b>Email:</b> $email<br>"
         . "<b>Username:</b> $username<br>"
         . "<b>Password:</b> $password<br>"
         . "<b>Nama Server:</b> $name<br>"
         . "<b>Spesifikasi:</b> RAM: $ram MB, Disk: $disk MB, CPU: $cpu%</p>";
sendEmail($email, "[Zizz Shop] Akun & Server Berhasil Dibuat", $message);

echo json_encode([
    'status' => true,
    'user' => $user_data['attributes'],
    'server' => $server_data['attributes'],
    'password' => $password
]);
