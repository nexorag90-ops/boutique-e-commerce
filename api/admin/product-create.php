<?php

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = (int) ($_POST['price'] ?? 0);
$categoryId = (int) ($_POST['category_id'] ?? 0);

if ($name === '' || $price <= 0 || $categoryId <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Nom, prix et catégorie sont obligatoires.'
    ]);

    exit;
}

try {
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

    $imagePath = null;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Impossible de télécharger l’image.'
            ]);

            exit;
        }

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        $fileType = mime_content_type(
            $_FILES['image']['tmp_name']
        );

        if (!in_array($fileType, $allowedTypes, true)) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Format d’image non autorisé. Utilisez JPG, PNG ou WEBP.'
            ]);

            exit;
        }

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'L’image ne doit pas dépasser 5 Mo.'
            ]);

            exit;
        }

        $extension = match ($fileType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => null
        };

        $fileName = uniqid('product_', true) . '.' . $extension;

        $uploadDirectory = __DIR__ . '/../uploads/products/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0775, true);
        }

        $destination = $uploadDirectory . $fileName;

        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $destination
        )) {
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Impossible d’enregistrer l’image.'
            ]);

            exit;
        }

        $imagePath = '/uploads/products/' . $fileName;
    }

    $statement = $pdo->prepare(
        'INSERT INTO products (
            name,
            description,
            price,
            image,
            category_id
        ) VALUES (
            :name,
            :description,
            :price,
            :image,
            :category_id
        )'
    );

    $statement->execute([
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'image' => $imagePath,
        'category_id' => $categoryId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Produit ajouté avec succès.',
        'product_id' => (int) $pdo->lastInsertId()
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible d’ajouter le produit.'
    ]);
}