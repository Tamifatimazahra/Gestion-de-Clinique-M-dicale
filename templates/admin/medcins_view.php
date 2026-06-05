<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Gestion Médecins</title>
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
                    <a href="medcins.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-clinicGreen/10 text-clinicGreen font-semibold text-sm border-l-4 border-clinicGreen">Gestion Médecins</a>
                    <a href="specialites.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">Spécialités</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 font-semibold">Mode Administrateur</div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <header class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-clinicPrimary tracking-tight">Comptes des Médecins ⚡</h1>
                <p class="text-sm text-slate-500 mt-1">Créez de nouveaux profils médicaux ou gérez les accès de l'équipe existante.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm h-fit">
                    <h3 class="text-lg font-bold text-clinicPrimary mb-4">Inscrire un Médecin</h3>
                    <form action="medcins.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="add_medecin">
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Nom</label>
                                <input type="text" name="nom" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Prénom</label>
                                <input type="text" name="prenom" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Adresse Email</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Mot de passe initial</label>
                            <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Spécialité Médicale</label>
                            <div class="relative">
                                <select name="id_specialite" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition appearance-none">
                                    <option value="" disabled selected class="text-slate-400">Choisir une spécialité...</option>
                                    <?php 
                                    if (isset($specialites) && !empty($specialites)): 
                                        foreach ($specialites as $spec): 
                                            $specArray = is_object($spec) ? (array)$spec : $spec;
                                            $s_id = $specArray['id'] ?? $specArray['id_specialite'] ?? array_values($specArray)[0] ?? '';
                                            $s_nom = $specArray['nom'] ?? $specArray['nom_specialite'] ?? $specArray['libelle'] ?? array_values($specArray)[1] ?? 'Spécialité Inconnue';
                                    ?>
                                            <option value="<?= $s_id ?>" class="bg-white text-clinicPrimary"><?= htmlspecialchars($s_nom) ?></option>
                                    <?php 
                                        endforeach; 
                                    endif; 
                                    ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 mt-2 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-sm rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.25)] transition duration-200 uppercase tracking-wider">
                            Créer le compte
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h2 class="text-base font-bold text-clinicPrimary tracking-wide">Équipe Médicale de la Clinique</h2>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <?php if (!isset($medecins) || empty($medecins)): ?>
                            <div class="p-12 text-center text-slate-400 text-sm font-medium">Aucun médecin inscrit pour le moment.</div>
                        <?php else: ?>
                            <?php foreach ($medecins as $med): 
                                $m_actif = is_object($med) ? $med->actif : $med['actif'];
                                $m_nom = is_object($med) ? $med->nom : $med['nom'];
                                $m_prenom = is_object($med) ? $med->prenom : $med['prenom'];
                                $m_specialite = is_object($med) ? $med->specialite : $med['specialite'];
                                $m_email = is_object($med) ? $med->email : $med['email'];
                                $m_medecin_id = is_object($med) ? $med->medecin_id : $med['medecin_id'];
                                
                                $isActive = ($m_actif == 1);
                            ?>
                                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition hover:bg-slate-50/40 <?= !$isActive ? 'bg-slate-50/60 opacity-60' : '' ?>">
                                    <div class="flex items-center gap-4">
                                        <div class="h-11 w-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 shadow-sm">
                                            Dr
                                        </div>
                                        <div>
                                            <h4 class="text-base font-bold text-clinicPrimary">Dr. <?= htmlspecialchars($m_nom . ' ' . $m_prenom) ?></h4>
                                            <p class="text-xs text-clinicGreen font-semibold mt-0.5"><?= htmlspecialchars($m_specialite) ?></p>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5"><?= htmlspecialchars($m_email) ?></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div>
                                            <?php if ($isActive): ?>
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">● Actif</span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-600 border border-rose-200">● Désactivé</span>
                                            <?php endif; ?>
                                        </div>

                                        <a href="medcins.php?action=toggle_status&id=<?= $m_medecin_id ?>&status=<?= $m_actif ?>" 
                                           class="px-4 py-2 text-xs font-bold rounded-xl border transition duration-200 shadow-sm
                                           <?= $isActive 
                                               ? 'bg-slate-100 text-rose-600 border-slate-200 hover:bg-rose-50 hover:border-rose-300' 
                                               : 'bg-clinicGreen text-white hover:bg-clinicGreenHover border-transparent shadow-[0_3px_10px_rgba(156,201,67,0.2)]' ?>">
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