<?php
class Cours {
    private $conn;
    private $tableCours = 'cours';

    public $module_id;
    public $enseignant_id;
    public $classe_id;
    public $salle_id;
    public $horaire;
    public $jour;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getheure(){
        return $this->horaire;
    }
    public function setheure($a){
        $this->horaire = $a;
    }
    public function getsalleId(){
        return $this->salle_id;
    }
    public function setsalleId($a){
        $this->salle_id = $a;
    }
    public function getmoduleId(){
        return $this->module_id;
    }
    public function setmoduleId($a){
        $this->module_id = $a;
    }
    public function getclasseId(){
        return $this->classe_id;
    }
    public function setclasseId($a){
        $this->classe_id = $a;
    }
    public function getenseignantId(){
        return $this->enseignant_id;
    }
    public function setIdenseignant($a){
        $this->enseignant_id = $a;
    }
    public function getjour(){
        return $this->jour;
    }
    public function setjour($a){
        $this->jour = $a;
    }
    public function insert() {
        $query = 'INSERT INTO ' . $this->tableCours . ' (module_id, enseignant_id, classe_id, salle_id, horaire, jour) VALUES (:module_id, :enseignant_id, :classe_id, :salle_id, :horaire, :jour)';
        $prep = $this->conn->prepare($query);
    
        $prep->bindParam(':module_id', $this->module_id);
        $prep->bindParam(':enseignant_id', $this->enseignant_id);
        $prep->bindParam(':classe_id', $this->classe_id);
        $prep->bindParam(':salle_id', $this->salle_id);
        $prep->bindParam(':horaire', $this->horaire);
        $prep->bindParam(':jour', $this->jour);
    
        if($prep->execute()) {
            return true;
        }
        return false;
    }
 
    
    // ici je vais faire des fonctions qui vont prendre des infos qui constitueront l'emploi du temps d prof
    public function CoursIns($a,$b,$c){
        $query = 'SELECT * FROM cours WHERE horaire = :heure AND jour = :jour AND enseignant_id=:en_id';
        $prep = $this->conn->prepare($query);
        
        $prep->bindParam(':heure', $a);
        $prep->bindParam(':jour', $b);
        $prep->bindParam(':en_id', $c);

        if($prep->execute()) {
            return $prep->fetchAll();
        }
        return false;
    }
    public function CoursInspOUReTUDIANT($a,$b,$c){
        $query = 'SELECT * FROM cours WHERE horaire = :heure AND jour = :jour AND classe_id=:cl_id';
        $prep = $this->conn->prepare($query);
        
        $prep->bindParam(':heure', $a);
        $prep->bindParam(':jour', $b);
        $prep->bindParam(':cl_id', $c);

        if($prep->execute()) {
            return $prep->fetch();
        }
        return false;
    }

