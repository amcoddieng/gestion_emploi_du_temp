<?php
session_start();
require_once 'function.php';

//appel de la base donnee
$dtb = new Database();
$db = $dtb->getConnection();

// emploi du temp de l'etudiant
if (isset($_GET['tab_edt_classe'])) {
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
$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi','samedi'];
// Création d'un tableau initialisé à 'Libre'
$horaires = array_fill_keys($jours, array_fill(8, 10, '')); 

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
            $salle = "no dispo";
        }
        $horaires[$jour][$heure] = ' ' . htmlspecialchars($cours['nom_module']) . '<br>'
                                  . ' ' . htmlspecialchars($cours['prenom']) . ' ' . htmlspecialchars($cours['nom']) . '<br>'
                                  . ' '. $salle;
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
    echo '<tr data-heure='.$heure.'>';
    echo '<td>' . $heure . 'h</td>';

    // Afficher les données pour chaque jour
    foreach ($jours as $jour) {
        $contenu = isset($horaires[$jour][$heure]) ? $horaires[$jour][$heure] : 'Libre';
        echo '<td class="jourMod" data-jour='.$jour.'>' . $contenu . '</td>';
    }
    echo '</tr>';
}

echo '</tbody>';    
echo '</table>';
echo '</div>';
}
// modal pour modifier l'emploi du temp
if (isset($_GET['modal_modifie_cours'])) {
    $ens = new Enseignant($db);
    $Prof=$ens->RecupLesprof();
    $ens = new Module($db);
    $cours=$ens->RecupLesmodule();
    // echo "hkdldkh";
    echo "
    <div class='modal-content'>
        <div class='d-flex justify-content-end'>
            <span class='close-modal'>&times;</span>
        </div>
        <h3>Choisir le cours</h3>
        <select class='choixCours'>";
         foreach ($cours as $key => $value) {
            echo "<option value=".$value['id'].">".$value['nom_module']."</option>";
        }
        echo "</select>
        <h3>Choisir le prof</h3>
        <select class='choisirprof'>";
           // Initialisation des objets Disponibilite et Cours
$dispo = new Disponibilite($db);
$jour = $_GET['jour'];
$heure = $_GET['heure'];

$dispo->setjour($jour);
$dispo->setheure($heure);

$cour = new Cours($db);
$cour->setjour($jour);
$cour->setheure($heure);

// Affichage des professeurs disponibles
foreach ($Prof as $key => $value) {
    $dispo->setidEnseignant($value['matricule']);

    if ($dispo->estDisponible() === 1) {
        // if ($cour->dispoNiveau2($value['matricule']) == true) {
            // Affichage des professeurs disponibles
            echo "<option value=\"" . htmlspecialchars($value['matricule']) . "\">" . htmlspecialchars($value['prenom']) . " " . htmlspecialchars($value['nom']) . " --SPECIALITE : " . htmlspecialchars($value['specialite']) . "</option>";
        // }
    }
}

        echo "</select><br>
        <button id='creerCours' class='btn btn-primary '>ajouter dans l'emploi du temp</button>
        <button id='suppCours' class='btn btn-danger '>supprimer le cours</button>
        </div>";
}
if (isset($_POST['soumettre-classe'])) {
    // echo $_POST['nom']."".$_POST['volume'];
   $mod = new Module($db);
   $mod->setnom($_POST['nom']);
   $mod->setvolume_horaireTotal($_POST['volume']);
   if ($mod->insert()) {
    echo "Cours ajoute avec succe";
   }else{
    echo "operation echec";
   }
}


// liste des cours
if (isset($_GET['liste_des_cours'])) {
    $R = new ClasseModule($db);
    $R->setClasseId($_SESSION['classe_id']);
    $lesClasse = $R->getDetailclass();
    if ($lesClasse === null) {
        echo "<h3>Liste vide</h3>";
    }else{
    echo "
    <table class='table table-striped table-bordered'>
        <thead class='thead-custom'>
            <tr>
                <th>Nom cours</th>
                <th>Volume fait</th>
                <th>volume total a faire</th>
                <th>Professeur</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>";
    foreach ($lesClasse as $key => $value) {
        echo "<tr>
            <td>".$value['nom_module']."</td>
            <td>".$value['volume_horaire_faite']."</td>
            <td>".$value['volume_horaire']. "</td>
            <td>".$value['prenom']." ".$value['nom']."</td>
            <td><button class='btn btn-danger btn_suprimer_ocurs' value=".$value['id_clm'].">Supprimer</button></td>
        </tr>";
    }
    echo "</tbody></table>";
    }
}
if (isset($_GET['changer_le_planing'])) {
    $coursId = $_GET['cours']; 
    $jour = $_GET['jour'];
    $heure = $_GET['heure'];
    $prof = $_GET['prof'];

    $cours = new Cours($db);
    $cours->setmoduleId($coursId); 
    $cours->setclasseId($_SESSION['classe_id']);
    $cours->setIdenseignant($prof);
    $cours->setjour($jour);
    $cours->setheure($heure);

    if ($cours->dispoNiveau2()) {
        if ($cours->mettreAJourCours()) {
            $m = new Module($db);
            $m->setId($coursId);
            $volume = $m->getVolumeHoraireById();

            $cl = new ClasseModule($db);
            $cl->setProfId($prof);
            $cl->setModule($coursId);
            $cl->setClasseId($_SESSION['classe_id']);
            $cl->setVolumeHoraire($volume);
            if ($cl->exists() == false) {
            $cl->insert();
            echo "Mise à jour réussie";  
            }
        } else {
            echo "Échec de la mise à jour";
        }
    }else{
        echo"le prof est dispo est deja pris";
    }
}
if (isset($_GET['supprimer_de_edt'])) {
    $cour = $_GET['cours'];
    $prof = $_GET['prof'];
    $jour = $_GET['jour'];
    $heure = $_GET['heure'];
    $cours = new Cours($db);
    $cours->setclasseId($_SESSION['classe_id']);
    $cours->setheure($heure);
    $cours->setjour($jour);
    var_dump($cours);
    if ($cours->supprimerCours()) {
        echo "Le cours a été supprimé avec succès.";
    } else {
        echo "Une erreur s'est produite lors de la suppression du cours.";
    }
}
if (isset($_GET['supCLm'])) {
    $id = $_GET['supCLm'];
    $classeModule = new ClasseModule($db);
    $classeModule->setId($id);
    if ($classeModule->supprimer()) {
        echo "L'enregistrement a été supprimé avec succès.";
    } else {
        echo "Une erreur s'est produite lors de la suppression de l'enregistrement.";
    }
}


?>