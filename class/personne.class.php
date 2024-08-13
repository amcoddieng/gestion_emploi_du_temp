<?php
class Personne
{
    protected $nom;
    protected $prenom;
    protected $email;
    protected $password;
    protected $date_nais;
    protected $lieu_nais;
    protected $email_inst;
    protected $numero;
    protected $matricule;
    protected $status;

    // Constructeur
    // public function __construct($nom, $prenom, $email, $password, $date_nais, $lieu_nais, $email_inst, $numero, $matricule)
    // {
    //     $this->nom = $nom;
    //     $this->prenom = $prenom;
    //     $this->email = $email;
    //     $this->password = $password;
    //     $this->date_nais = $date_nais;
    //     $this->lieu_nais = $lieu_nais;
    //     $this->email_inst = $email_inst;
    //     $this->numero = $numero;
    //     $this->matricule = $matricule;
    // }
    public function __construct(){}
    // Destructeur
    public function __destruct()
    {
        // Code pour libérer les ressources si nécessaire
    }

    // Getters
    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDNais()
    {
        return $this->date_nais;
    }

    public function getLieuNais()
    {
        return $this->lieu_nais;
    }

    public function getEmailInst()
    {
        return $this->email_inst;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getMatricule()
    {
        return $this->matricule;
    }

    // Setters
    public function getstatus()
    {
        return $this->matricule;
    }

    // Setters
    public function setstatus($status)
    {
        $this->status = $status;
    }
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setDNais($date_nais)
    {
        $this->date_nais = $date_nais;
    }

    public function setLNais($lieu_nais)
    {
        $this->lieu_nais = $lieu_nais;
    }

    public function setEmailInst($email_inst)
    {
        $this->email_inst = $email_inst;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
    }

     // Méthode pour insérer les données dans la base de données
    public function inserer()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gestion_emplois_du_temps";
        try {
            // d'apres mes recherche cette requêtes préparées permettent de séparer les instructions SQL de leurs paramètres. Cela évite les injections SQL en s'assurant que les données utilisateur ne sont jamais interprétées comme des parties de la requête SQL.
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Configuration de PDO pour afficher les erreurs PDOException
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête SQL préparée pour l'insertion
            $requete_sql = "INSERT INTO utilisateur (nom, prenom, Email_personnel,Date_de_naissance, Lieu_de_naissance, Email_instutionnel, numero_telephone,status, matricule)
                    VALUES (:nom, :prenom, :email, :date_nais, :lieu_nais, :email_inst, :numero, :status,:matricule)";
            
            // Préparation de la requête
            $inserer = $conn->prepare($requete_sql);

            // Binding des paramètres
            $inserer->bindParam(':nom', $this->nom);
            $inserer->bindParam(':prenom', $this->prenom);
            $inserer->bindParam(':email', $this->email);
            $inserer->bindParam(':date_nais', $this->date_nais);
            $inserer->bindParam(':lieu_nais', $this->lieu_nais);
            $inserer->bindParam(':email_inst', $this->email_inst);
            $inserer->bindParam(':numero', $this->numero);
            $inserer->bindParam(':status', $this->status);
            $inserer->bindParam(':matricule', $this->matricule);

            // Exécution de la requête
            $inserer->execute();

            echo "Nouvelle personne ajoutée avec succès";

        } catch(PDOException $e) {
            echo "Erreur d'insertion : " . $e->getMessage();
        }

        // Fermeture de la connexion
        $conn = null;
    }
    public function elementExiste($table, $colonne, $valeur)
{
    // Paramètres de connexion à la base de données
   

    try {
        // Connexion à la base de données
       
        // Prépare et exécute la requête
        $verifier = $conn->prepare("SELECT * FROM $table WHERE $colonne = ?");
        $verifier->execute([$valeur]);

        // Vérifie si l'élément existe
        if ($verifier->rowCount() > 0) {
            return true; // L'élément existe
        } else {
            return false; // L'élément n'existe pas
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la vérification de l'existence de l'élément : " . $e->getMessage();
        return false;
    } finally {
        $conn = null; // Ferme la connexion à la base de données
    }
}

}
?>
