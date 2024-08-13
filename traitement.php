<?php 
session_start();
require_once 'function.php';


//appel de la base donnee
$dtb = new Database();
$db = $dtb->getConnection();

if (isset($_POST['soumettre-prof'])) {
    $p=new enseignant($db);
    $p->setId($_POST['matricule']);
    $p->setPrenom($_POST['prenom']);
    $p->setNom($_POST['nom']);
    $p->setSpecialite($_POST['specialite']);
    $p->setTelephone($_POST['telephone']);
    $p->setEmail($_POST['email']);
    $p->setDateNaiss($_POST['dnaiss']);
    $p->setLieuNaiss($_POST['lnaiss']);
    if ($p->insert() == true) {
        $dispo=new Disponibilite($db);
        $dispo->setidEnseignant($_POST['matricule']);
        $dispo->DefaultDispo();
        echo"<h3 style='color:green'>Ajouter du prof reussi</h3>";
    }
}
    if (isset($_POST['ajoutclasse'])) {
        // echo "Classe ajoutéeee avec succès!";
        $c=new classe($db);
        $c->setNiveau($_POST['niveau']);
        $c->setFormation($_POST['nom']);
        if($c->insert()){
            echo "Classe ajoutéeee avec succès";
        }
    }
      if (isset($_POST['ajoutprofresp'])) {
        $c=new classe($db);
        $c->setId($_POST['idclasse']);
        $c->setProfResp($_POST['matricule']);
        $c->designeProfResp();
    } 
    if (isset($_POST['soumettre-gest'])) {
        $p=new GestionnaireSalles($db);
        $p->setId($_POST['matricule']);
        $p->setPrenom($_POST['prenom']);
        $p->setNom($_POST['nom']);
        $p->setTelephone($_POST['telephone']);
        $p->setEmail($_POST['email']);
        $p->setDateNaiss($_POST['dnaiss']);
        $p->setLieuNaiss($_POST['lnaiss']);
        // if ($p->insertUtilisateur() == true) {
            $p->insert();
        // }
    }
    // A REVOIR POUR COORIGER L'INSERTION DE L'ETUDIANT
    if (isset($_POST['soumettre-etud'])) {
        $p = new etudiant($db);
        $p->setClasseId(11);
        $p->setId($_POST['matricule']);
        $p->setPrenom($_POST['prenom']);
        $p->setNom($_POST['nom']);
        $p->setTelephone($_POST['telephone']);
        $p->setEmail($_POST['email']);
        $p->setDateNaiss($_POST['dnaiss']);
        $p->setLieuNaiss($_POST['lnaiss']);
        // if (
        $p->insertUtilisateur();
        // ) {
            $p->insert();
        // } else {
        //     echo"Échec de l'insertion";
        // }
        echo"<pre>".var_dump($p)."</pre>";
    }
    // retourne l'emploie du temp du prof(disponibilite)
    if (isset($_GET['tab_dispo_enseignant'])) {
        $en = new Disponibilite($db);
        $en->setidEnseignant($_SESSION['matricule']);
        $disponibilites = $en->getDisponibilite();
        $jours = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
        
        echo '<table class="table table-striped table-bordered">';
        echo '<thead class="thead-custom  bg-primary">';
        echo '<tr>';
        echo '<th>Heure</th>';
        
        foreach ($jours as $jour) {
            echo '<th>' . ucfirst($jour) . '</th>';
        }
        
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        for ($heure = 8; $heure < 18; $heure++) {
            echo '<tr>';
            echo '<td>' . $heure . 'h</td>';
            
            foreach ($jours as $jour) {
                $etat = $disponibilites[$jour][$heure];
                if ($etat == "dispo") {
                
                echo '<td style="background-color:green">';
                echo '<span id="etat">' . $etat . '</span>';
                echo ' <br><span class="btn-dispo" data-jour="' . $jour . '" data-heure="' . $heure . '" data-etat="' . $etat . '">changer</span>';
                echo '</td>';    
                }else{
                    echo '<td style="background-color:red">';
                    echo '<span id="etat">' . $etat . '</span>';
                    echo ' <br><span class="btn-dispo" data-jour="' . $jour . '" data-heure="' . $heure . '" data-etat="' . $etat . '">changer</span>';
                    echo '</td>'; 
                }
            }
            
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>
            <button class="rrrr"><h3>imprimer</h3></button>';
    
    }
    // mise a jour de disponibilite
    if (isset($_POST['mod_etat'])) {
        $enseignant_id = $_SESSION['matricule'];
        $jour = $_POST['jour'];
        $heure = $_POST['heure'];
        $etat = $_POST['etat'];
    
        $enseignant = new Disponibilite($db);
        $enseignant->setidEnseignant($enseignant_id);
        $enseignant->updateDisponibilite($jour, $heure, $etat);
        echo "ok";
    }
    
    // emploi du temps du prof
    if (isset($_GET['tab_edt_prof'])) {
        $cour = new Cours($db);    
        $cour->setIdenseignant($_SESSION['matricule']);
        $emploiDuTemps = $cour->getEmploiDuTempsProf();
    
        // Définir les jours de la semaine
        $jours = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
    
        // Initialiser un tableau pour stocker les horaires de l'emploi du temps
        $horaires = [];
        for ($heure = 8; $heure < 18; $heure++) {
            foreach ($jours as $jour) {
                $horaires[$jour][$heure] = ''; // Valeur par défaut vide
            }
        }
    
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
                $horaires[$jour][$heure] = 'Module: ' . htmlspecialchars($cours['nom_module']) . '<br>'
                                          . 'Classe: ' . htmlspecialchars($cours['formation']) . ' ' . htmlspecialchars($cours['niveau']) .' <br>'
                                          . 'Salle: '.$salle;
            }
        }
    
        // Début de l'affichage du HTML
        echo '<div class="container mt-5">';
        echo '<h2 class="text-center">Emploi du Temps</h2>';
    
        // Affichage du tableau Bootstrap
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-dark">';
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
                echo '<td class="confirmerCour" data-jour='.$jour.'>' . $contenu . '</td>';
            }
    
            echo '</tr>';
        }
    
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
    
    // ici on regle la disponibilite de l'enseignant
    if (isset($_GET['jour'])) {
        $jour=$_GET['jour'];
        $heure=$_GET['heure'];
        $id=1;
        $d = new Disponibilite($db);
        $d->setidEnseignant($id);
        $d->setjour($jour);
        $d->setheure($heure);
        if ($d->etatDispo() == 'occupe') {
            $d->setetat('dispo');
            $d->changerEtat();

        }else{
            $d->setetat('occupe');
            $d->changerEtat();
        }
    }
    if (isset($_POST['EtudiantDemandeInsClasse'])) {
        $carteEtudiant = $_POST['carteEtudiant'];
        $classeId = $_POST['classe'];
        $password = $_POST['password'];
         $p=new etudiant($db);
         $p->setId($carteEtudiant);
         $p->setClasseId($classeId);
         $p->demandeInsClasse();
    }
