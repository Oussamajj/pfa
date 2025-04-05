<?php
$Serveur = "127.0.0.1:3306";
$utilisateur= "root";
$motdepasse="";
$basededonner="kawtar";
$connexion= new mysqli($Serveur,$utilisateur,$motdepasse,$basededonner);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST["email"];
    $password = $_POST["password"];
    
    // Requête SQL d'insertion dans la base de données
    $REQUET = "insert into inscription (email, password) values ('$mail', '$password')";
    $connexion->query($REQUET);
}
?>
