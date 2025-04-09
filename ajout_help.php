<?php
// Inclure l'autoloader de Composer pour Sendinblue
require_once(__DIR__ . '/vendor/autoload.php'); // Assure-toi que Composer est installé et que le chemin est correct

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

// Connexion à la base de données
$Serveur = "127.0.0.1:3306";
$utilisateur = "root";
$motdepasse = "";
$basededonner = "kawtar";
$connexion = new mysqli($Serveur, $utilisateur, $motdepasse, $basededonner);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Connection failed: " . $connexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécuriser les entrées utilisateur
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $REQUET = $connexion->prepare("insert into help (nom, mail, message) values (?, ?, ?)");
    $REQUET->bind_param("sss", $name, $email, $message);

    // Exécuter la requête
    if ($REQUET->execute()) {
        echo "Message saved successfully in the database. ";

        // Envoi d'email via Sendinblue API
        try {
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key',''); // Remplace par ta clé API Sendinblue

            $apiInstance = new TransactionalEmailsApi(
                new Client(),
                $config
            );

            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => 'Votre soumission de message',
                'sender' => ['name' => 'KAWTAR', 'email' => 'oussamateloua@icloud.com'],
                'to' => [['email' => $email, 'name' => $name]],
                'htmlContent' => "<html><body><h1>Bonjour $name!</h1><p>Merci pour nous avoir contactés. nous allons repodre le plus possible a ton message :</p><p>$message</p></body></html>"
            ]);

            // Envoi de l'email
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            header("Location:index_succe.html "); 
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : ", $e->getMessage();
        }
    } else {
        echo "Erreur lors de l'insertion dans la base de données : " . $REQUET->error;
    }

    // Fermer la requête et la connexion à la base de données
    $REQUET->close();
    $connexion->close();
}
?>