// récupérer les détails d'un prof responsable
if (isset($_GET['matricule_prof_responsable'])) {
    $matricule = $_GET['matricule_prof_responsable'];
    $prof = new Enseignant($db);
    $prof->setId($matricule);
    $det = $prof->Recupprof(); 
    if ($det != null) {
        $g = "
                <img src='' class='img-prof-resp'>
        <h2 id='det_nom_prof_resp'>". $det['prenom'] . " " . $det['nom'] ."</h2>
        <h3 id='det_matricule_prof_resp'>Matricule : ". $det['matricule'] ."</h3>
        <H3 id='det_specialite_prof_resp'>Specialite : ". $det['specialite'] ."</H3>
        <h3 id='det_email_prof_resp'>Email : ". $det['email'] ."</h3>
        <H3 id='det_telephone_prof_resp'>Numero Telephone : ". $det['telephone'] ."</H3>
        ";
        echo $g;
    } else {
        var_dump($det);
    }
}
// RETOURNE TOUTE LES ETUDIANTS D'UNE CLASSE

if (isset($_GET['getEtudeFormation'])) {
    $etudiants = ReturnEtudiantClasses($db, $_GET['getEtudeFormation']);
    if ($etudiants != null) {
        echo "
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Matricule</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
        ";
        foreach ($etudiants as $key => $value) {
            echo "
                <tr>
                    <td>" . htmlspecialchars($value['prenom'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($value['nom'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($value['matricule'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($value['email'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($value['telephone'], ENT_QUOTES, 'UTF-8') . "</td>
                </tr>
            ";
        }
        echo "
            </tbody>
        </table>
        ";
    } else {
        echo "<h3>Pas encore d'étudiants inscrits à cette formation</h3>";
    }
}


