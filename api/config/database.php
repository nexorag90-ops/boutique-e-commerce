<?php
// Connexion à la base de données SQLite avec PDO.

$databasePath = __DIR__ . '/../database.sqlite';

try {
    $pdo = new PDO('sqlite:' . $databasePath);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    http_response_code(500);

    die('Erreur de connexion à la base de données.');
}