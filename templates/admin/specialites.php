<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Gestion Spécialités</title>
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
                    <a href="medcins.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 text-sm transition">Gestion Médecins</a>
                    <a href="specialites.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-500/10 to-transparent text-emerald-400 border-l-2 border-emerald-500 font-medium text-sm">Spécialités</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-slate-800/60 text-xs text-slate-500">Mode Administrateur</div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <header class="mb-8">
                <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Spécialités Médicales</h1>
                <p class="text-sm text-slate-400 mt-1">Gérez la liste des spécialités disponibles dans la clinique.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="glass-card p-6 rounded-2xl h-fit">
                    <h3 class="text-lg font-bold text-white mb-4">Ajouter une Spécialité</h3>
                    <form action="specialites.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="add_specialite">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-2">Nom de la spécialité</label>
                            <input type="text" name="nom" required class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-emerald-500 transition"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-black font-black text-sm rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.3)] transition duration-200">
                            Enregistrer
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 glass-card rounded-2xl overflow-hidden shadow-2xl">
                    <div class="px-6 py-5 border-b border-slate-800 bg-slate-900/40">
                        <h2 class="text-lg font-bold text-white tracking-wide">Flux des Spécialités Active</h2>
                    </div>

                    <div class="divide-y divide-slate-800/60">
                        <?php if (empty($specialites)): ?>
                            <div class="p-8 text-center text-slate-500 text-sm">Aucune spécialité trouvée.</div>
                        <?php else: ?>
                            <?php foreach ($specialites as $spec): ?>
                                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition duration-200 hover:bg-slate-800/10">
                                    <div>
                                        <h4 class="text-base font-bold text-white"><?= htmlspecialchars($spec->nom) ?></h4>
                                        <p class="text-xs text-slate-400 mt-1"><?= htmlspecialchars($spec->description ?? 'Pas de description') ?></p>
                                        <p class="text-[10px] text-slate-600 mt-1">ID: #SPEC-<?= $spec->id ?></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="specialites.php?action=delete_specialite&id=<?= $spec->id ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette spécialité ?')" class="px-3 py-2 bg-slate-800 hover:bg-rose-500/20 hover:text-rose-400 text-slate-400 font-medium text-xs rounded-xl border border-slate-700 hover:border-rose-500/30 transition duration-200">
                                            Supprimer
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