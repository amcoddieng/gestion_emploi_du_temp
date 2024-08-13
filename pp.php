<?php

session_start();
require_once 'function.php';

// Appel de la base de données
$dtb = new Database();
$db = $dtb->getConnection();

// Check if the form was submitted
if (isset($_POST['insetude'])) {
    // Retrieve form data
    $id = $_SESSION['matricule']; // Assuming this is the ID
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $date_naiss = $_POST['dnaiss'];
    $lieu_naiss = $_POST['lnaiss'];
    $status = 'etudiant';
    // Set this if you have a password field

    // Create a new instance of the Utilisateur class
    $utilisateur = new Utilisateur($db);
    $utilisateur->setId($id);
    $utilisateur->setPrenom($prenom);
    $utilisateur->setNom($nom);
    $utilisateur->setEmail($email);
    $utilisateur->setTelephone($telephone);
    $utilisateur->setDateNaiss($date_naiss);
    $utilisateur->setLieuNaiss($lieu_naiss);
    $utilisateur->setStatus($status);
    $utilisateur->setMdp($_POST['mdp']);

    // Insert into the database
    if ($utilisateur->insertUtilisateur()) {
        $etudiant = new Etudiant($db);
        $etudiant->setId($id);
        $_SESSION['classe_id']=$etudiant->recup_de_etudiant();
        $etudiant->setClasseId($etudiant->cl());
        $etudiant->insert();
        $_SESSION['connexion'] ="entrer vos identifiant pour ce connecter";
        header('location:connexion.php');
    } else {
        echo "Une erreur s'est produite lors de l'ajout de l'utilisateur.";
    }
}
// if(isset($_POST['modmod'])){

//     $user = new Utilisateur($db);
//     $user->setId($_POST['id']);
//     $user->updatePassword($_POST['modmod']);
//     echo"Reussi";
// }
?>
