<?php
// identification.php
require_once 'config.php'; // Inclut la connexion à la BDD et démarre la session

$login = $_POST['login'] ?? ''; // L'adresse mail de l'utilisateur valeur vide si champ pas p
$pass = $_POST['pass'] ?? '';   // Le mot de passe

if (empty($login) || empty($pass)) {
    // Si des champs sont vides, rediriger vers le login
    header('Location: login.html?erreur=champs_vides');
    exit();
}

// 1. Préparer la requête pour trouver l'internaute
$sql = "SELECT email, motDePasse FROM internautes WHERE email = :login";
$stmt = $dbh->prepare($sql);
$stmt->execute([':login' => $login]);
$internaute = $stmt->fetch(PDO::FETCH_ASSOC);

if ($internaute && $internaute['motDePasse'] === $pass) {
    // 2. Connexion réussie : Stocker l'email dans la session
    $_SESSION['email'] = $internaute['email'];
    $_SESSION['estConnecte'] = true;
    
    // 3. Rediriger vers la page d'accueil
    header('Location: configuration.php?action=liste');
    exit();
} else {
    // 4. Échec de la connexion : Rediriger vers le login avec erreur
    header('Location: login.html?erreur=identifiants_invalides');
    exit();
}
?>