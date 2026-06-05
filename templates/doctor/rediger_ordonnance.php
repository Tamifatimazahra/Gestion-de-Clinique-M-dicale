<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Controller/OrdonnanceController.php';

global $pdo;

// نداء للـ Controller وجلب الداتا الواجدة
$controller = new OrdonnanceController($pdo);
$data = $controller->handleRedigerOrdonnance();

$id_rdv = $data['id_rdv'];
$message = $data['message'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Rédiger Ordonnance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinicGreen: '#9CC943',
                        clinicGreenHover: '#88b236',
                        clinicPrimary: '#1e293b',
                        medicalIceBg: '#eef2f7', 
                        medicalCardBg: '#e0e7ff',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-medicalIceBg text-slate-700 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white border border-slate-200/80 shadow-[0_10px_30px_rgba(30,41,59,0.05)] rounded-3xl p-8">
        <div class="mb-6">
            <span class="text-xs font-bold text-clinicGreen uppercase tracking-widest">Étape Finale</span>
            <h1 class="text-2xl font-black text-clinicPrimary mt-1">Rédiger l'ordonnance médicale 🩺</h1>
            <p class="text-xs text-slate-400 mt-1">Le statut du rendez-vous <span class="font-mono font-bold text-slate-600">#RDV-<?= $id_rdv ?></span> passera automatiquement à 'Terminé'.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-4 rounded-xl mb-6 text-center font-medium">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="id_rendez_vous" value="<?= $id_rdv ?>">

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-3">Détails du traitement (Médicaments, Posologie...)</label>
                <textarea name="description" rows="8" required placeholder="Ex: Paracétamol 500mg : 1 comprimé 3 fois par jour..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-clinicGreen focus:ring-2 focus:ring-clinicGreen/20 transition text-sm font-mono leading-relaxed shadow-inner"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="dashboard.php" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl border border-slate-200 transition">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-xs rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)] transition uppercase tracking-wider cursor-pointer">
                    Enregistrer & Clôturer
                </button>
            </div>
        </form>
    </div>

</body>
</html>