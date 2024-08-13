<?php
class GestionnaireSalles extends Utilisateur {
    protected $tabl = 'gestionnaire';
    // recupere les gestionnaire
    public function RecupLesGest() {
        $query = 'SELECT * FROM ' . $this->tabl . ' JOIN utilisateur 
                ON gestionnaire.utilisateur=utilisateur.matricule';
        $prep = $this->conn->prepare($query);
        if($prep->execute()) {
            return $prep->fetchAll();
        }
        return false;
    }
    public function insert() {
        $this->status="gestionnaire";
        if (parent::insertUtilisateur()) {
            $query = 'INSERT INTO ' . $this->tabl . ' (utilisateur) VALUES (:utilisateur_id)';
            $prep = $this->conn->prepare($query);

            $prep->bindParam(':utilisateur_id', $this->id);

            if ($prep->execute()) {
                return true;
            }
        }
        return false;
    }
     // Supprime un gestionnaire
     public function supprimerGest() {
        try {
            // Suppression du gestionnaire
            $query = 'DELETE FROM ' . $this->tabl . ' WHERE utilisateur = :utilisateur_id';
            $prep = $this->conn->prepare($query);
            $prep->bindParam(':utilisateur_id', $this->id);
            $prep->execute();

            // Suppression de l'utilisateur
            $query = 'DELETE FROM utilisateur WHERE matricule = :utilisateur_id';
            $prep = $this->conn->prepare($query);
            $prep->bindParam(':utilisateur_id', $this->id);
            $prep->execute();

            return true;
        } catch (Exception $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }
}
?>
