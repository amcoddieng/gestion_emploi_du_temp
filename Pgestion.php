<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Gestionnaire des Salles</title>
    <link rel="stylesheet" href="framwork/css/bootstrap.css">
    <link rel="stylesheet" href="css/css.css">
</head>
<style>
    .modal-content {
    display: block; /* Or use other styles to control visibility */
    /* Add other styling here */
}

.modal-content {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    /* background-color: white; */
    border-radius: 5px solid;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    z-index: 1000;
}


</style>
<body>
<header >
    <div class="container">
        <div class="d-flex justify-content-center align-items-center mb-3">
            
            <h1 class="text-center">Tableau de Bord Gestionnaire des Salles</h1>
            
        </div>
        <div>
        <a class="navbar-brand" href="#">
                <img src="img/images.png" id="imglog" alt="Logo" class="mr-3" style="max-height: 50px;">
            </a>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <div class="collapse navbar-collapse justify-content-center">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" id="ajout_salle" href="#ajout-salle">Ajouter Salle</a></li>
                        <li class="nav-item"><a class="nav-link" id="attribuer_salle" href="#attribuer-salle">Attribuer les Salles</a></li>
                        <li class="nav-item"><a class="nav-link" id="edt" href="#edt">Emploi du Temps des salles</a></li>
                        <li class="nav-item"><a class="nav-link" id="notifications" href="#notifications">Notifications</a></li>
                        <li class="nav-item"><a class="nav-link" id="logout" href="connexion.php">Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

    <main>
        <section id="Sajout_salle">
        <div class="mb-3">
            <button id="listeSalle" class="btn btn-secondary">Liste des salles</button>
            <button id="ajoutSalle" class="btn btn-primary">Ajouter une nouvelle salle</button>
        </div>
        <div class="container mt-4">
    <div id="result" class="mt-3"></div>
    <form id="formSalle" style="display:none">
    <h2 class="mb-4">Ajout de Nouvelle Salle</h2>
        <div class="form-group">
            <label for="nomClasse">Nom de la classe</label>
            <input type="text" class="form-control" id="nomClasse" required name="nomClasse" placeholder="Entrez le nom de la classe">
        </div>
        <div class="form-group">
            <label for="capacite">Capacité de la salle</label>
            <input type="number" class="form-control" id="capacite" required name="capacite" placeholder="Entrez la capacité de la salle">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
<div class="divListeSalle" style="display:none"></div>
        </section>
        <section id="Sattribuer_salle">
            
            <div class="modalSalle"></div>
            <div id="edt_des_classe"></div>
                <!-- Formulaire d'attribution des salles -->
        </section>
        <section id="Sedt">
            <h2>Emploi du Temps des salles</h2>
            <div id="edt_des_salles">
            </div>
        </section>
        <section id="notifications">
            <h2>Notifications</h2>
            <ul>
                <!-- Liste des notifications -->
            </ul>
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
    <script type="text/javascript" src="js/jsGestionnaire.js"></script>
</html>
