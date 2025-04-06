<?php
$Serveur = "127.0.0.1:3306";
$utilisateur = "root";
$motdepasse = "";
$basededonner = "kawtar";

// Création de la connexion
$connexion = new mysqli($Serveur, $utilisateur, $motdepasse, $basededonner);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST["email"];
    $password = $_POST["password"];

    // Préparation de la requête pour vérifier si l'email et le mot de passe existent dans la base
    $REQUET = $connexion->prepare("select * from inscription where email = ? and password = ?");
    $REQUET->bind_param("ss", $mail, $password); // "ss" signifie deux paramètres de type string
    $REQUET->execute();
    $resultat = $REQUET->get_result();
    
    // Vérifier si des résultats ont été trouvés
    if ($resultat->num_rows > 0) {
        // L'utilisateur existe, on redirige vers la page principale
        header("Location: index_aceill.html"); // Remplacez "votre_page.php" par l'URL de votre page
        exit();
    } else {
        // L'utilisateur n'existe pas, afficher un message d'erreur
        header("Location: index_404.html");
    }

    $REQUET->close();
}

$connexion->close();
?>
