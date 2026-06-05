<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Horaires Médecins</title>
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
                    <a href="specialites.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">Spécialités</a>
                    <a href="creneaux.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-clinicGreen/10 text-clinicGreen font-semibold text-sm border-l-4 border-clinicGreen">Horaires Médecins</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 font-semibold">Mode Administrateur</div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <header class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-clinicPrimary tracking-tight">Horaires & Créneaux ⚡</h1>
                <p class="text-sm text-slate-500 mt-1">Définissez les plages horaires de disponibilité pour chaque médecin de la clinique.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm h-fit">
                    <h3 class="text-lg font-bold text-clinicPrimary mb-4">Ajouter un Créneau</h3>
                    <form action="creneaux.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="add_creneau">
                        
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Sélectionner le Médecin</label>
                            <select name="id_medecin" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                                <option value="" disabled selected class="text-slate-400">Choisir un médecin...</option>
                                <?php if (isset($medecins) && !empty($medecins)): ?>
                                    <?php foreach ($medecins as $med): 
                                        $m_actif = is_object($med) ? $med->actif : $med['actif'];
                                        if ($m_actif == 1): 
                                            $m_id = is_object($med) ? $med->medecin_id : $med['medecin_id'];
                                            $m_nom = is_object($med) ? $med->nom : $med['nom'];
                                            $m_prenom = is_object($med) ? $med->prenom : $med['prenom'];
                                            $m_spec = is_object($med) ? $med->specialite : $med['specialite'];
                                    ?>
                                        <option value="<?= $m_id ?>">Dr. <?= htmlspecialchars($m_nom . ' ' . $m_prenom) ?> (<?= htmlspecialchars($m_spec) ?>)</option>
                                    <?php endif; endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Date du Créneau</label>
                            <input type="date" name="date_creneau" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Heure de Début</label>
                                <input type="time" name="heure_debut" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Heure de Fin</label>
                                <input type="time" name="heure_fin" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-clinicPrimary focus:outline-none focus:border-clinicGreen focus:bg-white transition">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 mt-2 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-sm rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.25)] transition duration-200 uppercase tracking-wider">
                            Générer le Créneau
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="text-base font-bold text-clinicPrimary tracking-wide">Plages Horaires Enregistrées</h2>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <?php if (!isset($creneaux) || empty($creneaux)): ?>
                            <div class="p-12 text-center text-slate-400 text-sm font-medium">Aucun créneau horaire configuré pour le moment.</div>
                        <?php else: ?>
                            <?php foreach ($creneaux as $c): ?>
                                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition hover:bg-slate-50/40">
                                    <div class="flex items-center gap-4">
                                        <div class="h-11 w-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-clinicGreen shadow-sm">⏰</div>
                                        <div>
                                            <h4 class="text-base font-bold text-clinicPrimary">Dr. <?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?></h4>
                                            <p class="text-xs text-slate-400 mt-0.5">Spécialité: <span class="font-semibold text-slate-600"><?= htmlspecialchars($c['specialite']) ?></span></p>
                                            
                                            <div class="flex flex-wrap items-center gap-2 mt-2 text-xs font-medium text-slate-500">
                                                <span class="bg-clinicGreen/10 text-clinicGreen px-2.5 py-1 rounded-lg font-bold">
                                                    📅 <?= date('d/m/Y', strtotime($c['date_creneau'])) ?>
                                                </span>
                                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg font-mono font-semibold text-slate-700">
                                                    ⏱️ <?= date('H:i', strtotime($c['heure_debut'])) ?> - <?= date('H:i', strtotime($c['heure_fin'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if ($c['disponible'] == 1): ?>
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">Libre</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-600 border border-amber-200">Réservé</span>
                                        <?php endif; ?>
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