<?php
// File: api/gateway/createQris.php

require_once __DIR__ . '../config.php';

header('Content-Type: application/json');

$apikey = $_GET['apikey'] ?? '';
$amount = $_GET['amount'] ?? '';
$codeqr = $_GET['codeqr'] ?? '';

if (!$apikey || !$amount || !$codeqr) {
    echo json_encode(['status' => false, 'message' => 'Missing parameters']);
    exit;
}

$url = "https://cloud-rest-api-tau.vercel.app/api/orkut/createpayment?apikey=$apikey&amount=$amount&codeqr=$codeqr";
$response = file_get_contents($url);

if (!$response) {
    echo json_encode(['status' => false, 'message' => 'Failed to connect to ORKUT API']);
    exit;
}

echo $response;
