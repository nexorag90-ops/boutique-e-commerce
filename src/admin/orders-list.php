<?php

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

try {
    $statement = $pdo->query(
        'SELECT
            orders.id,
            orders.user_id,
            orders.total,
            orders.status,
            orders.created_at,
            users.name AS customer_name,
            users.email AS customer_email
        FROM orders
        INNER JOIN users
            ON users.id = orders.user_id
        ORDER BY orders.id DESC'
    );

    $orders = $statement->fetchAll();

    echo json_encode([
        'success' => true,
        'orders' => $orders
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de récupérer les commandes.'
    ]);
}