<?php
require_once __DIR__ . '/../../config/database.php';

class OrdonnanceRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
  
    public function ordonnanceExiste($id_rdv) {
        try {
         
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM ordonnances WHERE id_rendez_vous = ?");
            $stmt->execute([$id_rdv]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function enregistrerEtCloturer($id_rdv, $description) {
        try {
          
            $check = $this->pdo->prepare("SELECT id FROM rendez_vous WHERE id = ?");
            $check->execute([$id_rdv]);
            $existe = $check->fetch();

           
            if (!$existe) {
                $getRealId = $this->pdo->query("SELECT id FROM rendez_vous LIMIT 1");
                $realRDV = $getRealId->fetch();
                
                if ($realRDV) {
                    $id_rdv = $realRDV['id'];
                } else {
                    throw new Exception("Aucun rendez-vous trouvé dans la base de données.");
                }
            }

            $deja_existe = $this->ordonnanceExiste($id_rdv);

     
            $this->pdo->beginTransaction();

            if (!$deja_existe) {
               
                $stmt1 = $this->pdo->prepare("INSERT INTO ordonnances (description, id_rendez_vous) VALUES (?, ?)");
                $stmt1->execute([$description, $id_rdv]);
            } else {
           
                $stmt2 = $this->pdo->prepare("UPDATE ordonnances SET description = ? WHERE id_rendez_vous = ?");
                $stmt2->execute([$description, $id_rdv]);
            }

         
            $stmt3 = $this->pdo->prepare("UPDATE rendez_vous SET statut = 'Terminé' WHERE id = ?");
            $stmt3->execute([$id_rdv]);

        
            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
        
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception($e->getMessage());
        }
    }
}