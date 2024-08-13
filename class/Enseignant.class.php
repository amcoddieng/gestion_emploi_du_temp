<?php
class Enseignant extends Utilisateur {
    protected $tablenseignant = 'enseignant';

    private $specialite;

    // Getter and Setter for specialite
    public function getSpecialite() {
        return $this->specialite;
    }

    public function setSpecialite($specialite) {
        $this->specialite = $specialite;
    }
    // recupere les profs
    public function RecupLesprof() {
        $query = 'SELECT * FROM ' . $this->tablenseignant . ' JOIN utilisateur 
                ON enseignant.utilisateur_id=utilisateur.matricule';
        $prep = $this->conn->prepare($query);
        if($prep->execute()) {
            return $prep->fetchAll();
        }
        return false;
    }    
    public function Recupprof() {
        $query = 'SELECT * FROM ' . $this->tablenseignant . ' JOIN utilisateur 
                                            ON enseignant.utilisateur_id=utilisateur.matricule
                                            WHERE enseignant.utilisateur_id = :id';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':id', $this->id);

        if($prep->execute()) {
            return $prep->fetch();
        }
        return false;
    }
 // Récupérer les professeurs qui ne sont pas responsables d'une classe
 public function Recupprof_nonResp() {
    $query = 'SELECT * FROM enseignant 
              JOIN utilisateur ON enseignant.utilisateur_id = utilisateur.matricule 
              WHERE enseignant.utilisateur_id NOT IN (SELECT prof_responsable_id FROM classe)';
    
    $prep = $this->conn->prepare($query);

   if ($prep->execute()) {
       return $prep->fetchAll();
    }
   return false;
}
    public function insert() {
        $this->status="enseignant";
        if ($this->insertUtilisateur()) {
            $query = 'INSERT INTO ' . $this->tablenseignant . ' (utilisateur_id, specialite) VALUES (:utilisateur_id, :specialite)';
            $prep = $this->conn->prepare($query);

            $prep->bindParam(':utilisateur_id', $this->id);
            $prep->bindParam(':specialite', $this->specialite);

            if ($prep->execute()) {
                return true;
            }
        }
        return false;
    }
    // je cherche s'il est responsable d'une classe ou pas
    public function Est_il_responsable(){
        $query = "SELECT * FROM classe WHERE prof_responsable_id=:id ";
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id',$this->id);
        $prep->execute();
        $prep->fetch();
        if ($prep->rowCount()>0) {
            return true;
        }else{
            return false;
        }
    }
    public function retourneClasse(){
        $query = "SELECT * FROM classe WHERE prof_responsable_id=:id ";
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id',$this->id);
        $prep->execute();
        $T = $prep->fetch();
        return $T['id'];

    }

    public function deleteEnseignant() {
    // Vérifiez d'abord si l'enseignant est responsable d'une classe
    if ($this->Est_il_responsable()) {
        echo "L'enseignant est responsable d'une classe et ne peut pas être supprimé.";
        return false;
    }

    // Préparer la requête de suppression
    $query = 'DELETE FROM ' . $this->tablenseignant . ' WHERE utilisateur_id = :id';
    $prep = $this->conn->prepare($query);

    // Lier le paramètre ID
    $prep->bindParam(':id', $this->id);

    // Exécuter la requête
    try {
        if ($prep->execute()) {
            // Supprimer aussi l'utilisateur associé si nécessaire
            $queryUser = 'DELETE FROM utilisateur WHERE matricule = :id';
            $prepUser = $this->conn->prepare($queryUser);
            $prepUser->bindParam(':id', $this->id);
            $prepUser->execute();

            return true;
        }
    } catch (PDOException $e) {
        // Gestion des erreurs
        error_log("Erreur de suppression : " . $e->getMessage());
    }

    return false;
}

}
?>