// accepter la demande d'un etudiants
if (isset($_GET['AcceptEtudiant'])) {
    accepterDemande($db,$_GET['AcceptEtudiant'],$_GET['IDclassE']);
    $user = new Utilisateur($db);
    if ($user->connection($_GET['AcceptEtudiant']) == true) {
        $user->modClasse($_GET['IDclassE'],$_GET['AcceptEtudiant']);
    }
}
// refuser la demande d'un etudiants
if (isset($_GET['refuserEtudiant'])) {
    refuserDemande($db,$_GET['refuserEtudiant'],$_GET['IDclassE']);
}
// retourne les classes avec leur prof responsable avec une possible de modifier
if (isset($_GET['ListClassProf'])) {
    $lesClasse = recup($db);
    echo "
    <table class='table table-striped table-bordered'>
        <thead class='thead-custom'>
            <tr>
                <th>Formation</th>
                <th>Niveau</th>
                <th>Matricule Responsable</th>
                <th>Ajouter/Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>";
    foreach ($lesClasse as $key => $value) {
        echo "<tr>
            <td>".$value['formation']."</td>
            <td>".$value['niveau']."</td>
            <td>";
            if ($value['prof_responsable_id'] !== null) {
                echo '<span class="detail_responsable" value='.$value['prof_responsable_id'].'>'.$value['prof_responsable_id'].'</span>';
            } else {
                echo "pas encore de prof";
            }
            echo "</td>
            <td><button class='btn btn-warning btn_mod_prof' value=".$value['id'].">Modifier Prof</button></td>
            <td><button class='btn btn-danger btn_suprimer_prof' value=".$value['id'].">Supprimer Prof</button></td>
        </tr>";
    }
    echo "</tbody></table>";
}

// retourne les profs non responsable de classe
if (isset($_GET['modal_prof'])) {
    $lesNResp = Recupprof_nonResp($db);
    echo "
    <div class='modal-content'>
        <div class='d-flex justify-content-end'>
            <span class='close-modal'>&times;</span>
        </div>
        <h3>Sélectionner un Professeur</h3>
        <ul id='SelectFormation' class='list-group'>";
    if ($lesNResp != null) {
        foreach ($lesNResp as $key => $value) {
            echo "<li class='list-group-item P_prof_resp' id='".$value['matricule']."'>".$value['prenom']." ".$value['nom']." -- ".$value['matricule']."</li>";
        }
    } else {
        echo "<li class='list-group-item text-danger'>Aucun prof disponible</li>";
    }
    echo "</ul>
    </div>";
}

// désigner un professeur responsable
if (isset($_GET['Confprof_resp'])) {
    designeProfResp($db, $_GET['idclasseChoisi'], $_GET['Confprof_resp']);
    echo $_GET['Confprof_resp'];
}

// suppression d'un professeur responsable
if (isset($_GET['suprimer_prof_resp'])) {
    SupprimerProfResp($db, $_GET['suprimer_prof_resp']);
    echo "ras";
}

 if (isset($_GET['Liste_des_prof'])) {
    $lesProfs=RecupLesprof($db);
    // Supposons que $lesProfs soit défini et contienne les données des enseignants
    foreach ($lesProfs as $key => $value) {
        echo "
        <tr>
            <td>{$value['prenom']}</td>
            <td>{$value['nom']}</td>
            <td>{$value['matricule']}</td>
            <td>{$value['specialite']}</td>
            <td>{$value['email']}</td>
            <td>{$value['telephone']}</td>
            <td><button value=".$value['matricule']." class='btn btn-danger supProf'>supprimer</button></td>
        </tr>
        ";
    }

 }
 // supprimer un prof
