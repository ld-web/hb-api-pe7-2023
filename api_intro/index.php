<?php

// On indique avec cette en-tête le type de contenu que l'on va renvoyer au client
header('Content-Type: application/json; charset=UTF-8');
// Avec le header Access-Control-Allow-Origin,
// j'autorise http://127.0.0.1:5500 à requêter
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

require_once 'data/users.php';

// On a indiqué en ligne 4 qu'on renvoyait du JSON (avec le type MIME "application/json"),
// donc on encode (on transforme) notre tableau d'utilisateurs en une chaîne de
// caractères JSON
echo json_encode($users);
