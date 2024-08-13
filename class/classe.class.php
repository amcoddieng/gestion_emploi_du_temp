<?php
class Classe {
    private $conn;
    private $tableclass = 'classe';

    public $id;
    public $nom;
    public $niveau;
    public $formaton;
    public $prof_responsable_id='';

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getNom(){
        return $this->nom;
    }
    public function getId(){
        return $this->id;
    }
    public function getNiveau(){
        return $this->niveau;
    }
    public function getFormation(){
        return $this->formation;
    }
    public function getProfResp(){
        return $this->prof_responsable_id;
    }
    public function setNom($a){
         $this->nom = $a;
    }
    public function setId($a){
         $this->id = $a;
    }
    public function setNiveau($a){
         $this->niveau = $a;
    }
    public function setProfResp($a){
         $this->prof_responsable_id = $a;
    }
    public function setformation($a){
        $this->formation = $a;
   }
   public function LesClasses(){
    $query = 'SELECT * FROM classe';
    $prep = $this->conn->prepare($query);
    if($prep->execute())
    return $prep->fetchAll();
   }
   public function designeProfResp(){
    $query = 'UPDATE ' . $this->tableclass . ' SET prof_responsable_id = :prof_responsable_id WHERE id = :id';
    $prep = $this->conn->prepare($query);
    $prep->bindParam(':prof_responsable_id', $this->prof_responsable_id);
    $prep->bindParam(':id',$this->id);
    $prep->execute();
   }
   public function SupprimerProfResp(){
    $query = 'UPDATE ' . $this->tableclass . ' SET prof_responsable_id = :prof_responsable_id WHERE id = :id';
    $prep = $this->conn->prepare($query);
    $null="ras";
    $prep->bindParam(':prof_responsable_id',$null);
    $prep->bindParam(':id',$this->id);
    $prep->execute();
   }
   public function Recupclasse() {
    $query = 'SELECT formation FROM ' . $this->tableclass . ' WHERE id = :id';
    $prep = $this->conn->prepare($query);

    $prep->bindParam(':id', $this->id);

    if($prep->execute()) {
        return $prep->fetchAll();
    }
    return false;
}
public function recupNF() {
    $query = 'SELECT * FROM ' . $this->tableclass . ' WHERE formation = :formation';
    $prep = $this->conn->prepare($query);

    $prep->bindParam('formation', $this->formation);

    if($prep->execute()) {
        return $prep->fetchAll();
    }
    return false;
}
    public function AllClasse(){
        $query = 'SELECT formation FROM ' . $this->tableclass;
        $prep = $this->conn->prepare($query);
        if($prep->execute()) {
            return $prep->fetchAll();
        }
        return false;
    }
    public function insert() {
        $query = 'INSERT INTO ' . $this->tableclass . ' (formation, prof_responsable_id,niveau) VALUES (:formation, :prof_responsable_id,:niveau)';
        $prep = $this->conn->prepare($query);
        $prof_responsable_id='ras';
        $prep->bindParam(':formation',$this->formation);
        $prep->bindParam(':prof_responsable_id', $prof_responsable_id);
        $prep->bindParam(':niveau', $this->niveau);
        if($prep->execute()) {
            return true;
            echo"rrr";
        }
        return false;
           
    }
    public function deleteClasse() {
        $query = 'DELETE FROM ' . $this->tableclass . ' WHERE id = :id';
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id', $this->id);
        if($prep->execute()) {
            return true;
        }
        return false;
    }
    public function getNomAndNiveau() {
        $query = 'SELECT formation, niveau FROM ' . $this->tableclass . ' WHERE id = :id';
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id', $this->id);
        if ($prep->execute()) {
            return $prep->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
?>
