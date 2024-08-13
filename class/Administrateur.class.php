<?php
require_once 'Utilisateur.class.php';
class Administrateur extends Utilisateur {
    protected $tabl = 'administrateur';

    public function insert() {
        $this->status="admin";
        if (parent::insert()) {
            $query = 'INSERT INTO ' . $this->tabl . ' (utilisateur_id) VALUES (:utilisateur_id)';
            $prep = $this->conn->prepare($query);

            $prep->bindParam(':utilisateur_id', $this->getId());

            if ($prep->execute()) {
                return true;
            }
        }
        return false;
    }
}
?>
