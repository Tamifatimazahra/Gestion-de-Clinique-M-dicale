<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Controller/PatientController.php';
global $pdo;

$controller = new PatientController($pdo);
$data = $controller->handleDashboard();

$patient_nom = $data['patient_nom'];
$rendez_vous = $data['rendez_vous'];
$count_rdv = $data['count_rdv'];
$count_ordonnances = $data['count_ordonnances'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Dashboard Patient</title>
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
<body class="bg-medicalIceBg text-slate-700 font-sans min-h-screen flex flex-col md:flex-row">

    <aside class="w-full md:w-64 bg-clinicPrimary text-white flex flex-col justify-between p-6">
        <div>
            <div class="flex items-center gap-3 mb-10">
                <div class="h-9 w-9 rounded-xl bg-clinicGreen flex items-center justify-center text-white font-black text-xl">M</div>
                <span class="text-xl font-bold tracking-wider">Med<span class="text-clinicGreen">Flow</span></span>
            </div>

            <nav class="space-y-2">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-clinicGreen text-white font-bold rounded-xl transition text-sm">
                     <span>Tableau de bord</span>
                </a>
                <a href="prendre_rdv.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition text-sm">
                     <span>Prendre RDV</span>
                </a>
                <a href="ordonnance.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition text-sm">
                     <span>Mes Ordonnances</span>
                </a>
            </nav>
        </div>

        <div class="mt-10 md:mt-0 pt-4 border-t border-slate-800">
            <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 text-rose-400 hover:bg-rose-500/10 rounded-xl transition text-sm font-bold">
                 <span>Se déconnecter</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 p-6 md:p-10 overflow-y-auto">
        
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-5 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-clinicPrimary tracking-tight">Bonjour, <?= htmlspecialchars($patient_nom) ?> </h1>
                <p class="text-xs text-slate-400 mt-1">Bienvenue dans votre space santé personnel. Prenez soin de vous.</p>
            </div>
            <div>
                <a href="prendre_rdv.php" class="inline-flex items-center gap-2 px-5 py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-sm rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)] transition">
                     Prendre un Rendez-vous
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 border border-slate-200 shadow-sm rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rendez-vous prévus</span>
                    <h3 class="text-3xl font-black text-clinicPrimary mt-1"><?= $count_rdv ?></h3>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl font-bold"></div>
            </div>

            <div class="bg-white p-6 border border-slate-200 shadow-sm rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mes Ordonnances</span>
                    <h3 class="text-3xl font-black text-clinicPrimary mt-1"><?= $count_ordonnances ?></h3>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl font-bold"></div>
            </div>
        </div>

        <section class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden mb-10">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-black text-clinicPrimary tracking-tight">Mes prochains rendez-vous </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Médecin</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Spécialité</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Date & Heure</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (empty($rendez_vous)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                    Vous n'avez aucun rendez-vous programmé pour le moment.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rendez_vous as $rdv): 
                                $badge_class = "text-amber-600 bg-amber-50 border-amber-100"; 
                                if ($rdv['statut'] === 'Confirmé') {
                                    $badge_class = "text-emerald-600 bg-emerald-50 border-emerald-100";
                                } elseif ($rdv['statut'] === 'Annulé') {
                                    $badge_class = "text-rose-600 bg-rose-50 border-rose-100";
                                }

                                $date_formatee = date('d/m/Y', strtotime($rdv['date_creneau']));
                                $heure_formatee = date('H:i', strtotime($rdv['heure_debut']));
                            ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-bold text-clinicPrimary">Dr. <?= htmlspecialchars($rdv['medecin_nom']) ?></td>
                                    <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($rdv['specialite']) ?></td>
                                    <td class="px-6 py-4 text-slate-600 font-medium"><?= $date_formatee ?> à <?= $heure_formatee ?></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold border rounded-md <?= $badge_class ?>">
                                            <?= htmlspecialchars($rdv['statut']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>