if (isset($_POST['supProfs'])) {
    $enseignant = new Enseignant($db);
    $enseignant->setId($_POST['val']);
    if ($enseignant->deleteEnseignant()) {
        echo "L'enseignant a été supprimé avec succès.";
    } else {
        echo "La suppression de l'enseignant a échoué.";
    }
}
// liste des classes
 if (isset($_GET['Liste_des_classs'])) {
    $lesClasse = recup($db);
   
    foreach ($lesClasse as $key => $value) {

        echo "
        <tr>
            <td>".$value['formation']."</td>
            <td>".$value['niveau']."</td>
            <td>".$value['prof_responsable_id']."</td>
            <td>
                <button class='btn btn-danger btn-sm supCl' data-id='".$value['id']."'>Supprimer</button>
            </td>
        </tr>
        ";
    }

 }
// supprimer classe
if (isset($_POST['supClasse'])) {
    $classe = new Classe($db);
$classe->setId($_POST['id']);
if ($classe->deleteClasse()) {
    echo "Classe supprimée avec succès.";
} else {
    echo "Erreur lors de la suppression de la classe.";
}
}
// liste des gestionnaires
if (isset($_GET['Liste_des_gest'])) {
    $lesGest=RecupLesGest($db);
   echo'    <div class="container mt-5">
    <h2 class="text-center">Liste des Gestionnaires</h2>
    <table class="table table-striped table-bordered mt-4">
        <thead class="thead-custom">
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Matricule</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($lesGest as $key => $value) {
            echo "
            <tr>
                <td>".$value['prenom']."</td>
                <td>".$value['nom']."</td>
                <td>".$value['matricule']."</td>
                <td>".$value['email']."</td>
                <td>".$value['telephone']."</td>
                <td>
                    <button class='btn btn-danger btn-sm supGest' data-id='".$value['matricule']."'>Supprimer</button>
                </td>
            </tr>
            ";
        }
        echo"  </tbody>
    </table>
</div>";
 }
