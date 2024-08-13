<?php
class Etudiant extends Utilisateur {
    protected $tabl = 'etudiant';

    private $classe_id;
    private $id_cours;

    // Getter and Setter for classe_id
    public function getClasseId() {
        return $this->classe_id;
    }

    public function setClasseId($classe_id) {
        $this->classe_id = $classe_id;
    }
    public function setcoursId($a) {
        $this->id_cours = $a;
    }
    public function insert() {
        // if (parent::insert()) {
            $query = 'INSERT INTO ' . $this->tabl . ' (utilisateur_id, classe_id) VALUES (:utilisateur_id, :classe_id)';
            $prep = $this->conn->prepare($query);
            $ss=$this->getId();
            $prep->bindParam(':utilisateur_id', $ss);
            $prep->bindParam(':classe_id', $this->classe_id);

            if ($prep->execute()) {
                return true;
            }
        // }
        return false;
    }

    public function isAuthorized() {
        // Prepare the query to check if the student exists and is authorized
        $query = 'SELECT autorisation FROM demande_inscription WHERE id_etudiant = :id_etudiant';
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id_etudiant', $this->id);
    
        if ($prep->execute()) {
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            
            // Check if a result is returned and if the authorization status is true
            if ($result && $result['autorisation'] == "accepte") {
                return true;
            }
        }
        
        return false;
    }
    public function cl() {
        // Prepare the query to check if the student exists and is authorized
        $query = 'SELECT id_classe FROM demande_inscription WHERE id_etudiant = :id_etudiant';
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id_etudiant', $this->id);
    
        if ($prep->execute()) {
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            foreach ($result as $key => $value) {
                return $result['id_classe'];
            }
                
                
            
        }
        
        return false;
    }
    
    // un etudiant qui fait la demander d'inscription a une classe
    public function demandeInsClasse(){
        $query1="SELECT * FROM demande_inscription WHERE id_etudiant = :id_etudiant AND autorisation = :autorisation";
        $prep1=$this->conn->prepare($query1);
        $auto='attente';
        $prep1->bindParam(':id_etudiant',$this->id);
        $prep1->bindParam(':autorisation',$auto);
        $prep1->execute();
        $prep1->fetch();
        if($prep1->rowCount() === 0){
            $query = 'INSERT INTO demande_inscription (id_etudiant,id_classe) VALUES (:idetudiant,:idclasse)';
            $prep = $this->conn->prepare($query);
            $prep->bindParam(':idetudiant', $this->id);
            $prep->bindParam(':idclasse', $this->classe_id);

            if($prep->execute()) {
                return true;
            }
            return false;
        }else{
            return false;
        }
    }
    // les etudiants qui font la demande d'inscription
    public function LesDemandesDinscription(){
        $query = 'SELECT * FROM demande_inscription JOIN classe ON demande_inscription.id_classe = classe.id WHERE autorisation ="attente"';

        $prep = $this->conn->prepare($query);
        if($prep->execute()) {
            return $prep->fetchAll();
            }
            return false;
        }
    // les demandes d'inscription d'un etudiant
    public function LesDemandesDinscription1etudiant(){
        $query = 'SELECT * FROM demande_inscription JOIN classe ON demande_inscription.id_classe = classe.id WHERE id_etudiant=:id order by date desc';

        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id',$this->id);
        if($prep->execute()) {
            return $prep->fetchAll();
            }
            return false;
        }
        // retourne les etudiant d'une classe
        public function ReturnEtudiantClasse(){
            $query = 'SELECT * FROM ' . $this->tabl . ' JOIN utilisateur ON ' . $this->tabl . ' .utilisateur_id = utilisateur.matricule WHERE classe_id =:idclasse';
            $prep = $this->conn->prepare($query);
            $prep->bindParam(':idclasse',$this->classe_id);
            if($prep->execute()) {
            return $prep->fetchAll();
            }
            return false;
        }
    public function accepterDemande(){
        $query = 'UPDATE demande_inscription SET autorisation ="accepte" WHERE id_etudiant = :id_etudiant AND id_classe = :id_classe';
            $prep = $this->conn->prepare($query);
            $prep->bindParam(':id_etudiant',$this->id);
            $prep->bindParam(':id_classe',$this->classe_id);
            if($prep->execute()) {
            return true;
            }
            return false;
    }
    public function refuserDemande(){
        $query = 'UPDATE demande_inscription SET autorisation ="refuse" WHERE id_etudiant = :id_etudiant AND id_classe = :id_classe';
            $prep = $this->conn->prepare($query);
            $prep->bindParam(':id_etudiant',$this->id);
            $prep->bindParam(':id_classe',$this->classe_id);
            if($prep->execute()) {
            return true;
            }
            return false;
    }
    public function recup_de_etudiant(){
        $query = "SELECT classe_id FROM etudiant WHERE utilisateur_id = :id";
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id', $this->id);
        
        if ($prep->execute()) {
            $t = $prep->fetch(PDO::FETCH_ASSOC);
            if ($t) {
                return $t['classe_id'];
            } else {
                return false; // Aucun enregistrement trouvé
            }
        } else {
            return false; // Échec de l'exécution de la requête
        }
    }
    
    
}
?>
