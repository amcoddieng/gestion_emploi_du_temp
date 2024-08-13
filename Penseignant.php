<?php
session_start();
if ($_SESSION['status']!='enseignant') {
    header('location:connexion.php');
}
require_once 'class/bd.class.php';
require_once 'function.php';
// echo ($_SESSION['matricule']);
$dtb = new Database();
$bds = $dtb->getConnection();
$cours = new cours($bds);
$cours->setIdenseignant($_SESSION['matricule']);
$enseignant = new Utilisateur($bds);
$enseignant->connection($_SESSION['matricule']);
$user = new Utilisateur($bds);
$user->setId($_SESSION['matricule']);
$profil = $user->getUtilisateur();
$cl = new Classe($bds);
$cl->setId($_SESSION['classe_id']);
$t = $cl->getNomAndNiveau();

$cours = new Cours($bds);    
$cours->setIdenseignant($_SESSION['matricule']);
$emploiDuTemps = $cours->getEmploiDuTempsProf();
// var_dump($_SESSION['classe_id']);

$cours = new Cours($bds);    
$cours->setclasseId(5);
$emploiDuTemps = $cours->getEmploiDuTempsEtudiant();
// var_dump($emploiDuTemps);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="js/js/jquery.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Enseignant</title>
    <link rel="stylesheet" href="framwork/css/bootstrap.css">
    <link rel="stylesheet" href="css/css.css">
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
  
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
<style>
    
</style>
    
</head>
<body>
    <header>
        <h1>Espace Enseignant</h1>
        <nav>
            <img src="img/images.png" id="imglog"><br>
            <ul>
                <li><a href="#disponibilites" id="disponibilites">Disponibilités</a></li>
                <li><a href="#Sedt" id="edt">Emploi du Temps</a></li>
                <li><a href="#cours" id="cOurs">Suivi des Cours</a></li>
                <?php
                    if ($_SESSION['Prof_resp']===true) {
                        echo '<li><a href="#SGclasse" id="Gclasse">G. classe</a></li>';
                    }
                ?>
                <li><a href="#notification" id="notification">notification</a></li>
                <li><a href="#profil" id="profil">Profil</a></li>
                <li><a href="#logout" id="logout">Déconnexion</a></li>
                <li class="nav-item" ><h3 style="margin-top:-15px;margin-left:50px"><?php echo $profil['prenom'].' '.$profil['nom'];?></h3></li>
               <?php if ($_SESSION['Prof_resp']===true) {
                echo '<li class="nav-item" ><h4 style="margin-top:-10px;margin-left:10px">'. $t['formation'].' '.$t['niveau'].'</h4></li>';
                } ?>
            </ul>
        </nav>
    </header>
    <main>
<section id="Sdisponibilites">
    <h2>Gestion des Disponibilités </h2>
    <div id="tbody_disponibilite">
        
    </div>  


            </section>
<style>
    .modalcours{
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    border-radius: 5px solid;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    z-index: 1000;
    padding: 30;
    display: none;
    }
</style>
            <section id="Sedt">
            <div class="modalcours"></div>
            <div id="calendar">

            </div>
        </section>
        <section id="Snotification">

        </section>
        <!-- suivi des cours -->
        <section id="Suivicours">
            
        </section>
        <section id="SGclasse" style="">
        <div class="mb-3">
                <button id="ajout_cours" class="btn btn-primary ">ajouter un cours</button>
                <button id="btn_liste_cours" class="btn btn-primary">liste des cours</button>
                <button id="div_emp_class_gerer" class="btn btn-primary">voir l'emploi du temp</button>
                <button id="btn_emploi_prof" class="btn btn-primary">voir l'emploi du temp d'un prof</button>
              
        </div>
                    <div class="edtClasseGerer">
                        <!-- affichage emploi du temp -->
                    </div>
    <div class="listeDesCours" style='display:none'>
        ihvn
        <!-- liste des cours et leur volume horaire faite -->
    </div>
    <div class="container mt-5 form_ajout_cour">
                    
        <form id="form_ajout_classe" class="mt-4" style="display:none">
            <h2>Ajouter une Cours</h2>
            <div class="form-group">
                <label for="nom">Nom du cours</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="volume">Volume horaire</label>
                <input type="number" class="form-control" id="volume" name="volume" required>
            </div>
            <button type="submit" class="btn btn-primary" id="submitForm">Ajouter cours</button>
        </form>
     </div>
                    
                
     
     
     
     
     
    <div id="modal_modifie_cours" style='display:none'>
                <!-- modal pour modifier l'emploi du temp -->
    </div>
        </section>
        <section id="Sprofil">
        <div class="custom-container mt-5">
        <div class="row">
            <!-- Section pour afficher le profil -->
            <div class="col-md-12 mb-4" id="profile-view">
                <h3 class="mb-4">Informations du Profil</h3>
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Prénom:</strong> <?php echo htmlspecialchars($enseignant->getPrenom()); ?></p>
                        <p><strong>Nom:</strong> <?php echo htmlspecialchars($enseignant->getNom()); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($enseignant->getEmail()); ?></p>
                        <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($enseignant->getTelephone()); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Date de Naissance:</strong> <?php echo htmlspecialchars($enseignant->getDateNaiss()); ?></p>
                        <p><strong>Lieu de Naissance:</strong> <?php echo htmlspecialchars($enseignant->getLieuNaiss()); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($enseignant->getStatus()); ?></p>
                    </div>
                </div>
                <button class="btn btn-custom-1" onclick="showEditForm()">Modifier le Profil</button>
            </div>

            <!-- Section pour modifier le profil -->
            <div class="col-md-12" id="profile-edit" style="display: none;">
                <h3 class="mb-4">Modifier le Profil</h3>
                <form id="profile-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prenom">Prénom:</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($enseignant->getPrenom()); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="nom">Nom:</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($enseignant->getNom()); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($enseignant->getEmail()); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telephone">Téléphone:</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($enseignant->getTelephone()); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="date_naiss">Date de Naissance:</label>
                                <input type="date" class="form-control" id="date_naiss" name="date_naiss" value="<?php echo htmlspecialchars($enseignant->getDateNaiss()); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="lieu_naiss">Lieu de Naissance:</label>
                                <input type="text" class="form-control" id="lieu_naiss" name="lieu_naiss" value="<?php echo htmlspecialchars($enseignant->getLieuNaiss()); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                           
                            <div class="form-group">
                                <button type="submit" class="btn btn-custom-1">Enregistrer</button>
                                <button type="button" class="btn btn-secondary" onclick="showProfileView()">Annuler</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</section> 
<div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
    <img src="img/images.png" alt="Image Description" class="img-fluid" style="max-width: 80%; height: auto; opacity: 0.7;">
</div>
    </main>
    <footer>
        <p>&copy; 2024 UCAD - FST - DMI - Section Informatique</p>
    </footer>
</body>
    <script type="text/javascript" src="js/javascript.js"></script>
    <script type="text/javascript" src="js/jsenseignant.js"></script>
</html>
