<?php
require("commandes.php");

// Initialiser la session si ce n'est pas déjà fait
session_start();

// Traiter l'ajout au panier s'il y a une action "acheter" et un produit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acheter"]) && isset($_POST["produit"])) {
    $produit = json_decode(htmlspecialchars_decode($_POST["produit"]));
    ajouterAuPanier($produit);
}

// Gérer la recherche si le formulaire de recherche est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $searchTerm = $_POST["search"];
    $mesProduits = rechercherProduits($searchTerm);
} else {
    // Sinon, afficher tous les produits
    $mesProduits = afficher();
}

function ajouterAuPanier($produit) {
    // Initialiser le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Ajouter le produit au panier
    $_SESSION['panier'][] = $produit;
}

function rechercherProduits($searchTerm) {
    if (require("connexion.php")) {
        if ($access !== null) {
            $searchTerm = "%$searchTerm%"; // Ajoutez des jokers % pour rechercher des correspondances partielles
            $req = $access->prepare("SELECT * FROM produits WHERE nom LIKE ? ORDER BY id DESC");
            $req->execute(array($searchTerm));
            $data = $req->fetchAll(PDO::FETCH_OBJ);
            $req->closeCursor();
            return $data;
        } else {
            // Gérer l'erreur de connexion
            echo "Erreur de connexion à la base de données.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="Your Name" />
    <title>Monoshop</title>
    <style>
        body {
            
            margin: 0;
            padding: 0;
            background-color: gray;
           
        }

        header {
            background-color: #000;
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

        section {
            text-align: center;
            padding: 20px;
        }

        section a {
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            margin: 0 10px;
        }

        main {
            background-color: #fff;
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
            background-color: #007bff;
            color: #fff;
            margin-right: 5px;
        }

        main form {
            margin-bottom: 20px;
            text-align: center;
        }

        main input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        main input[type="submit"] {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
                <a href="About.php" style="color: #fff;">About</a>
               
                <a href="panier.php" style="color: #fff;">Panier</a> <!-- Ajout du bouton Panier -->
                
            </div>
        </div>
    </header>

    <main>
        <section>
            <form action="" method="post">
                <input type="text" name="search" placeholder="Rechercher...">
                <input type="submit" value="Rechercher">
            </form>
            <p>
                <a href="#" class="btn btn-primary my-2">Main call to action</a>
                <a href="#" class="btn btn-secondary my-2">Secondary action</a>
            </p>
        </section>

        <div>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php foreach ($mesProduits as $produit) : ?>
                    <div style="width: calc(33.33% - 20px); box-sizing: border-box;">
                        <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                            <img src="<?= $produit->image ?>" alt="Card image cap">
                            <p><?= $produit->description ?></p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <form action="" method="post">
                                    <button type="submit" name="acheter">Acheter</button>
                                    <input type="hidden" name="produit" value="<?= htmlspecialchars(json_encode($produit)) ?>">
                                </form>
                                <small><?= $produit->prix ?>€</small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>
