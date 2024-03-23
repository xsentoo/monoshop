<?php
require_once('tcpdf/tcpdf.php');

function afficherProduitsDansPDF($pdf, $produits) {
    $total = 0; // Initialiser le total à zéro
    foreach ($produits as $produit) {
        $html = '<p><strong>Nom du produit:</strong> ' . $produit->nom . '</p>';
        $html .= '<p><strong>Prix:</strong> ' . $produit->prix . '€</p>';
        $html .= '<hr>';
        $pdf->writeHTML($html);
        $total += $produit->prix; // Ajouter le prix du produit au total
    }
    // Afficher le total à la fin de la liste de produits
    $pdf->writeHTML('<p><strong>Total:</strong> ' . $total . '€</p>');
}

// Vérifier si le panier existe dans la session
session_start();
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    // Inclure le fichier avec les fonctions pour récupérer les produits
    require("commandes.php");

    // Récupérer les informations des produits dans le panier
    $produitsDansPanier = $_SESSION['panier'];

    // Créer une instance de la classe TCPDF
    $pdf = new TCPDF();

    // Ajouter une page au PDF
    $pdf->AddPage();

    // Ajouter le titre de la liste de courses
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Liste de courses', 0, 1, 'C');

    // Afficher les produits dans le PDF
    afficherProduitsDansPDF($pdf, $produitsDansPanier);

    // Définir le nom du fichier de sortie
    $filename = 'LISTE_COURSES.pdf';

    // Envoyer le fichier PDF au navigateur pour le téléchargement
    $pdf->Output($filename, 'D');

    // Terminer le script
    exit;
} else {
    // Si le panier est vide, rediriger ou afficher un message approprié
    header("Location: index.php"); // Rediriger vers la page d'accueil par exemple
    exit;
}
?>
