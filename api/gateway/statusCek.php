<?php
// File: api/gateway/statusCek.php

require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

$apikey = $_GET['apikey'] ?? '';
$merchantId = $_GET['merchantId'] ?? '';
$apiToken = $_GET['apiToken'] ?? '';

if (!$apikey || !$merchantId || !$apiToken) {
    echo json_encode(['status' => false, 'message' => 'Missing parameters']);
    exit;
}

$url = "https://cloud-rest-api-tau.vercel.app/api/orkut/cekstatus?apikey=$apikey&merchantId=$merchantId&apiToken=$apiToken";
$response = file_get_contents($url);

if (!$response) {
    echo json_encode(['status' => false, 'message' => 'Failed to connect to ORKUT API']);
    exit;
}

echo $response;
