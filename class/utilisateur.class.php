 <?php
class Utilisateur {
    protected $conn;
    protected $table = 'utilisateur';

    protected $id;
    protected $prenom;
    protected $nom;
    protected $email;
    protected $telephone;
    protected $date_naiss;
    protected $lieu_naiss;
    protected $status;
    protected $mdp = "default";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters and Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function getDateNaiss() {
        return $this->date_naiss;
    }

    public function setDateNaiss($date_naiss) {
        $this->date_naiss = $date_naiss;
    }

    public function getLieuNaiss() {
        return $this->lieu_naiss;
    }

    public function setLieuNaiss($lieu_naiss) {
        $this->lieu_naiss = $lieu_naiss;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    public function insertUtilisateur() {
        $query = 'INSERT INTO ' . $this->table . ' (matricule, prenom, nom, email, telephone, date_naiss, lieu_naiss, status, mdp) 
                  VALUES (:matricule, :prenom, :nom, :email, :telephone, :date_naiss, :lieu_naiss, :status, :mdp)';
        $prep = $this->conn->prepare($query);

        // Binding des paramètres
        $prep->bindParam(':matricule', $this->id);
        $prep->bindParam(':prenom', $this->prenom);
        $prep->bindParam(':nom', $this->nom);
        $prep->bindParam(':email', $this->email);
        $prep->bindParam(':telephone', $this->telephone);
        $prep->bindParam(':date_naiss', $this->date_naiss);
        $prep->bindParam(':lieu_naiss', $this->lieu_naiss);
        $prep->bindParam(':status', $this->status);
        $prep->bindParam(':mdp', $this->mdp);

        // Exécution de la requête
        try {
            if ($prep->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de PDO
            echo "Erreur d'insertion : " . $e->getMessage();
            return false;
        }
    }
// verifie utilisateur
    public function connection($id){
        $query = 'SELECT * FROM ' . $this->table . ' WHERE matricule = :matricule LIMIT 1';
        $prep = $this->conn->prepare($query);

        
        $prep->bindParam(':matricule', $id);

        try {
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $this->id = $row['matricule'];
                $this->prenom = $row['prenom'];
                $this->nom = $row['nom'];
                $this->email = $row['email'];
                $this->telephone = $row['telephone'];
                $this->date_naiss = $row['date_naiss'];
                $this->lieu_naiss = $row['lieu_naiss'];
                $this->status = $row['status'];
                $this->mdp = $row['mdp'];
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Handle any errors
            echo "Erreur de récupération : " . $e->getMessage();
            return false;
        }
    }
    public function updateUtilisateur() {
        $query = 'UPDATE ' . $this->table . ' SET prenom = :prenom, nom = :nom, email = :email, telephone = :telephone, 
                  date_naiss = :date_naiss, lieu_naiss = :lieu_naiss, status = :status WHERE matricule = :matricule';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':matricule', $this->id);
        $prep->bindParam(':prenom', $this->prenom);
        $prep->bindParam(':nom', $this->nom);
        $prep->bindParam(':email', $this->email);
        $prep->bindParam(':telephone', $this->telephone);
        $prep->bindParam(':date_naiss', $this->date_naiss);
        $prep->bindParam(':lieu_naiss', $this->lieu_naiss);
        $prep->bindParam(':status', $this->status);

        try {
            return $prep->execute();
        } catch (PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }



    public function getUtilisateur() {
        // Assurez-vous que l'ID est défini avant d'exécuter la requête
        if (empty($this->id)) {
            throw new Exception("ID utilisateur non défini.");
        }

        $query = 'SELECT matricule, prenom, nom, email, telephone, date_naiss, lieu_naiss, status, mdp 
                  FROM ' . $this->table . ' WHERE matricule = :matricule';
        $prep = $this->conn->prepare($query);

        // Binding du paramètre
        $prep->bindParam(':matricule', $this->id);

        // Exécution de la requête
        try {
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);

            // Vérification si l'utilisateur a été trouvé
            if ($result) {
                // Remplir les propriétés de l'objet avec les données récupérées
                $this->prenom = $result['prenom'];
                $this->nom = $result['nom'];
                $this->email = $result['email'];
                $this->telephone = $result['telephone'];
                $this->date_naiss = $result['date_naiss'];
                $this->lieu_naiss = $result['lieu_naiss'];
                $this->status = $result['status'];
                $this->mdp = $result['mdp'];

                return $result; // Retourner les données pour utilisation externe si besoin
            } else {
                return false; // Aucun utilisateur trouvé
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de PDO
            echo "Erreur de récupération : " . $e->getMessage();
            return false;
        }
    }
    public function modClasse($idcl,$id){
        $query = 'UPDATE etudiant SET classe_id =:id_cl WHERE utilisateur_id = :id';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':id_cl', $idcl);
        $prep->bindParam(':id', $id);
        $prep->execute();
    }

    // public function updatePassword($nouveauMotDePasse) {
    //     // Hacher le nouveau mot de passe
    //     $motDePasseHache = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
    
    //     $requete = 'UPDATE ' . $this->table . ' SET mdp = :mdp WHERE matricule = :matricule';
    //     $prep = $this->conn->prepare($requete);
    
    //     // Lier les paramètres
    //     $prep->bindParam(':matricule', $this->id);
    //     $prep->bindParam(':mdp', $motDePasseHache);
    
    //     try {
    //         // Exécuter la requête
    //         return $prep->execute();
    //     } catch (PDOException $e) {
    //         // Gérer les erreurs
    //         error_log("Erreur de mise à jour du mot de passe : " . $e->getMessage());
    //         return false;
    //     }
    // }
    
}

?>
