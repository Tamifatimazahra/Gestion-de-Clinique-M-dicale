<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// حماية الصفحة
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'medecin') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// جلب الـ ID ديال الموعد من الرابط
$id_rdv = isset($_GET['id_rdv']) ? intval($_GET['id_rdv']) : 0;

global $pdo;
$ordonnance = null;

if ($id_rdv > 0) {
    // كود الـ SQL كيجيب الوصفة ومعاها إسم المريض وتاريخ الموعد ف دقة وحدة
    $sql = "SELECT o.description, o.id AS id_ordonnance,
                   CONCAT(u_pat.nom, ' ', u_pat.prenom) AS nom_patient,
                   c.heure_debut AS date_consultation
            FROM ordonnances o
            JOIN rendez_vous r ON o.id_rendez_vous = r.id
            JOIN users u_pat ON r.id_patient = u_pat.id
            JOIN creneaux c ON r.id_creneau = c.id
            WHERE r.id = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_rdv]);
    $ordonnance = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Détails de l'ordonnance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0b0f19; background-image: radial-gradient(circle at 50% 0%, #1e2640 0%, #0b0f19 70%); }
        .glass-card { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .prescription-paper { background: rgba(255, 255, 255, 0.03); border: 1px dashed rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body class="text-slate-200 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl glass-card rounded-3xl p-8 shadow-2xl relative">
        
        <div class="flex items-center justify-between mb-8 border-b border-slate-800/60 pb-5">
            <div>
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Consultation Clôturée</span>
                <h1 class="text-2xl font-black text-white mt-1">Détails de l'Ordonnance </h1>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-mono">ID RDV: #RDV-<?= $id_rdv ?></p>
                <?php if ($ordonnance): ?>
                    <p class="text-xs text-slate-500 font-mono mt-1">N° Ord: #ORD-<?= $ordonnance['id_ordonnance'] ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$ordonnance): ?>
            <div class="text-center py-12">
                <div class="text-amber-400 text-3xl mb-3"></div>
                <h3 class="text-lg font-bold text-white">Aucune ordonnance trouvée</h3>
                <p class="text-xs text-slate-400 mt-1">Il وسبق أن ألغي هذا الموعد أو لم يتم تحرير وصفة طبية له بعد.</p>
                <a href="rendez_vous_passes.php" class="mt-6 inline-block px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs rounded-xl transition">
                    Retour à l'historique
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-900/40 p-4 rounded-xl border border-slate-800/60">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Patient</span>
                        <span class="text-sm font-bold text-white"><?= htmlspecialchars($ordonnance['nom_patient']) ?></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Date de consultation</span>
                        <span class="text-sm font-mono text-slate-300"><?= htmlspecialchars($ordonnance['date_consultation']) ?></span>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Médicaments & Posologie</label>
                    <div class="prescription-paper rounded-2xl p-6 text-slate-100 font-mono text-sm leading-relaxed whitespace-pre-wrap min-h-[200px]">
                        <?= htmlspecialchars($ordonnance['description']) ?>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-800/60">
                    <button onclick="window.print()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs rounded-xl border border-slate-700 transition flex items-center gap-2">
                         Imprimer l'ordonnance
                    </button>
                    
                    <a href="RendezVous.php" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.2)] transition uppercase tracking-wider">
                        Retour à l'historique
                    </a>
                </div>

            </div>
        <?php endif; ?>

    </div>

</body>
</html>