<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Controller/HomeController.php';

global $pdo;


$medecinRepo = new MedecinRepository($pdo);
$homeController = new HomeController($medecinRepo);

$data = $homeController->index();
$top_medecins = $data['top_medecins'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Accueil</title>
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

    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-clinicGreen flex items-center justify-center text-white font-black text-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)]">M</div>
                <span class="text-xl font-bold tracking-wider text-clinicPrimary">Med<span class="text-clinicGreen">Flow</span></span>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="../templates/auth/register.php" class="text-sm font-bold text-slate-600 hover:text-clinicPrimary px-3 py-2 transition">
                    Créer un compte
                </a>
                <a href="../templates/auth/login.php" class="px-5 py-2.5 bg-clinicPrimary hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition shadow-sm">
                    Se Connecter 
                </a>
            </div>
        </div>
    </header>

    <section class="max-w-4xl mx-auto text-center px-4 pt-16 pb-12">
        <h1 class="text-3xl md:text-5xl font-black text-clinicPrimary tracking-tight leading-none">
            Trouvez votre médecin et prenez <br class="hidden md:block"><span class="text-clinicGreen">un Rendez-vous</span>
        </h1>
        <p class="text-slate-500 text-sm md:text-base mt-3 max-w-xl mx-auto">
            Rechercher des professionnels de santé par leur nom ou leur spécialité médicale en toute simplicité.
        </p>

        <div class="mt-8 bg-white p-3 rounded-2xl border border-slate-200 shadow-lg max-w-2xl mx-auto">
            <form action="search_results.php" method="GET" class="flex flex-col sm:flex-row gap-2">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">🔍</span>
                    <input type="text" name="query" required
                        placeholder="Nom du médecin ou spécialité (ex: Cardiologue)..." 
                        class="w-full pl-9 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-clinicGreen focus:ring-2 focus:ring-clinicGreen/20 transition text-sm">
                </div>
                <button type="submit" class="px-6 py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-sm rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)] transition whitespace-nowrap cursor-pointer">
                    Rechercher
                </button>
            </form>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mb-16">
        <h2 class="text-xl md:text-2xl font-black text-clinicPrimary tracking-tight mb-6">Nos Médecins Disponibles 🌟</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($top_medecins)): ?>
                <div class="col-span-full text-center p-12 bg-white rounded-2xl border border-slate-200 text-slate-400 text-sm font-medium">
                     Aucun médecin n'est inscrit pour le moment.
                </div>
            <?php else: ?>
                <?php foreach ($top_medecins as $med): 
                    $imgUrl = !empty($med['image']) ? $med['image'] : 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=500&auto=format&fit=crop&q=60';
                ?>
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div class="p-5 flex items-center gap-4">
                            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Dr" class="w-16 h-16 rounded-xl object-cover border border-slate-100 shadow-inner">
                            <div>
                                <h3 class="font-bold text-clinicPrimary text-base"><?= htmlspecialchars($med['nom']) ?></h3>
                                <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-bold text-blue-600 bg-blue-50 border border-blue-100 rounded-md">
                                    <?= htmlspecialchars($med['specialite'] ?? 'Généraliste') ?>
                                </span>
                            </div>
                        </div>
                        <div class="px-5 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                            <a href="auth/login.php" class="px-4 py-2 bg-clinicGreen/10 text-clinicGreen hover:bg-clinicGreen hover:text-white transition font-bold text-xs rounded-lg uppercase tracking-wider">
                                Prendre RDV
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>