//  supprimer getionnaire
if (isset($_POST['supGest'])) {
    $gestionnaire = new GestionnaireSalles($db);
    $gestionnaire->setId($_POST['id']);
    $result = $gestionnaire->supprimerGest();
    if ($result) {
        echo "Gestionnaire supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression.";
    }
}
//  connexion a un compte
if (isset($_POST['connexion'])) {
    $id=$_POST['id'];
    $md=$_POST['md'];
    $user = new Utilisateur($db);
    if ($user->connection($id) == true) {
        if ($user->getMdp() == $md) {
            if ($user->getStatus() == "enseignant") {
                $_SESSION['status']='enseignant';
                $_SESSION['matricule']=$id;
                // je cherche si le prof est un prof responsable 
                // si c'est le cas il aura une option de plus par rapport a un prof simple
                $ens = new Enseignant($db);
                $ens->setId($id);
                if($ens->Est_il_responsable() == true){
                    $_SESSION['Prof_resp']=true;
                    $_SESSION['classe_id']=$ens->retourneClasse();

                }else{
                    $_SESSION['Prof_resp']=false;
                }
                header('location:Penseignant.php');
            }elseif ($user->getStatus() == "etudiant") {
                $etudiant = new Etudiant($db);
                $etudiant->setId($id);
                $_SESSION['classe_id']=$etudiant->recup_de_etudiant();
                $_SESSION['status']='etudiant';
                $_SESSION['matricule']=$id;
                header('location:Petudiant.php');
            }elseif ($user->getStatus() == "gestionnaire") {
                $_SESSION['status']='gestionnaire';
                $_SESSION['matricule']=$id;
                header('location:Pgestion.php');
            }elseif ($user->getStatus() == "admin") {
                $_SESSION['status']='admin';
                $_SESSION['matricule']=$id;
                header('location:Padmin.php');
            }
        }else{
            $_SESSION['connexion'] = "echec de la connexion : verifier vos donnees";
            header('location:connexion.php');
        }
    }else{
            $etudiant = new Etudiant($db);
            $etudiant->setId($id);
            if($etudiant->isAuthorized()){
                header('location:test.php');
                $_SESSION['status']='etudiant';
                $_SESSION['matricule']=$id;
                
            }else{
                header('location:connexion.php');
                $_SESSION['connexion'] = "echec de la connexion : verifier vos donnees";
            
            }
        
    }
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
// Deconnexion
if (isset($_GET['deconnexion'])) {
    session_destroy();
    header('location:connexion.php');
}
// les notifications de l'prof
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



if (isset($_POST['MODconfirmerCours'])) {
echo'<div class="container d-block align-items-center justify-content-center mt-4">
<span>valider si vous avez fait le cours</span><br>
<div class="d-flex align-items-center justify-content-center">
<button class="val btn btn-success mx-auto " data-id="">valider</button>
<button class=" sup btn btn-danger delete-btn" data-id="">fermer</button>
</div>
</div>';
}
// incrmenter un cou ou confirmation d'un cours
if (isset($_POST['confirmerCours'])) {
    $heure = isset($_POST['heure']) ? htmlspecialchars($_POST['heure']) : '';
    $jour = isset($_POST['jour']) ? htmlspecialchars($_POST['jour']) : '';

    if ($heure && $jour) {
        try {
            // Instanciation des objets
            $cours = new Cours($db);
            $cours->setHeure($heure);
            $cours->setJour($jour);
            $cours->setIdenseignant($_SESSION['matricule']);

            // Confirmation du cours
            $mod = $cours->CoursIns($heure, $jour, $_SESSION['matricule']);
            
            if ($mod && is_array($mod)) {
                foreach ($mod as $value) {
                    $cl = $value['classe_id'];
                    $md = $value['module_id'];
                }
                
                // Mise à jour du volume horaire
                $clm = new ClasseModule($db);
                $clm->setProfId($_SESSION['matricule']);
                $clm->setModule($md);
                $clm->setClasseId($cl);
                
                if ($clm->incrementVolumeHoraire()) {
                    // Suppression du cours
                    $cours->setClasseId($cl);
                    $cours->setHeure($heure);
                    $cours->setJour($jour);
                    
                    if ($cours->supprimerCours()) {
                        echo "Confirmation réussie";
                    } else {
                        echo "Une erreur s'est produite lors de la confirmation du cours.";
                    }
                } else {
                    echo "Une erreur s'est produite lors de l'incrémentation du volume horaire.";
                }
            } else {
                echo "Aucun module trouvé pour les paramètres fournis.";
            }
        } catch (Exception $e) {
            echo "Une erreur s'est produite : " . $e->getMessage();
        }
    } else {
        echo "Les données fournies sont invalides.";
    }
}
if (isset($_POST['register'])) {
    // Récupérer les données du formulaire d'inscription
    $matricule = $_POST['matricule'];
    $cl = $_POST['cl'];
// echo "$cl";
        // Exemple d'insertion des données d'inscription
        $query = 'INSERT INTO demande_inscription (id_etudiant, id_classe) VALUES (:matricule, :cl)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->bindParam(':cl', $cl);

        if ($stmt->execute()) {
            $_SESSION['connexion']="en cours de traitement";
            header('location:connexion.php');
        } else {
            $_SESSION['connexion']="echec";
            header('location:connexion.php');
        }

    }
    if (isset($_POST['afficheSuiviCours'])) {
        
        // Créer une instance de ClasseModule
$classeModule = new ClasseModule($db);
$classeModule->setProfId($_SESSION['matricule']);

// Récupérer les cours du professeur avec les volumes horaires
$coursAvecVolumes = $classeModule->getCoursProfAvecVolumes();



echo '    <div class="container mt-5">';
echo '        <h2>Tableau des Cours du Professeur</h2>';
echo '        <table class="table table-striped">';
echo '            <thead>';
echo '                <tr>';
echo '                    <th>Module</th>';
echo '                    <th>Volume Total</th>';
echo '                    <th>Volume Déjà Fait</th>';
echo '                    <th>Volume Restant</th>';
echo '                </tr>';
echo '            </thead>';
echo '            <tbody>';

if ($coursAvecVolumes) {
foreach ($coursAvecVolumes as $cours) {
    echo '                <tr>';
    echo '                    <td>' . htmlspecialchars($cours['module_nom']) . '</td>';
    echo '                    <td>' . htmlspecialchars($cours['volume_total']) . ' heures</td>';
    echo '                    <td>' . htmlspecialchars($cours['volume_fait']) . ' heures</td>';
    echo '                    <td>' . htmlspecialchars($cours['volume_restant']) . ' heures</td>';
    echo '                </tr>';
}
} else {
echo '                <tr>';
echo '                    <td colspan="4">Aucun cours trouvé.</td>';
echo '                </tr>';
}

echo '            </tbody>';
echo '        </table>';
echo '    </div>';
    }



?>
