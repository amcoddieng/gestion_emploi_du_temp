<?php
session_start();
if ($_SESSION['status']!='admin') {
    header('location:connexion.php');
}
require_once 'class/bd.class.php';
require_once 'function.php';
$dtb = new Database();
$bds = $dtb->getConnection();
$formation = formation($bds);
$lesClasse = recup($bds);
$lesNResp = Recupprof_nonResp($bds);
$lesProfs=RecupLesprof($bds);
$lesGest=RecupLesGest($bds);
$lesEtudiantsDemandeurs=LesDemandesDinscription($bds);

// var_dump(Recupprof_nonResp($bds));
// echo $lesClasse['id'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="framwork/css/bootstrap.css">
    <link rel="stylesheet" href="css/css.css">
    <style>
                .btn-custom {
            background-color: #6b9bd1;
            color: white;
        }

    </style>
    <script type="text/javascript" src="js/js/jquery.js"></script>
   </script>

</head>
<body>


<header>
    <div class="container">
        <h1>Espace Administration</h1>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">
                <img src="img/images.png" id="imglog" alt="Logo">
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" id="LesEnseignants" href="#Senseignants">G. des Enseignants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="classes" href="#classes">G. des Classes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="Presponsables" href="#SPresponsables">Prof. Responsables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="gestionnaires" href="#Sgestionnaires">G. des Gestionnaires</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="etudiants" href="#Setudiants">G. des Étudiants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="deconnexion" href="connexion.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
    <main>
        <section id="Senseignants">
            <h2>Gestion des Enseignants</h2>
              <!-- <div id="Senseignants"> -->
                <button class="btn btn-secondary"  id="btn-Liste-enseignant">Liste des enseignants</button>
                <button class="btn btn-primary"  id="btn-ajout-enseignant">Ajouter un enseignant</button>
            
            
            
                <div class="container">
        <form method='post' id="form-enseignant" class="form-container">
            <h4 class="text-center">Ajouter un nouveau enseignant</h4>
            <div id="result" class="mt-3"></div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="specialite">Spécialité</label>
                    <input type="text" class="form-control" id="specialite" name="specialite" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="matricule">Matricule</label>
                    <input type="text" class="form-control" id="matricule" name="matricule" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="telephone">Numéro de téléphone</label>
                    <input type="number" class="form-control" id="telephone" name="telephone" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="email">Email personnel</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="dnaiss">Date de naissance</label>
                    <input type="date" class="form-control" id="dnaiss" name="dnaiss" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="lnaiss">Lieu de naissance</label>
                    <input type="text" class="form-control" id="lnaiss" name="lnaiss" required>
                </div>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Ajouter le prof</button>
        </form>
    </div>
            
            
     <div style="display:none" id="tab_Les_enseignant" class="mt-5">
        <h2 class="text-center">Liste des Enseignants</h2>
        <table  class="table table-striped table-bordered">
            <thead class="thead-custom">
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Matricule</th>
                    <th>Spécialité</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Suprimer</th>
                </tr>
            </thead>
            <tbody id='enseignantsTableBody'>
              
            </tbody>
        </table>
    </div>
</div>
        </section>
            
                <!-- liste des classes -->
        <section id="Sclasses">
            <h2>Gestion des Classes</h2>
                <button class="btn btn-secondary" id="btn_Listes_classes">Liste des classes</button>
                <button class="btn btn-primary" id="btn_ajout_classe">Ajouter une classe</button>
           
                
                <div class="container mt-5">
            <form id="form_ajout_classe" class="mt-4" style="display:none">
            <h2>Ajouter une Classe</h2>
            <div class="form-group">
                <label for="nom">Nom de la Formation</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="niveau">Niveau</label>
                <select class="form-control" id="niveau" name="niveau" required>
                    <option value="L1">L1</option>
                    <option value="L2">L2</option>
                    <option value="L3">L3</option>
                    <option value="M1">M1</option>
                    <option value="M2">M2</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="submitForm">Ajouter Classe</button>
            </form>
        </div>
            <!-- liste des classes -->

            <div class="container mt-5">
    <div id="tab_Les_classes" class="mt-4" style="display:none">
        <h2 class="text-center">Liste des Classes</h2>
        <table class="table table-striped table-bordered">
            <thead class="thead-custom">
                <tr>
                    <th>Formation</th>
                    <th>Niveau</th>
                    <th>Prof Responsable</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody id="tbody_Listes_classes">

            </tbody>
        </table>
    </div>
