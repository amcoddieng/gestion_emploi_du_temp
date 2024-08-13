<?php
session_start();
require_once 'function.php';
//appel de la base donnee
$dtb = new Database();
$db = $dtb->getConnection();
// demande d'inscription a une classe
if (isset($_POST['demande_inscription_a_une_classe'])) {
    $id=$_POST['carteEtudiant'];
    $md=$_POST['password'];
    if ($id == $_SESSION['matricule']) {   
        $user = new Utilisateur($db);
        if ($user->connection($id) == true) {
            if ($user->getMdp() == $md) {
                $et = new Etudiant($db);
                $et->setId($id);
                $et->setClasseId($_POST['classe']);
                if($et->demandeInsClasse()){
                    echo "Demande en cours traitement";
                }else
                {
                    echo "echec de la demande :vous avez deja un demande en cours";
                }

            }else{
                echo"mot de passe incorrect";
             }
            
 }
    // echo "ok";
}else{
    echo"les donnees ne correspond pas a vos donnees";
}
}


if (isset($_POST[''])) {
    # code...
}


// modification d'un users par lui meme
if (isset($_POST['mod_user_profil'])) {
    $utilisateur = new Utilisateur($db);
    $utilisateur->connection($_SESSION['matricule']);
    $utilisateur->setPrenom($_POST['prenom']);
    $utilisateur->setNom($_POST['nom']);
    $utilisateur->setEmail($_POST['email']);
    $utilisateur->setTelephone($_POST['telephone']);
    $utilisateur->setDateNaiss($_POST['date_naiss']);
    $utilisateur->setLieuNaiss($_POST['lieu_naiss']);
    $utilisateur->setStatus($_SESSION['status']);
    if ($utilisateur->updateUtilisateur()) {
        header('Penseignant.php?#profil');
        echo "Profil mis à jour avec succès ,actualiser votre page.";
    } else {
        echo "Erreur lors de la mise à jour du profil.";
    }
// echo "nice";
}

// emploi du temp de l'etudiant
if (isset($_GET['tab_edt_etudiant'])) {
    $cour = new Cours($db);    
    $cour->setclasseId($_SESSION['classe_id']);
    $emploiDuTemps = $cour->getEmploiDuTempsEtudiant();

    // Définir les jours de la semaine
    $jours = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');

    // Initialiser un tableau pour stocker les horaires de l'emploi du temps
    $horaires = [];
    for ($heure = 8; $heure < 18; $heure++) {
        foreach ($jours as $jour) {
            $horaires[$jour][$heure] = ''; // Valeur par défaut vide
        }
    }

// Exemple de données pour $jours et $horaires
$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi']; // Assurez-vous que ces jours sont corrects
$horaires = array_fill_keys($jours, array_fill(8, 10, '')); // Création d'un tableau initialisé à 'Libre'

// Remplir le tableau avec les données de l'emploi du temps
foreach ($emploiDuTemps as $cours) {
    $heure = $cours['horaire'];
    $jour = strtolower($cours['jour']);
    
    // Vérifier si le jour et l'heure existent dans le tableau
    if (isset($horaires[$jour][$heure])) {
        $cour->setsalleId($cours['salle_id']);
        if($cour->Salle() != null){
            $salle = $cour->Salle();
        }else{
            $salle = "en attente de salle";
        }
        $horaires[$jour][$heure] = 'Module: ' . htmlspecialchars($cours['nom_module']) . '<br>'
                                  . 'Prof: ' . htmlspecialchars($cours['prenom']) . ' ' . htmlspecialchars($cours['nom']) . '<br>'
                                  . 'Salle: ' . $salle;;
    }
}

// Début de l'affichage du HTML
echo '<div class="container mt-5">';
echo '<h2>Emploi du Temps</h2>';

// Affichage du tableau Bootstrap
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Horaire</th>';

// Afficher les en-têtes de colonnes pour chaque jour
foreach ($jours as $jour) {
    echo '<th>' . ucfirst($jour) . '</th>';
}

echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Affichage des lignes pour chaque heure
for ($heure = 8; $heure < 18; $heure++) {
    echo '<tr>';
    echo '<td>' . $heure . 'h</td>';

    // Afficher les données pour chaque jour
    foreach ($jours as $jour) {
        $contenu = isset($horaires[$jour][$heure]) ? $horaires[$jour][$heure] : 'Libre';
        echo '<td>' . $contenu . '</td>';
    }

    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';
}

// liste des classes dans le select
if(isset($_GET['les_classes_list'])){
    echo"<option value=''>selection une classe";
    $lesClasse = recup($db);
    foreach ($lesClasse as $key => $value) {
        echo"
            <option class='ClasseChoisi' value=".$value['id'].">".$value['niveau']." ".$value['formation']."</option>
        ";
    }
}
// les notifications de l'etudiant
if(isset($_GET['notification'])){
    $notifications = notification($db,$_SESSION['matricule']);
    echo"
    <div class='container'>
    <h2>Notifications</h2>";
    foreach ($notifications as $notification){
        if ($notification['lu'] == "non") {
            lireMessage($db,$notification['id']);
        echo  "<div class='alert alert-info' role='alert'>
                <strong>". htmlspecialchars($notification['titre'])."</strong><br>
                ".htmlspecialchars($notification['motif'])."<br>
                <small>".htmlspecialchars($notification['date'])."</small>
            </div>";   
        }
    }
    foreach ($notifications as $notification){
        if ($notification['lu'] == "oui") {
        
        echo  "<div class='alert border' role='alert'>
                <strong>". htmlspecialchars($notification['titre'])."</strong><br>
                ".htmlspecialchars($notification['motif'])."<br>
                <small>".htmlspecialchars($notification['date'])."</small>
            </div>";   
        }
    }
echo "</div>";
}
// les demande de l'etudiant            
if (isset($_GET['demande_encour_traitement'])) {
    $demandes = LesDemandesDinscription1etudiant($db,$_SESSION['matricule']);
    foreach ($demandes as $demande){
        // if ($notification['lu'] == "oui") {
        echo  "<div class='alert border' role='alert'>
                <small> Votre etat de demande d'inscrire en ". htmlspecialchars($demande['niveau'])."
                ".htmlspecialchars($demande['formation'])." est : </small><strong>".htmlspecialchars($demande['autorisation'])."</strong><br>
                <small>".htmlspecialchars($demande['date'])."</small>
            </div>";   
        // }
    }
}
// Deconnexion
if (isset($_GET['deconnexion'])) {
    session_destroy();
    header('location:connexion.php');
}








?>