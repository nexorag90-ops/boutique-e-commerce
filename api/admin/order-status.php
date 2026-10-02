<?php

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$orderId = (int) ($data['order_id'] ?? 0);
$status = trim($data['status'] ?? '');

$allowedStatuses = [
    'pending',
    'confirmed',
    'completed',
    'cancelled'
];

if ($orderId <= 0 || !in_array($status, $allowedStatuses, true)) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Commande ou statut invalide.'
    ]);

    exit;
}

try {
    $orderStatement = $pdo->prepare(
        'SELECT id FROM orders WHERE id = :id'
    );

    $orderStatement->execute([
        'id' => $orderId
    ]);

    if (!$orderStatement->fetch()) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Commande introuvable.'
        ]);

        exit;
    }

    $statement = $pdo->prepare(
        'UPDATE orders
         SET status = :status
         WHERE id = :id'
    );

    $statement->execute([
        'status' => $status,
        'id' => $orderId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Statut de la commande mis à jour.'
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de modifier le statut de la commande.'
    ]);
}