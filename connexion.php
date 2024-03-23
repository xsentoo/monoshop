<?php
try {
    $access = new PDO("mysql:host=localhost;dbname=monoshop;charset=utf8", "root", "");
    $access->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage(); // Afficher le message d'erreur en cas d'échec de la connexion
    die(); // Arrêter l'exécution du script
}
?>
