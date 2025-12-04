<!DOCTYPE html>
<html>
<head> 
    <title>Ctrl+Car</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css">
    <style>
    body { font-family: Arial, sans-serif; background:#f9f9f9 url('covoiturage.jpeg') center fixed ; padding: 20px; max-width: 800px; margin: 0 auto;}
    h1 { color: #083d60; }
    h2 {color: #3a007cff}
    nav a { margin-right: 15px; color: #1766a8; text-decoration: none; font-weight: bold;}
    #connexion-btn { float: right; }
    form { margin-top: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
    label { display: inline-block; width: 150px; margin-bottom: 5px; }
    input { margin-bottom: 10px; padding: 5px; }
    ul { list-style-type: none; padding: 0; }
    li { border: 1px solid #eee; margin: 10px 0; padding: 10px; background: #fff; }
    </style>
</head>
<body>
    <div id="connexion-btn"> 
        <a href="login.html">
            Connexion/ S'authentifier
        </a>
    </div>
    <h1>Ctrl+Car: Le covoiturage simple, rapide et sécurisé</h1>
    <h2>Trouvez votre covoiturage au clic près</h2>
   
    <nav>  <menu avec les actions php >
        <a href="configuration.php?action=liste">Liste des propositions</a>
        <a href="configuration.php?action=proposer">Proposer un covoiturage</a>
        <a href="configuration.php?action=rechercher">Rechercher un covoiturage</a>
        <a href="configuration.php?action=reserver">Réserver un covoiturage</a>
    </nav>
<?php
require_once 'config.php';

$action = $_GET['action'] ?? 'liste'; //concaténés URL

// 1. LISTE DES PROPOSITIONS 
if ($action === 'liste') { // si action = liste utilser tout 
    echo "<h2>Liste des propositions :</h2>";
    $sql = "SELECT * FROM propositions;";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $results = $sth->fetchAll(PDO::FETCH_ASSOC); // récupere tous les résultat 

    echo "<ul>";
    foreach ($results as $prop) {
        echo "<li> Proposition " . ($prop['idProposition']) . 
             " par " . ($prop['emailConducteur']) . 
             " - De: " . ($prop['villeDepart']) . 
             " à: " . ($prop['villeArrivee']) . 
             " - Places: " . ($prop['nbPlace']) . 
             " - Prix: " . ($prop['prix']) . " €" . 
             " - Date:" . ($prop['date']) . "</li>";
    }
    echo "</ul>";

// 2. PROPOSER UN COVOITURAGE 
} elseif ($action === 'proposer') {
    echo "<h2>Proposer un covoiturage</h2>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { //parametre inserer + une nvll ligne 
        $email = $_POST['emailConducteur'] ?? '';
        $villeDepart = $_POST['villeDepart'] ?? ''; 
        $villeArrivee = $_POST['villeArrivee'] ?? '';
        $date = $_POST['date'] ?? '';
        $places = ($_POST['nbPlaces'] ?? 0);
        $prix = ($_POST['prix'] ?? 0);

        if ($email && $villeDepart && $villeArrivee && $date && $places > 0 && $prix >= 0) {
            $sql = "INSERT INTO propositions (emailConducteur, villeDepart, villeArrivee, date, nbPlace, prix)
                    VALUES (:email, :villeDepart, :villeArrivee, :date, :places, :prix)";
            $sth = $dbh->prepare($sql); // préparation
            
            if ($sth->execute([
                ':email' => $email,
                ':villeDepart' => $villeDepart,
                ':villeArrivee' => $villeArrivee,
                ':date' => $date,
                ':places' => $places,
                ':prix' => $prix,
            ])) {
                echo "<p style='color: green;'>Proposition ajoutée avec succès !</p>";
            } else {
                echo "<p style='color: red;'>Erreur lors de l'ajout (Vérifiez si l'email existe dans la table Internautes).</p>";
            }
        } else {
            echo "<p style='color: orange;'>Merci de remplir tous les champs correctement.</p>";
        }
    }

    // Formulaire de proposition
    ?>
    <form method="post" action="configuration.php?action=proposer">
        <label>Email conducteur :</label>  
        <input type="email" name="emailConducteur" value="<?php echo ($_SESSION['email'] ?? ''); ?>" required>
        <br>
        <label>Ville de départ :</label>
        <input type="text" name="villeDepart" required>
        <br>
        <label>Ville d'arrivée :</label>
        <input type="text" name="villeArrivee" required>
        <br>
        <label>Date (AAMMJJ) :</label>
        <input type="text" name="date" pattern="\d{6}" required placeholder="Ex : 251230">
        <br>
        <label>Nombre de places :</label>
        <input type="number" name="nbPlaces" min="1" required>
        <br>
        <label>Prix (€) :</label>
        <input type="number" name="prix" min="0.01" step="0.01" required>
        <br>
        <button type="submit">Proposer le covoiturage</button>
    </form>
    <?php

//3. RECHERCHER UN COVOITURAGE 
} elseif ($action === 'rechercher') {
    echo "<h2>Rechercher un covoiturage</h2>";
    $depart = $_POST['depart'] ?? ''; 
    $arrivee = $_POST['arrivee'] ?? ''; 
    $date = $_POST['date'] ?? '';
    $prixMax = $_POST['prixMax'] ?? null;
    
    // Formulaire de recherche
    ?>
    <form method="post" action="configuration.php?action=rechercher">
        <label for="depart">Ville de départ :</label>
        <input type="text" id="depart" name="depart" value="<?php echo ($depart); ?>">
        <br>
        <label for="arrivee">Ville d'arrivée :</label>
        <input type="text" id="arrivee" name="arrivee" value="<?php echo ($arrivee); ?>">
        <br>
        <label for="date">Date (format AAMMJJ) :</label>
        <input type="text" pattern="\d{6}" id="date" name="date" placeholder="Ex : 251230" value="<?php echo ($date); ?>">
        <br>
        <label for="prixMax">Prix maximal (€) :</label>
        <input type="number" step="0.01" min="0" id="prixMax" name="prixMax" value="<?php echo ($prixMax ?? ''); ?>">
        <br>
        <button type="submit">Rechercher</button>
    </form>
    <?php
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // parametre inserer dans le corps du msg
        
        $sql = "SELECT * FROM propositions WHERE nbPlace > 0";
        $params = [];
        
        // Construction de la requête SQL de manière dynamique
        if ($depart) {
            $sql .= " AND villeDepart LIKE :depart";
            $params[':depart'] = "%$depart%";
        }
        if ($arrivee) {
            $sql .= " AND villeArrivee LIKE :arrivee";
            $params[':arrivee'] = "%$arrivee%";
        }
        if ($date) {
            $sql .= " AND date = :date";
            $params[':date'] = $date;
        }
        if ($prixMax) {
            $sql .= " AND prix <= :prixMax";
            $params[':prixMax'] = $prixMax;
        }

        $sth = $dbh->prepare($sql);
        $sth->execute($params);
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo "<h3>Résultats de la recherche</h3>";
            echo "<ul>";
            foreach ($results as $row) {
                echo "<li>Proposition #" . ($prop['idProposition']) .
                     " par " . ($prop['emailConducteur']) .
                     " de " . ($prop['villeDepart']) .
                     "< à " . ($prop['villeArrivee']) .
                     "- Places restantes: " . ($prop['nbPlace']) .
                     "- Prix: " . ($prop['prix']) . " €<br>";
                
                // Formulaire de réservation directe
                ?>
                <form method="post" action="configuration.php?action=reserver" style="border: none; padding: 0; display: inline-block;">
                    <input type="hidden" name="idProposition" value="<?php echo ($row['idProposition']); ?>" />
                    <input type="email" name="emailClient" placeholder="Votre email" value="<?php echo ($_SESSION['email'] ?? ''); ?>" required size="25" />
                    <button type="submit" title="Réserver cette place">Réserver</button>
                </form>
                </li>
                <?php
            }
            echo "</ul>";
        } else {
            echo "<p>Aucune proposition ne correspond à vos critères.</p>";
        }
    }
}

//  4. RÉSERVER UN COVOITURAGE 
elseif ($action === 'reserver') {
    echo "<h2>Réserver un covoiturage</h2>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idProposition = ($_POST['idProposition'] ?? 0);
        $emailClient = ($_POST['emailClient'] ?? '');

        if ($idProposition > 0 && $emailClient) {
            try {
                $dbh->beginTransaction();
                
                $sqlInsert = "INSERT INTO transports (idProposition, emailPassager) VALUES (:idProp, :emailPassager)";
                $sthInsert = $dbh->prepare($sqlInsert);
                $sthInsert->execute([':idProp' => $idProposition, ':emailPassager' => $emailClient]);
                
                $sqlUpdate = "UPDATE propositions SET nbPlace = nbPlace - 1 WHERE idProposition = :idProp AND nbPlace > 0";
                $sthUpdate = $dbh->prepare($sqlUpdate);
                $sthtUpdate->execute([':idProp' => $idProposition]);
                
                $dbh->commit();

                echo "<p style='color: green;'>Réservation effectuée avec succès !</p>";
            } catch (Exception $e) {
                $dbh->rollBack();
                echo "<p style='color: red;'>Erreur lors de la réservation : La proposition est peut-être déjà complète.</p>";
            }
        } else {
            echo "<p style='color: orange;'>Veuillez vérifier les informations de réservation.</p>";
        }
    } else {
        // Formulaire de réservation 
    ?>
        <form method="post" action="configuration.php?action=reserver">
            <label for="idProposition">ID Proposition :</label>
            <input type="number" id="idProposition" name="idProposition" min="1" required>
            <br>
            <label for="emailClient">Votre email :</label>
            <input type="email" id="emailClient" name="emailClient" value="<?php echo ($_SESSION['email'] ?? ''); ?>" required>
            <br>
            <button type="submit">Réserver</button>
        </form>
    <?php
    }
}
else {
    echo "<p>Action inconnue.</p>";
}
?>

</body>
</html>