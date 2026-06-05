<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Spécialités Médicales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinicGreen: '#9CC943',
                        clinicGreenHover: '#88b236',
                        clinicPrimary: '#0f172a', 
                        clinicBg: '#f8fafc', 
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-clinicBg text-slate-700 font-sans min-h-screen">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-white border-r border-slate-200 p-6 flex flex-col justify-between hidden md:flex shadow-sm">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="h-9 w-9 rounded-xl bg-clinicGreen flex items-center justify-center text-white font-black text-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)]">M</div>
                    <span class="text-xl font-bold tracking-wider text-clinicPrimary">Med<span class="text-clinicGreen">Flow</span></span>
                </div>
                <nav class="space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">Dashboard</a>
                    <a href="medcins.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">Gestion Médecins</a>
                    <a href="specialites.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-clinicGreen/10 text-clinicGreen font-semibold text-sm border-l-4 border-clinicGreen">Spécialités</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 font-semibold">Mode Administrateur</div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <header class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-clinicPrimary tracking-tight">Spécialités Médicales ⚡</h1>
                <p class="text-sm text-slate-500 mt-1">Gérer la liste des spécialités disponibles dans la clinique.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm h-fit">
                    <h3 class="text-lg font-bold text-clinicPrimary mb-4">Ajouter une Spécialité</h3>
                    <form action="specialites.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="add_specialite">
                        
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Nom de la spécialité</label>
                            <input type="text" name="nom" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Description</label>
                            <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 mt-2 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-sm rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.25)] transition duration-200 uppercase tracking-wider">
                            Enregistrer
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="text-base font-bold text-clinicPrimary tracking-wide">Flux des Spécialités Actives</h2>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <?php if (!isset($specialites) || empty($specialites)): ?>
                            <div class="p-12 text-center text-slate-400 text-sm font-medium">Aucune spécialité configurée pour le moment.</div>
                        <?php else: ?>
                            <?php foreach ($specialites as $spec): 
                                // Hna t-n-choufou wach object wla array bach n-تفاداو l'error b-marra
                                $s_id = is_object($spec) ? $spec->id : $spec['id'];
                                $s_nom = is_object($spec) ? $spec->nom : $spec['nom'];
                                $s_desc = is_object($spec) ? $spec->description : $spec['description'];
                            ?>
                                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition hover:bg-slate-50/40">
                                    <div>
                                        <h4 class="text-base font-bold text-clinicPrimary"><?= htmlspecialchars($s_nom) ?></h4>
                                        <p class="text-sm text-slate-400 mt-1"><?= htmlspecialchars($s_desc) ?></p>
                                        <span class="text-xs text-slate-400 font-mono inline-block mt-2">ID: #SPEC-<?= $s_id ?></span>
                                    </div>
                                    <div>
                                        <a href="specialites.php?action=delete_specialite&id=<?= $s_id ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette spécialité ?')" class="px-4 py-2 text-xs font-bold rounded-xl bg-slate-100 hover:bg-red-50 hover:text-red-600 border border-slate-200 hover:border-red-200 text-slate-600 transition block text-center">
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