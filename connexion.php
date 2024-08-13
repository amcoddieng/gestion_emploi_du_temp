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
    <script type="text/javascript" src="js/js/jquery.js"></script>
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
        <div id="divCbtn" class="text-center mb-4">
           <button id="btnConnect" class="btn btn-primary mr-2">Se connecter</button>
           <button id="btnInscr" class="btn btn-secondary">S'inscrire</button>
         <h4 style='color:red;margin:auto;'><?php echo @$_SESSION['connexion'];session_destroy();?></h4>
        </div>
       <form method="post" action="traitement.php" id="form-connect" class="border p-4 rounded bg-light">
            <h2>Se connecter en tant que <span id="enTantque"></span></h2>
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="id" id="username" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="md" id="password" class="form-control">
            </div>
            <input type="submit" name="connexion" value="Connexion" class="btn btn-primary btn-block">
            <a href="#" id="LinkIns" class="d-block mt-2">Créer un compte</a>
        </form>
        <form method="post" action="traitement.php" id="form-inscription" class="border p-4 rounded bg-light hidden">
            <h2>Inscription</h2>
            <div class="form-group">
                <label for="matricule">Matricule ou Numero carte etudiant</label>
                <input type="text" name="matricule" id="matricule" class="form-control">
            </div>
            <div class="form-group">
            <div class="form-group">
    <label for="choix-classe">Sélectionner une classe</label>
    <select class="form-control ChoixClasse" name="cl" id="choix-classe">
        <option value="">Sélectionner une classe</option>
        <?php
        require_once 'class/bd.class.php';
        require_once 'function.php';
        $dtb = new Database();
        $bds = $dtb->getConnection();
        $lesClasse = recup($bds);
        foreach ($lesClasse as $key => $value) {
            echo "<option class='ClasseChoisi' value='" . $value['id'] . "'>" . $value['niveau'] . " " . $value['formation'] . "</option>";
        }
        ?>
    </select>
</div> </div>
            <input type="submit" name="register" value="Soumettre la demande d'inscription" class="btn btn-primary btn-block">
        </form>
    </main>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 UCAD - FST - DMI - Section Informatique</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#btnConnect').click(function() {
                $('#form-inscription').addClass('hidden');
                $('#form-connect').removeClass('hidden');
            });

            $('#btnInscr').click(function() {
                $('#form-connect').addClass('hidden');
                $('#form-inscription').removeClass('hidden');
            });

            $('#LinkIns').click(function(e) {
                e.preventDefault();
                $('#form-connect').addClass('hidden');
                $('#form-inscription').removeClass('hidden');
            });

            $('#LinkIns1').click(function(e) {
                e.preventDefault();
                $('#form-inscription').addClass('hidden');
                $('#form-connect').removeClass('hidden');
            });
        });
    </script>
</body>
</html>