    public function Salle(){
        $s = new salle($this->conn);
        $s->setId($this->salle_id);
        return $s->RecupNomSalle();
    }
    public function leCours(){
        $s = new Module($this->conn);
        $s->setId($this->module_id);
        return $s->RecupModule();
    }
    public function leProf(){
        $s = new enseignant($this->conn);
        $s->setId($this->enseignant_id);
        return $s->Recupprof();
    }
    public function leclasse(){
        $s = new classe($this->conn);
        $s->setId($this->classe_id);
        return $s->Recupclasse();
    }
    // emploie du temp du professeur
    public function getEmploiDuTempsProf() {
    $query = '
    SELECT 
        *
    FROM 
        cours c
    JOIN 
        classe cl ON c.classe_id = cl.id
    JOIN  
        module m ON c.module_id = m.id
    WHERE 
        c.enseignant_id = :enseignant_id
    ORDER BY 
        c.jour, c.horaire
';

$prep = $this->conn->prepare($query);
$prep->bindParam(':enseignant_id', $this->enseignant_id);

if ($prep->execute()) {
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}
return false;
}
public function getEmploiDuTempsEtudiant() {
    $query = '
    SELECT 
        c.*, 
        p.*, 
        m.*,
        salle_id
    FROM 
        ' . $this->tableCours . ' c
    JOIN 
        utilisateur p ON c.enseignant_id = p.matricule
    JOIN 
        module m ON c.module_id = m.id
    WHERE 
        c.classe_id = :classe_id
    ORDER BY 
        c.jour, c.horaire
';

$prep = $this->conn->prepare($query);
$prep->bindParam(':classe_id', $this->classe_id);

if ($prep->execute()) {
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}
return false;
}
public function mettreAJourCours() {
    // Étape 1 : Vérifier si une ligne existe déjà en excluant module_id
    $requeteVerification = 'SELECT COUNT(*) FROM ' . $this->tableCours . ' WHERE classe_id = :classe_id AND horaire = :horaire AND jour = :jour';
    $preparationVerification = $this->conn->prepare($requeteVerification);
    
    // Assurez-vous que les propriétés sont des valeurs scalaires
    $preparationVerification->bindParam(':classe_id', $this->classe_id, PDO::PARAM_STR);
    $preparationVerification->bindParam(':horaire', $this->horaire, PDO::PARAM_STR);
    $preparationVerification->bindParam(':jour', $this->jour, PDO::PARAM_STR);

    $preparationVerification->execute();
    $nombre = $preparationVerification->fetchColumn();

    if ($nombre > 0) {
        // La ligne existe déjà, mettre à jour les informations sauf heure, jour et classe_id
        $requeteMiseAJour = 'UPDATE ' . $this->tableCours . ' SET module_id = :module_id, enseignant_id = :enseignant_id WHERE classe_id = :classe_id AND horaire = :horaire AND jour = :jour';
        $preparationMiseAJour = $this->conn->prepare($requeteMiseAJour);
        
        // Assurez-vous que les propriétés sont des valeurs scalaires
        $preparationMiseAJour->bindParam(':module_id', $this->module_id, PDO::PARAM_STR);
        $preparationMiseAJour->bindParam(':enseignant_id', $this->enseignant_id, PDO::PARAM_STR);
        $preparationMiseAJour->bindParam(':classe_id', $this->classe_id, PDO::PARAM_STR);
        $preparationMiseAJour->bindParam(':horaire', $this->horaire, PDO::PARAM_STR);
        $preparationMiseAJour->bindParam(':jour', $this->jour, PDO::PARAM_STR);
        

        return $preparationMiseAJour->execute();
    } else {
        // La ligne n'existe pas, on ajoute une nouvelle ligne
        $requeteInsertion = 'INSERT INTO ' . $this->tableCours . ' (module_id, enseignant_id, classe_id, salle_id, horaire, jour) VALUES (:module_id, :enseignant_id, :classe_id, :salle_id, :horaire, :jour)';
        $preparationInsertion = $this->conn->prepare($requeteInsertion);

        $preparationInsertion->bindParam(':module_id', $this->module_id, PDO::PARAM_STR);
        $preparationInsertion->bindParam(':enseignant_id', $this->enseignant_id, PDO::PARAM_STR);
        $preparationInsertion->bindParam(':classe_id', $this->classe_id, PDO::PARAM_STR);
        $preparationInsertion->bindParam(':salle_id', $this->salle_id, PDO::PARAM_STR); // Assurez-vous que salle_id est également lié
        $preparationInsertion->bindParam(':horaire', $this->horaire, PDO::PARAM_STR);
        $preparationInsertion->bindParam(':jour', $this->jour, PDO::PARAM_STR);

        return $preparationInsertion->execute();
    }
}

public function dispoNiveau2() {
    try {
        $query = 'SELECT 1 FROM ' . $this->tableCours . ' WHERE enseignant_id = :enseignant_id AND jour = :jour AND horaire = :heure LIMIT 1';
        $prep = $this->conn->prepare($query);
        
        $prep->bindParam(':enseignant_id', $this->enseignant_id);
        $prep->bindParam(':jour', $this->jour);
        $prep->bindParam(':heure', $this->horaire);

        $prep->execute();
        
        if ($prep->rowCount() > 0) {
            return false;
        } else {
            return true;
        }
    } catch (PDOException $e) {
        error_log('erreur: ' . $e->getMessage());
        return false;
    }
}
public function attribuerSalle() {
    try {
        // Mettre à jour le cours avec la salle sélectionnée
        $query = 'UPDATE ' . $this->tableCours . ' SET salle_id = :salle_id WHERE classe_id = :classe_id AND jour = :jour AND horaire = :horaire';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':salle_id', $this->salle_id, PDO::PARAM_INT);
        $stmt->bindParam(':classe_id', $this->classe_id, PDO::PARAM_INT);
        $stmt->bindParam(':jour', $this->jour, PDO::PARAM_STR);
        $stmt->bindParam(':horaire', $this->horaire, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        // Enregistrer l'erreur
        error_log('Erreur : ' . $e->getMessage());
        return false;
    }
}

public function getEmploiDuTempsSalle() {
    // Requête SQL pour obtenir l'emploi du temps d'une salle spécifique
    $query = '
    SELECT 
        c.*, 
        cl.*, 
        m.*
    FROM 
        ' . $this->tableCours . ' c
    JOIN 
        classe cl ON c.classe_id = cl.id
    JOIN 
        module m ON c.module_id = m.id
    WHERE 
        c.salle_id = :salle_id
    ORDER BY 
        c.jour, c.horaire
';

    // Préparation et exécution de la requête
    $prep = $this->conn->prepare($query);
    $prep->bindParam(':salle_id', $this->salle_id);

    if ($prep->execute()) {
        // Retourne les résultats sous forme de tableau associatif
        return $prep->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}
public function getTousLesIdsSalles() {
    // Requête SQL pour obtenir tous les IDs distincts des salles utilisées dans les cours
    $query = '
    SELECT DISTINCT 
        salle_id 
    FROM 
        ' . $this->tableCours . '
    ORDER BY 
        salle_id
';

    $prep = $this->conn->prepare($query);

    if ($prep->execute()) {
        return $prep->fetchAll(PDO::FETCH_COLUMN, 0); // Retourner uniquement les IDs des salles
    }
    return false;
}
public function supprimerCours() {
    // Requête SQL pour supprimer un cours
    $query = 'DELETE FROM ' . $this->tableCours . ' WHERE classe_id = :classe_id AND horaire = :horaire AND jour = :jour';
    
    $prep = $this->conn->prepare($query);
    
    $prep->bindParam(':classe_id', $this->classe_id, PDO::PARAM_INT);
    $prep->bindParam(':horaire', $this->horaire, PDO::PARAM_INT);
    $prep->bindParam(':jour', $this->jour, PDO::PARAM_STR);
    
    return $prep->execute();
}
// ceder la salle
public function cederSalle() {
    
    $query = 'UPDATE ' . $this->tableCours . ' SET salle_id = NULL WHERE classe_id = :classe_id AND horaire = :horaire AND jour = :jour';
    
    $prep = $this->conn->prepare($query);
    
    $prep->bindParam(':classe_id', $this->classe_id, PDO::PARAM_INT);
    $prep->bindParam(':horaire', $this->horaire, PDO::PARAM_INT);
    $prep->bindParam(':jour', $this->jour, PDO::PARAM_STR);
    
    return $prep->execute();
}
}





?>
 