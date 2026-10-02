```php
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

$id = (int) ($data['id'] ?? 0);
$name = trim($data['name'] ?? '');
$description = trim($data['description'] ?? '');
$price = (int) ($data['price'] ?? 0);
$categoryId = (int) ($data['category_id'] ?? 0);

if ($id <= 0 || $name === '' || $price <= 0 || $categoryId <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Les informations du produit sont invalides.'
    ]);

    exit;
}

try {
    $productStatement = $pdo->prepare(
        'SELECT id, image FROM products WHERE id = :id'
    );

    $productStatement->execute([
        'id' => $id
    ]);

    $product = $productStatement->fetch();

    if (!$product) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Produit introuvable.'
        ]);

        exit;
    }

    $categoryStatement = $pdo->prepare(
        'SELECT id FROM categories WHERE id = :id'
    );

    $categoryStatement->execute([
        'id' => $categoryId
    ]);

    if (!$categoryStatement->fetch()) {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'La catégorie sélectionnée est introuvable.'
        ]);

        exit;
    }

    $statement = $pdo->prepare(
        'UPDATE products
         SET name = :name,
             description = :description,
             price = :price,
             category_id = :category_id
         WHERE id = :id'
    );

    $statement->execute([
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'category_id' => $categoryId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Produit modifié avec succès.',
        'image' => $product['image']
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de modifier le produit.'
    ]);
}