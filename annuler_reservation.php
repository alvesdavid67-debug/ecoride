<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_resa = $_GET['id'];
$id_user = $_SESSION['user_id'];

// Récupérer la réservation
$sql = "SELECT reservation.*, covoiturage.prix_personne 
        FROM reservation 
        JOIN covoiturage ON reservation.covoiturage_id = covoiturage.covoiturage_id
        WHERE reservation.reservation_id = ? AND reservation.utilisateur_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_resa, $id_user]);
$resa = $stmt->fetch();

if ($resa) {
    // Rembourser les crédits
    $sql2 = "UPDATE utilisateur SET credits = credits + ? WHERE utilisateur_id = ?";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([$resa['prix_personne'], $id_user]);

    // Remettre la place disponible
    $sql3 = "UPDATE covoiturage SET nombre_place = nombre_place + 1 WHERE covoiturage_id = ?";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([$resa['covoiturage_id']]);

    // Mettre à jour le statut
    $sql4 = "UPDATE reservation SET statut = 'annulée' WHERE reservation_id = ?";
    $stmt4 = $pdo->prepare($sql4);
    $stmt4->execute([$id_resa]);

    // Envoyer un mail de notification
    $sql_user = "SELECT email FROM utilisateur WHERE utilisateur_id = ?";
    $stmt_user = $pdo->prepare($sql_user);
    $stmt_user->execute([$id_user]);
    $user_info = $stmt_user->fetch();

    $to = $user_info['email'];
    $subject = "Annulation de votre réservation EcoRide";
    $message = "Bonjour,\n\nVotre réservation pour le trajet " . $resa['lieu_depart'] . " → " . $resa['lieu_arrivee'] . " a été annulée.\n\nVos crédits ont été remboursés.\n\nL'équipe EcoRide";
    $headers = "From: contact@ecoride.fr";

mail($to, $subject, $message, $headers);
}

header('Location: historique.php');
exit;
?>