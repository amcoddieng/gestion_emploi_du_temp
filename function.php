<?php
    // function etat($bd,$id, $jour, $heure) {
    //     $en = new Disponibilite($bd);
    //     $en->setjour($jour);
    //     $en->setheure($heure);
    //     $en->setidEnseignant($id);
    //     $etat = $en->etatDispo();
    //     return $etat;
    // }
    function disponibilite($bd,$id) {
        $en = new Disponibilite($bd);
        $en->setidEnseignant($id);
        $disponibilites = $en->getDisponibilite();
        $jours = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
        
        echo '<table class="table table-striped">';
        echo '<thead>';
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
                $etat = isset($disponibilites[$jour][$heure]) ? $disponibilites[$jour][$heure] : 'N/A';
                echo '<td>' . $etat . '</td>';
            }
            
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    
    }
    // Fonction pour inclure toutes les classes
function includeAllClasses() {
    $directory = 'class/';
    $fileExtension = '.class.php';
    $dir = opendir($directory);

    while (($file = readdir($dir)) !== false) {
        if (substr($file, -strlen($fileExtension)) === $fileExtension) {
            require_once $directory . $file;
        }
    }
    closedir($dir);
}
includeAllClasses();
function recup($db){
    $per = new classe($db);
    return $per->LesClasses();

}
function etudiant($db,$idetudiant){
    $query = 'SELECT * FROM etudiant WHERE utilisateur_id = :id';
    $prep = $db->prepare($query);

    $prep->bindParam(':id', $idetudiant);

    if($prep->execute()) {
        return $prep->fetch();
    }
    return false;
}
function notification($db,$idrecever){
$n = new Notification($db);
$n->setIdRecever($idrecever);
// echo '<pre>'.var_dump($n).'</pre>';
return $n->recupmotif();
}
function formation($db){
    $f = new classe($db);
    return $f->AllClasse();
}
function nievauDispo($db,$formation){
    $f = new classe($db);
    $f->setformation($formation);
    return $f->recupNF();
}
// les profd non responsable
function Recupprof_nonResp($db){
    $f = new Enseignant($db);
    return $f->Recupprof_nonResp();
}
function RecupLesprof($db){
    $f = new Enseignant($db);
    return $f->RecupLesprof();
}

function RecupLesGest($db){
    $f = new GestionnaireSalles($db);
    return $f->RecupLesGest();
}
function LesDemandesDinscription($db){
    $f = new Etudiant($db);
    return $f->LesDemandesDinscription();
}
function lireMessage($db,$setId_message){
    $n = new Notification($db);
    $n->setId_message($setId_message);
    $n->Lu();
}
 // les etudiants qui font la demande d'inscription
 function LesDemandesDinscription1etudiant($db,$a){
    $f = new Etudiant($db);
    $f->setId($a);
    return $f->LesDemandesDinscription1etudiant();
}
function ReturnEtudiantClasses($db,$classe_id){
    $f = new Etudiant($db);
    $f->setClasseId($classe_id);
    return $f->ReturnEtudiantClasse();
}
function accepterDemande($dbs,$a,$b){
    $e = new Etudiant($dbs);
    $e->setClasseId($b);
    $e->setId($a);
    $e->accepterDemande();
}
function refuserDemande($dbs,$a,$b){
    $e = new Etudiant($dbs);
    $e->setClasseId($b);
    $e->setId($a);
    $e->refuserDemande();
}
function designeProfResp($dbs,$a,$b){
    $e = new Classe($dbs);
    $e->setId($a);
    $e->setProfResp($b);
    $e->designeProfResp();
}
function SupprimerProfResp($dbs,$a){
    $e = new Classe($dbs);
    $e->setId($a);
    $e->SupprimerProfResp();
}












?>