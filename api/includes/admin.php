<?php

require_once __DIR__ . '/session.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Vous devez être connecté.'
    ]);

    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);

    echo json_encode([
        'success' => false,
        'message' => 'Accès réservé aux administrateurs.'
    ]);

    exit;
}