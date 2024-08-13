<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="framwork/css/bootstrap.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        header {
            background-color: #6b9bd1;
            color: white;
            padding: 15px;
            text-align: center;
        }
        #imglogConnect {
            width: 100px;
            height: auto;
        }
        #divCbtn {
            margin-bottom: 20px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <h1 class="h1connexion">
            Bienvenue dans la plateforme de la section informatiques
            <br>Departement mathematique et informatique
            <br>faculte des sciences et techniques
            </h1>
            <img src="img/images.png" id="imglogConnect" class="img-fluid my-3">
        </nav>
    </header>
    <main class="container my-5">
    <div class="container">
        <form method='post' id="form-enseignant" action="pp.php" class="form-container">
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
                <div class="form-group col-md-3">
                    <label for="lnaiss">mot de passe</label>
                    <input type="text" class="form-control" id="mdp" name="mdp" required>
                </div>
            </div>
            <input type="submit" class="btn btn-custom btn-block" name="insetude">
        </form>
    </div>
    </main>



</body>
</html>