</div>


        </section>
        <div class="container mt-5">
        <section id="SPresponsables">
            <h2 class="text-center">Désignation des Professeurs Responsables</h2>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Recherche...">
                <div class="input-group-append">
                    <butto class="btn btn-primary" nstyle='background-color:#6b9bd1' type="button">Recherche</button>
                </div>
            </div>
            <div id="tab_classe_profResp"></div>
            <div id="modal_prof" style='display:none'>
                
            </div>

        </section>
    </div>
    </div>
    <div class="container mt-5">
        <section id="Sgestionnaires">
            <h2 class="text-center">Gestion des Gestionnaires</h2>
            <div class="mb-3">
                <button id="btn_Listes_Gest" class="btn btn-primary btn-xs">Liste des Gestionnaires</button>
                <button id="btn_ajout_ges" class="btn btn-secondary">Ajouter un gestionnaire</button>
            </div>
           
            <form id="form_ajout_ges" style='display:none'>
    <div class="container">
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" name="prenom" id="prenom">
            </div>
            <div class="col-md-3 form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" name="nom" id="nom">
            </div>
            <div class="col-md-3 form-group">
                <label for="matricule">Matricule</label>
                <input type="text" class="form-control" name="matricule" id="matricule">
            </div>
            <div class="col-md-3 form-group">
                <label for="telephone">Numéro de téléphone</label>
                <input type="number" class="form-control" name="telephone" id="telephone">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="email">Email personnel</label>
                <input type="email" class="form-control" name="email" id="email">
            </div>
            <div class="col-md-3 form-group">
                <label for="dnaiss">Date de naissance</label>
                <input type="date" class="form-control" name="dnaiss" id="dnaiss">
            </div>
            <div class="col-md-3 form-group">
                <label for="lnaiss">Lieu de naissance</label>
                <input type="text" class="form-control" name="lnaiss" id="lnaiss">
            </div>
            <div class="col-md-3 form-group">
                <!-- Empty column for alignment -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-primary" name="soumettre-gest">Ajouter gestionnaire</button>
            </div>
        </div>
    </div>
</form>


            <!-- Liste des Gestionnaires -->
             <div id="tab-gest"  style='display:none'>
            <div id="tbody_Listes_gest" class="table table-striped table-bordered">
            

    </div>
    </div>
        </section>
    </div>
        <section id="Setudiants">
    <h2>Gestion des Étudiants</h2>
    <button class="btn btn-secondary" id="btn_divListEtudiant">Liste des etudiants</button>
    <button class="btn btn-primary" id="btn_Les_Edemandeur">verifier les demandes en cours</button><br><br>
    <!-- style="display:none" -->
     <!-- Liste des etudiants -->
      <div id="divListEtudiant" style="display:none" >
        <h3>Selectionner la classe et le Niveau</h3>
        <p>
        <div class="form-group">
    <label for="choix-classe">Sélectionner une classe</label>
    <select class="form-control ChoixClasse" id="choix-classe">
        <option value="">Sélectionner une classe</option>
        <?php
        foreach ($lesClasse as $key => $value) {
            echo "<option class='ClasseChoisi' value='" . $value['id'] . "'>" . $value['niveau'] . " " . $value['formation'] . "</option>";
        }
        ?>
    </select>
</div>

            
            <!-- <select  class="ChoixNiveau">
                <option >Selectionner le niveau</option>
            </select> -->
        </p>
        <!-- tableau des etudiant qu'on cherche style="display:none" -->
        <table id="tab_Les_etudiantDemander" >

                </table><br><br>
      </div>
<!-- Liste des étudiants demandeurs -->
<table id="tab_Les_Edemandeur" class="table table-striped table-bordered" style="display:none">
    <thead>
        <tr class="bg-primary text-white">
            <th>Matricule</th>
            <th>Classe</th>
            <th>Niveau</th>
            <th>Réponse</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($lesEtudiantsDemandeurs as $key => $value) {
            echo "
            <tr>
                <td>".$value['id_etudiant']."</td>
                <td>".$value['formation']."</td>
                <td>".$value['niveau']."</td>
                <td>
                    <button class='BtnOui btn btn-success' value='".$value['id_etudiant']."' data-classe='".$value['id']."'>Oui</button>
                    <button class='BtnNon btn btn-danger' value='".$value['id_etudiant']."' data-classe='".$value['id']."'>Non</button>
                </td>
            </tr>
            ";
        }
        ?>
    </tbody>
</table>
<br><br><br>
</section>
<div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
    <img src="img/images.png" alt="Image Description" class="img-fluid" style="max-width: 80%; height: auto; opacity: 0.7;">
</div>


    </main>
</body>
    <footer>
        <p>&copy; 2024 UCAD - FST - DMI - Section Informatique</p>
    </footer>
    <script type="text/javascript" src="js/javascript.js"></script>
    <script type="text/javascript" src="js/jsAdmin.js"></script>
</html>
