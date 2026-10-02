<?php
// API de récupération de l'utilisateur connecté.

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Utilisateur non connecté.'
    ]);

    exit;
}

echo json_encode([
    'success' => true,
    'user' => $_SESSION['user']
]);