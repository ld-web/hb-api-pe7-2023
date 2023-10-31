<?php

// On indique avec cette en-tête le type de contenu que l'on va renvoyer au client
header('Content-Type: application/json; charset=UTF-8');
// Avec le header Access-Control-Allow-Origin,
// j'autorise http://127.0.0.1:5500 à requêter
// header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

require_once 'vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use App\DbConnection;

// Chargement des variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

// Récupération de la connexion à la BDD
try {
    $pdo = DbConnection::getConnection();
    $stmt = $pdo->query("SELECT id, last_name, first_name, email, active, profile_pic FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    // TODO: Que faire en cas d'erreur ?
}
