<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Repository/PatientRepository.php';
global $pdo;

$patient_id = $_SESSION['user_id'];

$repository = new PatientRepository($pdo);
$les_ordonnances = $repository->getOrdonnancesByPatient($patient_id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Mes Ordonnances</title>
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
                <a href="prendre_rdv.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition text-sm">
                     <span>Prendre RDV</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-clinicGreen text-white font-bold rounded-xl transition text-sm">
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
        
        <header class="border-b border-slate-200 pb-5 mb-8">
            <h1 class="text-2xl md:text-3xl font-black text-clinicPrimary tracking-tight">Mes Ordonnances</h1>
            <p class="text-xs text-slate-400 mt-1">Consultez et imprimez ici toutes les ordonnances détaillées délivrées par vos médecins.</p>
        </header>

        <section class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden mb-10">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-black text-clinicPrimary tracking-tight">Historique des ordonnances</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Médecin</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Spécialité</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider">Date de délivrance</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 tracking-wider text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (empty($les_ordonnances)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                    Aucune ordonnance n'est disponible pour le moment.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($les_ordonnances as $ord): 
                                $date_ord = date('d/m/Y', strtotime($ord['date_creneau']));
                            ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-bold text-clinicPrimary">Dr. <?= htmlspecialchars($ord['medecin_nom']) ?></td>
                                    <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($ord['specialite']) ?></td>
                                    <td class="px-6 py-4 text-slate-600 font-medium"><?= $date_ord ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <button onclick="ouvrirModal('Dr. <?= addslashes(htmlspecialchars($ord['medecin_nom'])) ?>', '<?= addslashes(htmlspecialchars($ord['specialite'])) ?>', '<?= $date_ord ?>', '<?= addslashes(nl2br(htmlspecialchars($ord['description']))) ?>')" class="inline-flex items-center gap-1 px-3 py-2 bg-sky-50 hover:bg-sky-500 text-sky-700 hover:text-white border border-sky-100 text-xs font-bold rounded-lg transition shadow-sm cursor-pointer">
                                             Voir l'ordonnance
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="ordonnanceModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden transform transition-all scale-95 duration-300">
            
            <div id="printArea" class="p-8 space-y-6">
                <div class="flex justify-between items-start border-b border-slate-200 pb-4">
                    <div>
                        <h3 id="modalMedecin" class="font-black text-clinicPrimary text-lg">Dr. Name</h3>
                        <p id="modalSpecialite" class="text-xs text-clinicGreen font-bold">Spécialité</p>
                        <p class="text-[10px] text-slate-400 mt-1"> MedFlow Clinic, Paris</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date</span>
                        <p id="modalDate" class="text-xs font-bold text-slate-700">00/00/0000</p>
                    </div>
                </div>

                <div class="space-y-3 min-h-[150px]">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider block">Ordonnance :</span>
                    <div id="modalDescription" class="text-sm text-slate-800 font-medium leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 whitespace-pre-line">
                        Contenu de l'ordonnance...
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-200 pt-4 text-center">
                    <p class="text-[10px] text-slate-400 italic">Document généré électroniquement par MedFlow.</p>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-100">
                <button onclick="fermerModal()" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl transition cursor-pointer">
                    Fermer
                </button>
                <button onclick="window.print()" class="px-4 py-2 bg-clinicGreen hover:bg-clinicGreenHover text-white font-bold text-xs rounded-xl transition shadow-md cursor-pointer">
                    Imprimer / PDF
                </button>
            </div>
        </div>
    </div>

    <script>
        function ouvrirModal(medecin, specialite, date, description) {
            document.getElementById('modalMedecin').innerText = medecin;
            document.getElementById('modalSpecialite').innerText = specialite;
            document.getElementById('modalDate').innerText = date;

            document.getElementById('modalDescription').innerHTML = description;
            
      
            document.getElementById('ordonnanceModal').classList.remove('hidden');
        }

        function fermerModal() {
          
            document.getElementById('ordonnanceModal').classList.add('hidden');
        }
    </script>

</body>
</html>