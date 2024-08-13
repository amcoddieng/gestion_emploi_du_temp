<?php
session_start();
require_once 'function.php';

//appel de la base donnee
$dtb = new Database();
$db = $dtb->getConnection();

if (isset($_POST['ajoutSalle'])) {
    // Récupérer les données du formulaire
    $nomClasse = $_POST['nomClasse'];
    $capacite = $_POST['capacite'];
    $s = new Salle($db);
    $s->setNom($nomClasse);
    $s->setCapacite($capacite);
    if ($s->insert()) {
        echo "Salle ajoutée avec succès !";
    }

}
if (isset($_POST['listesalle'])) {
    $salle = new Salle($db);
// Récupérer toutes les salles
$salles = $salle->getAllSalles();

echo'<div class="container mt-4">
<h2>Liste des Salles</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom de la Salle</th>
            <th>Capacité</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>';
foreach ($salles as $salle) {
    echo '<tr>' .
         '<td>' . htmlspecialchars($salle['nom_salle']) . '</td>' .
         '<td>' . htmlspecialchars($salle['capacite']) . '</td>' .
         '<td>' .
         '<button class="btn btn-danger delete-btn" data-id="' . $salle['id'] . '">Supprimer</button>' .
         '</td>' .
         '</tr>';
}
echo '</tbody>
</table>
</div>';
}
// emploi du temp des classes
if (isset($_POST['edt_classes'])) {
    $classes = new Classe($db);
    $cl = $classes->LesClasses();
    $cour = new Cours($db);
        foreach ($cl as $key => $value) {
           
                $cour->setclasseId($value['id']);
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
                        $salle = "en attente de salle";
                    }
                    $horaires[$jour][$heure] = ' ' . htmlspecialchars($cours['nom_module']) . '<br>'
                                            . '<span class="salleMoment">'. $salle.'</span>';
                }
            }

            // Début de l'affichage du HTML
            echo '<div class="container mt-5">';
            echo '<h2>Emploi du Temps '.$value['niveau'].' '.$value['formation'].'</h2>';

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
                $heureplus = $heure +1;
                echo '<tr data-heure='.$heure.'>';
                echo '<td>' . $heure . 'h - ' . $heureplus. 'h</td>';

                // Afficher les données pour chaque jour
                foreach ($jours as $jour) {
                    $contenu = isset($horaires[$jour][$heure]) ? $horaires[$jour][$heure] : 'Libre';
                    echo '<td class="jourMod" data-classe='.$value['id'].' data-jour='.$jour.'>' . $contenu . '</td>';
                }
                echo '</tr>';
            }

            echo '</tbody>';    
            echo '</table>';
            echo '</div>';
}
}
if (isset($_POST['SalleDispo'])) {
    $salle = new Salle($db);

    $classe = $_POST['classe'];
    $jour = $_POST['jour'];
    $heure = $_POST['heure'];
    $salles = $salle->getAllSallesDispo($classe, $jour, $heure);
    
    echo "
    <div class='modal-content p-3'>
        
        <div class='container p-3'>
            <span class='closemodal'>&times;</span>
            <h3>Choisir la salle</h3>
            <ul class='list-group'>";
    
    foreach ($salles as $salle) {
        echo '<li data-salle='.$salle['id'].' class="list-group-item liSalle">' . htmlspecialchars($salle['nom_salle']) . '</li>';
    }

    echo "</ul>
        </div>
    </div>";
}
// attribution de la salle
if (isset($_POST['attribuerSalle'])) {
    $cours = new Cours($db);
    $cours->setclasseId($_POST['classe']);
    $cours->setjour($_POST['jour']);
    $cours->setheure($_POST['heure']);
    $cours->setsalleId($_POST['salle']);
    $nom = $_POST['nom'];
    // echo '<pre>'.var_dump($cours).'</pre>';
    if ($cours->attribuerSalle()) {
        echo $nom;
    } else {
        echo 'Erreur lors de l\'attribution de la salle.';
    }
    

}
if (isset($_POST['edt_des_salles'])) {
    $Cours = new Cours($db);

    // Récupérer tous les IDs des salles
    $idsSalles = $Cours->getTousLesIdsSalles();

    foreach ($idsSalles as $key => $idSalle) {
        // Si getTousLesIdsSalles retourne des entiers directement
        $Cours->setsalleId($idSalle); // Utiliser directement $idSalle

        $emploiDuTemps = $Cours->getEmploiDuTempsSalle();
        $Salle = new Salle($db);
        $Salle->setId($idSalle);
        $nomSalle = $Salle->RecupNomSalle();
        // Afficher les données de l'emploi du temps pour chaque salle
        echo '<div class="container mt-5">';
        echo '<h2 class="d-flex justify-content-center border bg-primary">Emploi du Temps de la Salle ' . htmlspecialchars($nomSalle) . '</h2>'; // Utiliser directement $idSalle

        echo '<table class="table table-bordered table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Classe</th>';
        echo '<th>Module</th>';
        echo '<th>Horaire</th>';
        echo '<th>Jour</th>';
        echo '<th>supprimer</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if ($emploiDuTemps) {
            foreach ($emploiDuTemps as $cours) {
                $horaire = (int)$cours['horaire']; // Convert to integer
                $horairePlusUn = $horaire + 1; // Add 1 to the time
                echo '<tr>';
                echo '<td>' . htmlspecialchars($cours['niveau']) . ' ' . htmlspecialchars($cours['formation']) . '</td>';
                echo '<td>' . htmlspecialchars($cours['nom_module']) . '</td>';
                echo '<td>' . htmlspecialchars($horaire) . 'H - ' . htmlspecialchars($horairePlusUn) . 'H</td>';
                echo '<td>' . htmlspecialchars($cours['jour']) . '</td>';
                echo '<td><button class="btn btn-danger vidersalle" data-classe='.$cours['classe_id'].' data-horaire='.$cours['horaire'].' data-jour='.$cours['jour'].'>supprimer</button></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">Aucun emploi du temps trouvé pour cette salle.</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div><br>';
    }
}
if (isset($_POST['supsalle'])) {
    $n = new Salle($db);
    $n->setId($_POST['supsalle']);
    $n->deleteSalle();
    echo"salle supprimer";
}
if (isset($_POST['viderSalle'])) {
    $cl = $_POST['cl'];
    $j = $_POST['j'];
    $h = $_POST['h'];
    $cours = new Cours($db);
    $cours->setclasseId($cl);
    $cours->setheure($h);
    $cours->setjour($j);
    // var_dump($cours);
    if ($cours->cederSalle()) {
        echo "La est videé.";
    } else {
        echo "Une erreur s'est produite.";
    }
}
?>