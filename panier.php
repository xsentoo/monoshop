<?php
// Initialiser la session si ce n'est pas déjà fait
session_start();

// Vérifier si le panier existe
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    // Inclure le fichier avec les fonctions ou récupérer les produits de la base de données
    require("commandes.php");

    // Récupérer les informations des produits dans le panier
    $produitsDansPanier = $_SESSION['panier'];

    // Fonction pour afficher les informations des produits dans le panier
    function afficherProduitsDansPanier($produits) {
        foreach ($produits as $key => $produit) {
            echo '<div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 20px;">';
            echo '<img src="' . $produit->image . '" alt="Card image cap" style="max-width: 100%; height: auto; border-radius: 5px;">';
            echo '<p>' . $produit->description . '</p>';
            echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
            echo '<small>' . $produit->prix . '€</small>';
            echo '<form action="" method="post">';
            echo '<button type="submit" name="supprimer" value="' . $key . '">Supprimer</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
    }

    // Supprimer un produit du panier
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["supprimer"])) {
        $indice = $_POST["supprimer"];
        if (isset($produitsDansPanier[$indice])) {
            unset($produitsDansPanier[$indice]);
            // Réindexer le tableau après suppression
            $produitsDansPanier = array_values($produitsDansPanier);
            // Mettre à jour la session avec le nouveau panier
            $_SESSION['panier'] = $produitsDansPanier;
        }
    }
} else {
    // Si le panier est vide, afficher un message
    $produitsDansPanier = [];
}

// Calculer le nombre d'articles dans le panier
$nombreArticlesDansPanier = count($produitsDansPanier);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="Your Name" />
    <title>Panier</title>
    <!-- Ajoutez vos balises meta et link CSS ici -->

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: gray;
           
           
        }

        header {
            background-color: #000; /* Noir */
            color: #fff;
            padding: 10px;
        }

        header a {
            text-decoration: none;
            color: #fff;
            margin: 0 10px;
        }

        header a:hover {
            text-decoration: underline;
        }

        main {
            background-color: #fff; /* Blanc */
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
        }

        main img {
            max-width: 100%;
            border-radius: 5px;
        }

        main button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff; /* Bleu Bootstrap */
            color: #fff;
            margin-right: 5px;
        }

        .notification {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <header>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="#" style="display: flex; align-items: center; text-decoration: none; color: #fff;">
                <img src="images/Shop.jpg" alt="Logo" style="margin-right: 5px; height: 20px; width: 20px;">
                <strong>Ô Petit Marché</strong>
            </a>
            <div>
                <a href="index.php" style="color: #fff;">Accueil</a>
            </div>
        </div>
    </header>

    <main>
        <div class="notification">
            <?php echo 'Votre panier contient ' . $nombreArticlesDansPanier . ' article(s)';
            $totalPanier = calculerTotalPanier($produitsDansPanier);
            echo '<p>Total du panier : ' . number_format($totalPanier, 2) . '€</p>';?>
        </div>

        <h1>Panier</h1>
        <?php
        if (!empty($produitsDansPanier)) {
            afficherProduitsDansPanier($produitsDansPanier);
        } else {
            echo '<p>Votre panier est vide.</p>';
        }
        ?>

        <!-- Formulaire pour valider le panier -->
        <form action="generer_pdf.php" method="post">
            <button type="submit" name="valider_panier">Valider le panier</button>
        </form>
    </main>
</body>
</html>


