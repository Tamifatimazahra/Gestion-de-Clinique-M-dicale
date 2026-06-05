<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Controller/MedecinController.php';
global $pdo;

$controller = new MedecinController($pdo);
$data = $controller->handleGestionCreneaux();

$mes_creneaux = $data['mes_creneaux'];
$message_success = $data['message_success'];
$message_error = $data['message_error'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Définir mes disponibilités</title>
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
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition text-sm">
                     <span>Tableau de bord</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-clinicGreen text-white font-bold rounded-xl transition text-sm">
                     <span>Mes Disponibilités</span>
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
        <header class="border-b border-slate-200 pb-5 mb-8">
            <h1 class="text-2xl md:text-3xl font-black text-clinicPrimary tracking-tight">Gestion des disponibilités</h1>
            <p class="text-xs text-slate-400 mt-1">Configurez les jours et les heures où vous êtes disponible pour recevoir des patients.</p>
        </header>

        <?php if (!empty($message_success)): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-center font-bold text-xs">🎉 <?= $message_success ?></div>
        <?php endif; ?>
        <?php if (!empty($message_error)): ?>
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-center font-bold text-xs">⚠️ <?= $message_error ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 border border-slate-200 rounded-3xl shadow-sm h-fit">
                <h2 class="text-sm font-black text-clinicPrimary uppercase tracking-wider mb-4">Ajouter un créneau</h2>
                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-1.5">Choisir la Date :</label>
                        <input type="date" name="date_creneau" min="<?= date('Y-m-d') ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-clinicGreen">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-1.5">Heure de début :</label>
                        <input type="time" name="heure_debut" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-clinicGreen">
                    </div>
                    <button type="submit" name="ajouter_creneau" class="w-full py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-xs rounded-xl shadow-md transition cursor-pointer">
                        ＋ Ajouter au planning
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-sm font-black text-clinicPrimary uppercase tracking-wider">Mon Planning Actuel</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Date</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Heure</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if (empty($mes_creneaux)): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-400">
                                        Aucun créneau configuré pour le moment.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mes_creneaux as $c): 
                                    $date_c = date('d/m/Y', strtotime($c['date_creneau']));
                                    $heure_c = date('H:i', strtotime($c['heure_debut']));
                                    
                                    // تحديد الـ Badge على حسب واش الساعة تخاذت ولا مازال
                                    $badge = $c['disponible'] == 1 
                                        ? "text-emerald-600 bg-emerald-50 border-emerald-100" // متاح
                                        : "text-slate-400 bg-slate-100 border-slate-200 line-through"; // محجوز
                                    $text_badge = $c['disponible'] == 1 ? "Disponible" : "Réservé";
                                ?>
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4 font-bold text-clinicPrimary"><?= $date_c ?></td>
                                        <td class="px-6 py-4 text-slate-600 font-medium"><?= $heure_c ?></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold border rounded-md <?= $badge ?>">
                                                <?= $text_badge ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>
</html>