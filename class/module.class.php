<?php
class Module {
    private $conn;
    private $tableModule = 'module';

    public $id;
    public $nom;
    public $volume_horaireTotal;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function setId($a){
        $this->id=$a;
    }
    public function setnom($a){
        $this->nom=$a;
    }
    public function setvolume_horaireTotal($a){
        $this->volume_horaireTotal=$a;
    }
    public function getId(){
        return $this->id;
    }
    public function getnom(){
        return $this->nom;
    }
    public function getvolume_horaireTotal(){
        return $this->volume_horaireTotal;
    }
   
    public function insert() {
        $query = 'INSERT INTO ' . $this->tableModule . ' (nom_module, volume_horaire) VALUES (:nom, :volume_horaire)';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':nom', $this->nom);
        $prep->bindParam(':volume_horaire', $this->volume_horaireTotal);
       
        if($prep->execute()) {
            return true;
        }
        return false;
    }
    public function RecupModule() {
        $query = 'SELECT nom_module FROM ' . $this->tableModule . ' WHERE id = :id';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':id', $this->id);

        if($prep->execute()) {
            return $prep->fetch();
        }
        return false;
    }
    public function getVolumeHoraireById() {
        $query = 'SELECT volume_horaire FROM ' . $this->tableModule . ' WHERE id = :id';
        $prep = $this->conn->prepare($query);

        $prep->bindParam(':id', $this->id);

        if($prep->execute()) {
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result['volume_horaire'];
            }
        }
        return false;
    }
        // les different cours
        public function RecupLesmodule() {
            $query = 'SELECT * FROM ' . $this->tableModule . '';
            $prep = $this->conn->prepare($query);
            if($prep->execute()) {
                return $prep->fetchAll();
            }
            return false;
        }  
}
?>
