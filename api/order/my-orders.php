<?php
// API de récupération des commandes de l'utilisateur connecté.

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

if (!isset($_SESSION['user'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Vous devez être connecté.'
    ]);

    exit;
}

$userId = (int) $_SESSION['user']['id'];

$statement = $pdo->prepare(
    'SELECT id, total, status, created_at
     FROM orders
     WHERE user_id = :user_id
     ORDER BY id DESC'
);

$statement->execute([
    'user_id' => $userId
]);

$orders = $statement->fetchAll();

echo json_encode([
    'success' => true,
    'orders' => $orders
]);