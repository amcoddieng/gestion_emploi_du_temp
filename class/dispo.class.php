<?php
class Disponibilite {
    private $conn;
    private $tabl = 'disponibilite';

    public $enseignant_id;
    public $jour;
    public $heure;
    public $etat;   
    public function __construct($db) {
        $this->conn = $db;
    }
    public function setidEnseignant($a){
        $this->enseignant_id = $a;
    } 
    public function setjour($a){
        $this->jour = $a;
    } 
    public function setheure($a){
        $this->heure = $a;
    } 
    public function setetat($a){
        $this->etat = $a;
    } 
    public function etatDispo() {
        $query = 'SELECT * FROM ' . $this->tabl . ' WHERE enseignant_id = :id AND jour = :jour AND heure = :heure';
        $prep = $this->conn->prepare($query);
        
        // Variables intermédiaires pour passer par référence
        $enseignant_id = $this->enseignant_id;
        $journee = $this->jour;
        $heures = $this->heure;

        $prep->bindParam(':id', $enseignant_id);
        $prep->bindParam(':jour', $journee);
        $prep->bindParam(':heure', $heures);
        
        try {
            if ($prep->execute()) {
                $r = $prep->fetch(PDO::FETCH_ASSOC);
                if ($r) {
                    return $r['etat'];
                }
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de PDO
            echo "Erreur de requête : " . $e->getMessage();
            return false;
        }
    }
    public function changerEtat(){
        $query = ('UPDATE '.$this->tabl.' SET etat = :etat WHERE enseignant_id = :id AND jour = :jour AND heure = :heure');
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':etat', $this->etat);
        $prep->bindParam(':id', $this->enseignant_id);
        $prep->bindParam(':jour', $this->jour);
        $prep->bindParam(':heure', $this->heure);
        if($prep->execute()) {
            return true;
        }
        return false;
    }
    
    public function DefaultDispo(){
        $jours = array(
            'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'
        );      
        
        foreach ($jours as $jour) { 
            for ($heure = 8; $heure < 18; $heure++) {
                $query = 'INSERT INTO ' . $this->tabl . ' (enseignant_id, jour, heure, etat) VALUES (:enseignant_id, :jour, :heure, :etat)';
                $prep = $this->conn->prepare($query);
                $etat = "dispo";
                
                $prep->bindParam(':enseignant_id', $this->enseignant_id);
                $prep->bindParam(':jour', $jour);
                $prep->bindParam(':heure', $heure);
                $prep->bindParam(':etat', $etat);
    
                $prep->execute();
            }
        }
    }
    public function getDisponibilite() {
        $jours = array(
            'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'
        );
    
        $disponibilites = array();
    
        foreach ($jours as $jour) {
            for ($heure = 8; $heure < 18; $heure++) {
                $query = 'SELECT etat FROM ' . $this->tabl . ' WHERE enseignant_id = :enseignant_id AND jour = :jour AND heure = :heure';
                $prep = $this->conn->prepare($query);
    
                $prep->bindParam(':enseignant_id', $this->enseignant_id);
                $prep->bindParam(':jour', $jour);
                $prep->bindParam(':heure', $heure);
    
                $prep->execute();
                $result = $prep->fetch(PDO::FETCH_ASSOC);
    
                // Vérifiez si $result est un tableau avant d'accéder à ses éléments
                if ($result === false) {
                    // Gestion des erreurs : si la requête échoue, définissez un état par défaut
                    $disponibilites[$jour][$heure] = 'N/A';
                } else {
                    // Assurez-vous que 'etat' existe dans le résultat
                    $disponibilites[$jour][$heure] = isset($result['etat']) ? $result['etat'] : 'N/A';
                }
            }
        }
    
        return $disponibilites;
    }
    

    
    public function updateDisponibilite($jour, $heure, $etat) {
        $query = 'UPDATE ' . $this->tabl . ' SET etat = :etat WHERE enseignant_id = :enseignant_id AND jour = :jour AND heure = :heure';
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':etat', $etat);
        $prep->bindParam(':enseignant_id', $this->enseignant_id);
        $prep->bindParam(':jour', $jour);
        $prep->bindParam(':heure', $heure);
        $prep->execute();
    }
    
    public function insert() {
        $query = 'INSERT INTO ' . $this->tabl . ' (enseignant_id, jour, heure) VALUES (:enseignant_id, :jour, :heure)';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':enseignant_id', $this->enseignant_id);
        $prep->bindParam(':jour', $this->jour);
        $prep->bindParam(':heure', $this->heure);

        if($prep->execute()) {
            return true;
        }
        return false;
    }
    public function estDisponible() {
        //Vérifier la disponibilité
        $query = 'SELECT etat FROM ' . $this->tabl . ' WHERE enseignant_id = :enseignant_id AND jour = :jour AND heure = :heure';
        $prep = $this->conn->prepare($query);
    
        $prep->bindParam(':enseignant_id', $this->enseignant_id);
        $prep->bindParam(':jour', $this->jour);
        $prep->bindParam(':heure', $this->heure);
    
        $prep->execute();
        $result = $prep->fetch(PDO::FETCH_ASSOC);
    
        if ($result['etat'] === 'dispo') {
            return 1;
        } else {
            return 0;
        }
    }
    

}
?>
