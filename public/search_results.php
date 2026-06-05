<?php
require_once __DIR__ . '/../config/database.php';
global $pdo;

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if (!empty($query)) {
    try {
        $sql = "SELECT u.id, u.nom, s.nom 
        FROM users u 
        INNER JOIN medecins m ON u.id = m.id_user 
        INNER JOIN specialites s ON s.id=m.id_specialite 
        WHERE u.role = 'medecin' AND (u.nom LIKE ? OR s.nom LIKE ?)";
                
        $search_term = "%" . $query . "%";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$search_term, $search_term]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Résultats de recherche</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { clinicGreen: '#9CC943', clinicPrimary: '#0f172a', medicalIceBg: '#eef2f7' } } } }
    </script>
</head>
<body class="bg-medicalIceBg text-slate-700 p-6">
    <div class="max-w-4xl mx-auto">
        <a href="index.php" class="text-sm font-bold text-clinicGreen hover:underline">← Retour à l'accueil</a>
        
        <h1 class="text-2xl font-black text-clinicPrimary mt-4 mb-6">Résultats pour : <span class="text-clinicGreen">"<?= htmlspecialchars($query) ?>"</span></h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php if (empty($results)): ?>
                <p class="text-slate-400 text-sm col-span-full bg-white p-6 rounded-xl border border-slate-200 text-center">Aucun médecin trouvé pour cette recherche.</p>
            <?php else: ?>
                <?php foreach ($results as $med): ?>
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <img src="<?= !empty($med['image']) ? htmlspecialchars($med['image']) : 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=500&auto=format&fit=crop&q=60' ?>" class="w-12 h-12 rounded-xl object-cover">
                            <div>
                                <h3 class="font-bold text-clinicPrimary"><?= htmlspecialchars($med['nom']) ?></h3>
                                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100"><?= htmlspecialchars($med['specialite'] ?? 'Généraliste') ?></span>
                            </div>
                        </div>
                        <a href="auth/login.php" class="px-3 py-1.5 bg-clinicGreen text-white text-xs font-bold rounded-lg uppercase">Prendre RDV</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>