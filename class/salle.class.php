<?php
class Salle {
    private $conn;
    private $tableSalle = 'salle';

    public $id;
    public $nom;
    public $capacite;

    public function __construct($db) {
        $this->conn = $db;
    }
        public function getNom() {
            return $this->nom;
        }
    
        public function setNom($nom) {
            $this->nom = $nom;
        }
    
        public function getCapacite() {
            return $this->capacite;
        }
    
        public function setCapacite($capacite) {
            $this->capacite = $capacite;
        }
    public function setId($a){
        $this->id=$a;
    }
    public function RecupNomSalle() {
        $query = 'SELECT nom_salle FROM ' . $this->tableSalle . ' WHERE id = :id';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':id', $this->id);

        if($prep->execute()) {
            $salle = $prep->fetchAll();
            foreach ($salle as $key => $s) {
            return $s['nom_salle'];
        }
        }else{
        return false;
    }
    }

    public function insert() {
        $query = 'INSERT INTO ' . $this->tableSalle . ' (nom_salle, capacite) VALUES (:nom_salle, :capacite)';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':nom_salle', $this->nom);
        $prep->bindParam(':capacite', $this->capacite);

        if($prep->execute()) {
            return true;
        }
        return false;
    }
    public function getAllSalles() {
        $query = 'SELECT * FROM ' . $this->tableSalle;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    public function getAllSallesDispo($classe, $jour, $horaire) {
        // Prepare the SQL query with parameterized placeholders
        $query = 'SELECT * FROM ' . $this->tableSalle . ' WHERE id IS NOT NULL AND id NOT IN (
                    SELECT salle_id FROM cours
                    WHERE salle_id IS NOT NULL
                    AND classe_id = :classe
                    AND jour = :jour
                    AND horaire = :horaire
                  )';
        
        // Prepare and execute the query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':classe', $classe, PDO::PARAM_INT);
        $stmt->bindParam(':jour', $jour, PDO::PARAM_STR);
        $stmt->bindParam(':horaire', $horaire, PDO::PARAM_STR);
        $stmt->execute();
        
        // Fetch all results as an associative array
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    public function deleteSalle() {
        $query = 'DELETE FROM ' . $this->tableSalle . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    

    
}
?>
