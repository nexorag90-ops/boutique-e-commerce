<?php
// API de récupération d'un produit.

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$id = $_GET['id'] ?? '';

if (!ctype_digit($id) || (int) $id <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Identifiant du produit invalide.'
    ]);

    exit;
}

$statement = $pdo->prepare('
    SELECT
        products.id,
        products.name,
        products.description,
        products.price,
        products.image,
        categories.id AS category_id,
        categories.name AS category
    FROM products
    INNER JOIN categories
        ON categories.id = products.category_id
    WHERE products.id = :id
');

$statement->execute([
    'id' => (int) $id
]);

$product = $statement->fetch();

if (!$product) {
    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' => 'Produit introuvable.'
    ]);

    exit;
}

echo json_encode([
    'success' => true,
    'product' => $product
]);