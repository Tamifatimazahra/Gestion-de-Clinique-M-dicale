<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// حماية الصفحة: التأكد من صلاحية الطبيب
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'medecin') {
    header('Location: ../auth/login.php');
    exit();
}

// استدعاء قاعدة البيانات والـ Controller الخاص بالطبيب
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Controller/MedecinController.php';

global $pdo;

// تمرير الـ $pdo للـ Controller لتجنب الـ Fatal Error مسبقاً
$controller = new MedecinController($pdo);
$rendez_vous_liste = $controller->afficherDashboard(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Espace Médecin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinicGreen: '#9CC943',
                        clinicGreenHover: '#88b236',
                        clinicPrimary: '#0f172a',
                        medicalIceBg: '#eef2f7',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-medicalIceBg text-slate-700 font-sans min-h-screen">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-clinicPrimary text-white flex flex-col justify-between p-6 hidden md:flex">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="h-9 w-9 rounded-xl bg-clinicGreen flex items-center justify-center text-white font-black text-xl">M</div>
                    <span class="text-xl font-bold tracking-wider">Med<span class="text-clinicGreen">Flow</span></span>
                </div>
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-clinicGreen text-white font-bold rounded-xl transition text-sm">
                        <span>Dashboard</span>
                    </a>
                    <a href="gestion_creneaux.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition text-sm">
                        <span> Gérer mes Disponibilités</span>
                    </a>
                    
                </nav>
            </div>
            
            <div class="pt-4 border-t border-slate-800 space-y-3">
                <div class="px-4 text-xs text-slate-400 font-medium truncate">
                    Dr. <?= htmlspecialchars($_SESSION['nom'] ?? 'Médecin') ?>
                </div>
                <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 text-rose-400 hover:bg-rose-500/10 rounded-xl transition text-sm font-bold">
                     <span>Se déconnecter</span>
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-slate-200 pb-5">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-clinicPrimary tracking-tight">Gestion des Consultations ⚡</h1>
                    <p class="text-sm text-slate-400 mt-1">Confirmez les demandes, puis rédigez l'ordonnance pour clore le RDV.</p>
                </div>
            </header>

            <section class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden mb-10">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-lg font-black text-clinicPrimary tracking-tight">Flux des Consultations Actives</h2>
                </div>

                <div class="divide-y divide-slate-100 text-sm">
                    <?php if (empty($rendez_vous_liste)): ?>
                        <div class="p-12 text-center text-slate-400 text-sm">✨ Aucun rendez-vous à traiter pour le moment.</div>
                    <?php else: ?>
                        <?php foreach ($rendez_vous_liste as $rdv): 
                            $isConfirme = (strtolower($rdv['statut']) === 'confirme');
                            
                            $date_formatted = date('d/m/Y', strtotime($rdv['date_creneau']));
                            $heure_formatted = date('H:i', strtotime($rdv['heure_debut']));
                        ?>
                            <div class="p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-slate-50/50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="bg-slate-100 text-slate-600 font-mono text-xs px-3 py-2 rounded-xl border border-slate-200 text-center min-w-[90px]">
                                        <span class="block font-bold text-clinicGreen uppercase">DATE</span>
                                        <span class="text-[10px] text-slate-400"><?= $date_formatted ?></span>
                                    </div>
                                    <div class="bg-slate-50 text-slate-700 font-mono text-sm px-3 py-2 rounded-xl border border-slate-200 shadow-sm font-medium">
                                        <?= $heure_formatted ?>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-clinicPrimary"><?= htmlspecialchars($rdv['patient_nom'] ?? 'Patient') ?></h4>
                                    </div>
                                </div>

                                <div>
                                    <?php if ($isConfirme): ?>
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold border rounded-md text-emerald-600 bg-emerald-50 border-emerald-100">🔵 Confirmé</span>
                                    <?php else: ?>
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold border rounded-md text-amber-600 bg-amber-50 border-amber-100">🟡 En attente</span>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center gap-2">
                                    <?php if (!$isConfirme): ?>
                                        <a href="dashboard.php?action=confirmer&id=<?= $rdv['id'] ?>" class="px-4 py-2 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-100 font-bold text-xs rounded-xl transition">
                                            Confirmer
                                        </a>
                                        <a href="dashboard.php?action=annuler&id=<?= $rdv['id'] ?>" class="px-3 py-2 bg-rose-50 hover:bg-rose-500 text-rose-700 hover:text-white border border-rose-100 font-bold text-xs rounded-xl transition">
                                            Annuler
                                        </a>
                                    <?php else: ?>
                                        <a href="rediger_ordonnance.php?id_rdv=<?= $rdv['id'] ?>" class="px-4 py-2 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-xs rounded-xl shadow-md transition">
                                            Rédiger Ordonnance
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <?php
    // معالجة الـ Actions (confirmer / annuler) ديناميكياً إذا جاءت في الرابط
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $controller->gererStatut($_GET['action'], intval($_GET['id']));
    }
    ?>
</body>
</html>