<?php

function ajouter($image, $nom, $prix, $desc) {
    require("connexion.php");
    global $access;

    // Formater le prix avec deux chiffres après la virgule
    $prix = number_format($prix, 2, '.', '');

    $req = $access->prepare("INSERT INTO produits (image, nom, prix, description) VALUES (?, ?, ?, ?)");
    $req->execute(array($image, $nom, $prix, $desc));
    $req->closeCursor();
}


function afficher() {
    if(require("connexion.php")) {
        if ($access !== null) {
            $req = $access->prepare("SELECT * FROM produits ORDER BY id DESC");
            $req->execute();
            $data = $req->fetchAll(PDO::FETCH_OBJ);
            $req->closeCursor();
            return $data;
        } else {
            // Gérer l'erreur de connexion
            echo "Erreur de connexion à la base de données.";
        }
    }
}


function supprimer($id) {
    require("connexion.php");
    global $access; // Ajout de la variable globale pour utiliser $access dans la fonction

    $req = $access->prepare("DELETE FROM produits WHERE id=?");
    $req->execute(array($id));
}
// Fonction pour calculer le total du panier
function calculerTotalPanier($produits) {
    $total = 0;
    foreach ($produits as $produit) {
        $total += $produit->prix;
    }
    return $total;
}

?>
