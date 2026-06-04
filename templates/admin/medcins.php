<?php
require_once __DIR__ . '/../../src/Controller/AdminController.php';
$controller = new AdminController();
$controller->gererMedecins();
return;
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Gestion Médecins</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0b0f19;
            background-image: radial-gradient(circle at 50% 0%, #1e2640 0%, #0b0f19 70%);
        }
        .glass-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="text-slate-200 font-sans min-h-screen">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 glass-card border-r border-slate-800 p-6 flex flex-col justify-between hidden md:flex">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="h-9 w-9 rounded-xl bg-emerald-500 flex items-center justify-center text-black font-black text-xl shadow-[0_0_15px_rgba(16,185,129,0.5)]">M</div>
                    <span class="text-xl font-bold tracking-wider text-white">Med<span class="text-emerald-400">Flow</span></span>
                </div>
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 text-sm transition">Dashboard</a>
                    <a href="medcins.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-500/10 to-transparent text-emerald-400 border-l-2 border-emerald-500 font-medium text-sm">Gestion Médecins</a>
                    <a href="specialites.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 text-sm transition">Spécialités</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-slate-800/60 text-xs text-slate-500">Mode Administrateur</div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <header class="mb-8">
                <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Comptes des Médecins</h1>
                <p class="text-sm text-slate-400 mt-1">Créez de nouveaux profils médicaux ou gérez les accès de l'équipe existante.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="glass-card p-6 rounded-2xl h-fit">
                    <h3 class="text-lg font-bold text-white mb-4">Inscrire un Médecin</h3>
                    <form action="medcins.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="add_medecin">
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Nom</label>
                                <input type="text" name="nom" required class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Prénom</label>
                                <input type="text" name="prenom" required class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Adresse Email</label>
                            <input type="email" name="email" required class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Mot de passe initial</label>
                            <input type="password" name="password" required class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Spécialité Médicale</label>
                            <select name="id_specialite" required class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500 transition appearance-none">
                                <option value="" disabled selected class="text-slate-500">Choisir une spécialité...</option>
                                <?php foreach ($specialites as $spec): ?>
                                    <option value="<?= $spec->id ?>" class="bg-slate-900 text-white"><?= htmlspecialchars($spec->nom) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-3 mt-2 bg-emerald-500 hover:bg-emerald-400 text-black font-black text-sm rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.3)] transition duration-200">
                            Créer le compte
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 glass-card rounded-2xl overflow-hidden shadow-2xl">
                    <div class="px-6 py-5 border-b border-slate-800 bg-slate-900/40 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-white tracking-wide">Équipe Médicale de la Clinique</h2>
                    </div>

                    <div class="divide-y divide-slate-800/60">
                        <?php if (empty($medecins)): ?>
                            <div class="p-8 text-center text-slate-500 text-sm">Aucun médecin inscrit pour le moment.</div>
                        <?php else: ?>
                            <?php foreach ($medecins as $med): 
                                $isActive = ($med->actif == 1);
                            ?>
                                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition duration-200 <?= !$isActive ? 'opacity-40 bg-slate-950/20' : '' ?>">
                                    
                                    <div class="flex items-center gap-4">
                                        <div class="h-11 w-11 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-white shadow-inner">
                                            Dr
                                        </div>
                                        <div>
                                            <h4 class="text-base font-bold text-white">Dr. <?= htmlspecialchars($med->nom . ' ' . $med->prenom) ?></h4>
                                            <p class="text-xs text-emerald-400 font-medium"><?= htmlspecialchars($med->specialite) ?></p>
                                            <p class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($med->email) ?></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div>
                                            <?php if ($isActive): ?>
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Actif</span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">Désactivé</span>
                                            <?php endif; ?>
                                        </div>

                                        <a href="medcins.php?action=toggle_status&id=<?= $med->medecin_id ?>&status=<?= $med->actif ?>" 
                                           class="px-4 py-2 text-xs font-bold rounded-xl border transition duration-200 
                                           <?= $isActive 
                                               ? 'bg-slate-800 text-rose-400 border-slate-700 hover:bg-rose-500/10 hover:border-rose-500/20' 
                                               : 'bg-emerald-500 text-black hover:bg-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.2)] border-transparent' ?>">
                                            <?= $isActive ? 'Désactiver' : 'Activer' ?>
                                        </a>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>