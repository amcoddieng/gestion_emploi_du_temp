<?php
session_start();
if ($_SESSION['status']!='etudiant') {
    header('location:connexion.php');
}
require_once 'class/bd.class.php';
require_once 'function.php';
$dtb = new Database();
$bds = $dtb->getConnection();
$enseignant = new Utilisateur($bds);
$enseignant->connection($_SESSION['matricule']);
$user = new Etudiant($bds);
$user->setId($_SESSION['matricule']);
$profil = $user->getUtilisateur();
// $cl = $user->ReturnEtudiantClasse();
// var_dump($cl);
// echo $_SESSION['classe_id'];
$cl = new Classe($bds);
$cl->setId($_SESSION['classe_id']);
$t = $cl->getNomAndNiveau();
// var_dump($t);
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Étudiant</title>
    <link rel="stylesheet" href="framwork/css/bootstrap.css">
    <link rel="stylesheet" href="css/css.css">
</head>

<body>
    <header> 
    <div class="container">
        <h1>Tableau de Bord Étudiant</h1>
            <a class="navbar-brand" href="#">
                <img src="img/images.png" id="imglog" alt="Logo">
            </a>
        <nav class="navbar navbar-expand-lg navbar-dark border" >

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#Sinscriptions" id="inscriptions">Changer de Classes</a></li>
                <li class="nav-item"><a class="nav-link" href="#Sedt" id="edt">Emploi du Temps</a></li>
                <li class="nav-item"><a class="nav-link" href="#Snotifications" id="notifications">Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="#Sprofil" id="profil">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="#Sdemande" id="demande">demande en cours</a></li>
                <li class="nav-item"><a class="nav-link" href="#" id="logout">Déconnexion</a></li>
                <li class="nav-item" ><h4 style="margin-top:0px;margin-left:10px"><?php echo @$profil['prenom'].' '.@$profil['nom'];?></h4></li>
                <li class="nav-item" ><h4 style="margin-top:0px;margin-left:10px"><?php echo @$t['formation'].' '.@$t['niveau'];?></h4></li>
            </ul>
        </div>
        </nav>
    </div>
    </header>
    <main>
        <section id="Sinscriptions">
            <!-- Formulaire pour faire une demande dinscription a un cours -->
            <h2>Inscription a une Classes</h2>
            <form method="post" id="Form_demamde_ins" class="container mt-4">
    <div class="form-group">
        <label for="carteEtudiant">Carte Etudiant</label>
        <input type="text" class="form-control" id="carteEtudiant" name="carteEtudiant" placeholder="Numero carte etudiant...">
    </div>
    
    <div class="form-group">
        <label for="classe">Classe</label>
        <select class="form-control" id="classe" name="classe">
        </select>
    </div>
    
    <div class="form-group">
        <label for="password">Mot de Passe</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Entrer votre mot de passe">
    </div>
    
    <button type="submit" class="btn btn-dark" id="btnInscription" name="EtudiantDemandeInsClasse">Envoyer une demande</button>
</form>
        </section>
        <section class="container" id="Sedt">
            <!-- Partie emploie du temps -->
            <div id="calendar">
    
            </div>
        </section>
        <section id="Snotifications">
        <div class="container" id="notifs"></div>
        </section>

        <section class="container" id="Sdemande">
        <h3>Aucune demande en cours</h3>
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
    <script type="text/javascript" src="js/js/jquery.js"></script>
    <script type="text/javascript" src="js/javascript.js"></script>
    <script type="text/javascript" src="js/jsEtudiant.js"></script>
</html>
