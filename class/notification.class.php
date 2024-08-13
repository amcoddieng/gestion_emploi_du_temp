<?php
class Notification {
    private $conn;
    private $tableNotif = 'notification';
    private $id_recever;
    private $motif;
    private $id_message;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters et Setters simples
    public function getIdRecever() {
        return $this->id_recever;
    }

    public function setIdRecever($id_recever) {
        $this->id_recever = $id_recever;
    }
    public function setId_message($id_message) {
        $this->id_message = $id_message;
    }

    public function getMotif() {
        return $this->motif;
    }

    public function setMotif($motif) {
        $this->motif = $motif;
    }

    public function getLu() {
        return $this->lu;
    }


    public function recupmotif() {
        $query = 'SELECT * FROM ' . $this->tableNotif . ' WHERE id_receveur = :id order by date DESC';
        $prep = $this->conn->prepare($query);
        $prep->bindParam(':id', $this->id_recever, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function Lu() {
        $query = 'UPDATE ' . $this->tableNotif . ' SET lu = :lu WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $lu='oui';
        $stmt->bindParam(':lu', $lu);
        $stmt->bindParam(':id', $this->id_message, PDO::PARAM_INT);
        $stmt->execute();
    }
}











    // Getters et Setters avec interaction de base de données
//     public function getIdRecever($id) {
//         $query = 'SELECT id_recever FROM ' . $this->tableNotif . ' WHERE id = :id';
//         $stmt = $this->conn->prepare($query);
//         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
//         $stmt->execute();
//         $result = $stmt->fetch(PDO::FETCH_ASSOC);
//         $this->id_recever = $result['id_recever'];
//         return $this->id_recever;
//     }

//     public function setIdRecever($id, $id_recever) {
//         $query = 'UPDATE ' . $this->tableNotif . ' SET id_recever = :id_recever WHERE id = :id';
//         $stmt = $this->conn->prepare($query);
//         $stmt->bindParam(':id_recever', $id_recever, PDO::PARAM_INT);
//         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
//         $stmt->execute();
//         $this->id_recever = $id_recever;
//     }

//     public function getMotif($id) {
//         $query = 'SELECT motif FROM ' . $this->tableNotif . ' WHERE id = :id';
//         $stmt = $this->conn->prepare($query);
//         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
//         $stmt->execute();
//         $result = $stmt->fetch(PDO::FETCH_ASSOC);
//         $this->motif = $result['motif'];
//         return $this->motif;
//     }

//     public function setMotif($id, $motif) {
//         $query = 'UPDATE ' . $this->tableNotif . ' SET motif = :motif WHERE id = :id';
//         $stmt = $this->conn->prepare($query);
//         $stmt->bindParam(':motif', $motif, PDO::PARAM_STR);
//         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
//         $stmt->execute();
//         $this->motif = $motif;
//     }

//     public function getLu($id) {
//         $query = 'SELECT lu FROM ' . $this->tableNotif . ' WHERE id = :id';
//         $stmt = $this->conn->prepare($query);
//         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
//         $stmt->execute();
//         $result = $stmt->fetch(PDO::FETCH_ASSOC);
//         $this->lu = $result['lu'];
//         return $this->lu;
//     }

// }
?>
