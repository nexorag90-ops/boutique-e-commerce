<?php
// API de création d'une commande.

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
        'message' => 'Vous devez être connecté pour passer une commande.'
    ]);

    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$items = $data['items'] ?? [];

if (!is_array($items) || count($items) === 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Le panier est vide.'
    ]);

    exit;
}

try {
    $pdo->beginTransaction();

    $total = 0;
    $validatedItems = [];

    foreach ($items as $item) {
        $productId = (int) ($item['product_id'] ?? 0);
        $quantity = (int) ($item['quantity'] ?? 0);

        if ($productId <= 0 || $quantity <= 0) {
            throw new Exception('Produit ou quantité invalide.');
        }

        $statement = $pdo->prepare(
            'SELECT id, name, price
             FROM products
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $productId
        ]);

        $product = $statement->fetch();

        if (!$product) {
            throw new Exception('Un produit demandé est introuvable.');
        }

        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;

        $validatedItems[] = [
            'product_id' => $product['id'],
            'quantity' => $quantity,
            'price' => $product['price']
        ];
    }

    $statement = $pdo->prepare(
        'INSERT INTO orders (user_id, total, status)
         VALUES (:user_id, :total, :status)'
    );

    $statement->execute([
        'user_id' => $_SESSION['user']['id'],
        'total' => $total,
        'status' => 'pending'
    ]);

    $orderId = $pdo->lastInsertId();

    $itemStatement = $pdo->prepare(
        'INSERT INTO order_items (
            order_id,
            product_id,
            quantity,
            price
        ) VALUES (
            :order_id,
            :product_id,
            :quantity,
            :price
        )'
    );

    foreach ($validatedItems as $item) {
        $itemStatement->execute([
            'order_id' => $orderId,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }

    $pdo->commit();

    http_response_code(201);

    echo json_encode([
        'success' => true,
        'message' => 'Commande créée avec succès.',
        'order_id' => (int) $orderId,
        'total' => $total
    ]);
} catch (Exception $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage()
    ]);
}