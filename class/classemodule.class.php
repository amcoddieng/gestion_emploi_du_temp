<?php
class ClasseModule {
    private $conn;
    private $table = 'classe_module';
    private $id;
    public $classe_id;
    private $prof_id;
    private $module_id;
    private $volumeHoraire;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getter for prof_id
    public function getProfId() {
        return $this->prof_id;
    }
    public function setId($a) {
        $this->id = $a;
    }
    // Setter for prof_id
    public function setProfId($prof_id) {
        $this->prof_id = $prof_id;
    }
    public function setModule($a) {
        $this->module_id = $a;
    }
    public function setClasseId($classe_id) {
        $this->classe_id = $classe_id;
    }
    // Getter for volumeHoraire
    public function getVolumeHoraire() {
        return $this->volumeHoraire;
    }

    // Setter for volumeHoraire
    public function setVolumeHoraire($volumeHoraire) {
        $this->volumeHoraire = $volumeHoraire;
    }

    public function insert() {
        $query = 'INSERT INTO ' . $this->table . ' (id_classe,id_module,id_prof) VALUES (:classe_id, :module_id, :prof_id)';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':classe_id', $this->classe_id);
        $prep->bindParam(':module_id', $this->module_id);
        $prep->bindParam(':prof_id', $this->prof_id);
        

        if ($prep->execute()) {
            return true;
        }
        return false;
    }

    // Methode volumeHoraire dans la base de donnees
    private function getVolumeHoraireFromDB() {
        $query = 'SELECT volume_horaire_faite FROM ' . $this->table . ' WHERE id_classe = :classe_id AND id_module = :module_id AND id_prof = :prof_id';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':classe_id', $this->classe_id);
        $prep->bindParam(':module_id', $this->module_id);
        $prep->bindParam(':prof_id', $this->prof_id);

        $prep->execute();
        $result = $prep->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['volume_horaire_faite'];
        }
        return null;
    }

    // Methode pour incrementer volumeHoraire
    public function incrementVolumeHoraire() {
        $currentVolumeHoraire = $this->getVolumeHoraireFromDB();
        
        if ($currentVolumeHoraire !== null) {
            $this->volumeHoraire = $currentVolumeHoraire + 1;

            $query = 'UPDATE ' . $this->table . ' SET volume_horaire_faite = :volume_horaire WHERE id_classe = :classe_id AND id_module = :module_id AND id_prof = :prof_id';
            $prep = $this->conn->prepare($query);

            $prep->bindParam(':volume_horaire', $this->volumeHoraire);
            $prep->bindParam(':classe_id', $this->classe_id);
            $prep->bindParam(':module_id', $this->module_id);
            $prep->bindParam(':prof_id', $this->prof_id);

            if ($prep->execute()) {
                return true;
                
            }
        }
        return false;
    }
        // Methode pour retourner les differentes classes et leurs modules qu'un prof enseigne avec leur volume horaire faite
        public function getClassesAndModulesByProf() {
            $query = '
                SELECT c.formation as classe_nom, m.nom as module_nom, cm.id_module,cm.volume_horaire_faite
                FROM ' . $this->table . ' cm
                JOIN classe c ON cm.id_classe = c.id
                JOIN module m ON cm.id_module = m.id
                WHERE cm.id_prof = :prof_id
            ';
            $prep = $this->conn->prepare($query);
    
            $prep->bindParam(':prof_id', $this->prof_id);
    
            $prep->execute();
            $result = $prep->fetchAll(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            }
            return null;
        }

        public function getDetailclass() {
            $query = '
                SELECT *
                FROM ' . $this->table . ' cm
                JOIN classe c ON cm.id_classe = c.id
                JOIN module m ON cm.id_module = m.id
                JOIN utilisateur u ON cm.id_prof = u.matricule
                WHERE cm.id_classe = :id

            ';
            $prep = $this->conn->prepare($query);
    
            $prep->bindParam(':id', $this->classe_id);
    
            $prep->execute();
            $result = $prep->fetchAll(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            }
            return null;
        }
        public function exists() {
            $query = 'SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE id_classe = :classe_id AND id_module = :module_id';
            $prep = $this->conn->prepare($query);
        
            $prep->bindParam(':classe_id', $this->classe_id);
            $prep->bindParam(':module_id', $this->module_id);
        
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            
            // Retourne true si le count est supérieur à 0, sinon false
            return $result['count'] > 0;
        }
        
        public function getCoursProfAvecVolumes() {
            $query = '
                SELECT 
                    m.nom_module AS module_nom,
                    SUM(cm.volume_horaire_faite) AS volume_fait,
                    m.volume_horaire AS volume_total,
                    (m.volume_horaire - SUM(cm.volume_horaire_faite)) AS volume_restant
                FROM ' . $this->table . ' cm
                JOIN module m ON cm.id_module = m.id
                WHERE cm.id_prof = :prof_id
                GROUP BY m.nom_module, m.volume_horaire
            ';
            $prep = $this->conn->prepare($query);
            $prep->bindParam(':prof_id', $this->prof_id);
            if ($prep->execute()) {
                return $prep->fetchAll(PDO::FETCH_ASSOC);
            }
            return null;
        }
        public function supprimer() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id_clm = :id';
            $prep = $this->conn->prepare($query);
    
            $prep->bindParam(':id', $this->id, PDO::PARAM_INT);
    
            if ($prep->execute()) {
                return true;
            }
            return false;
        }
        
}

?>
