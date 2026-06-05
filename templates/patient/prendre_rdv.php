<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Controller/PatientController.php';
global $pdo;

$controller = new PatientController($pdo);
$data = $controller->handlePrendreRDV();

$les_medecins = $data['les_medecins'];
$message_success = $data['message_success'];
$message_error = $data['message_error'];
$repository = $data['repository'];


$id_medecin_selectionne = isset($_POST['id_medecin']) ? $_POST['id_medecin'] : (isset($_GET['medecin']) ? $_GET['medecin'] : "");


$jours_planning = [];
for ($i = 0; $i < 5; $i++) {
    $timestamp = strtotime("+$i days");
    $jours_planning[] = [
        'date_db'  => date('Y-m-d', $timestamp), 
        'nom_jour' => date('l', $timestamp),     
        'num_jour' => date('j', $timestamp),     
        'mois'     => date('F', $timestamp)      
    ];
}

$trad_jours = ['Friday'=>'vendredi', 'Saturday'=>'samedi', 'Sunday'=>'dimanche', 'Monday'=>'lundi', 'Tuesday'=>'mardi', 'Wednesday'=>'mercredi', 'Thursday'=>'jeudi'];
$trad_mois  = ['June'=>'juin', 'July'=>'juil.', 'August'=>'août'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Prendre RDV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { clinicGreen: '#9CC943', clinicPrimary: '#0f172a', medicalIceBg: '#eef2f7' } } }
        }
    </script>
</head>
<body class="bg-medicalIceBg p-4 md:p-8 font-sans">

    <div class="max-w-5xl mx-auto space-y-6">
        <a href="dashboard.php" class="text-xs font-bold text-clinicGreen hover:underline">← Retour au Dashboard</a>

        <?php if (!empty($message_success)): ?>
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-center font-bold text-xs"> <?= $message_success ?></div>
        <?php endif; ?>
        <?php if (!empty($message_error)): ?>
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-center font-bold text-xs"> <?= $message_error ?></div>
        <?php endif; ?>

        <div class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm">
            <form action="" method="POST" class="flex flex-col sm:flex-row gap-3 items-center">
                <label class="text-xs font-bold text-clinicPrimary whitespace-nowrap">Choisir un Médecin :</label>
                <select name="id_medecin" onchange="this.form.submit()" class="w-full sm:flex-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-clinicGreen cursor-pointer">
                    <option value="">-- Sélectionnez --</option>
                    <?php foreach ($les_medecins as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= ($id_medecin_selectionne == $m['id']) ? 'selected' : '' ?>>
                            Dr. <?= htmlspecialchars($m['nom']) ?> (<?= htmlspecialchars($m['specialite']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if (!empty($id_medecin_selectionne)): 
            $med_info = current(array_filter($les_medecins, function($item) use ($id_medecin_selectionne) { return $item['id'] == $id_medecin_selectionne; }));
           
            $img = !empty($med_info['image']) ? $med_info['image'] : 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=150';
        ?>
            <div class="bg-white border border-slate-200 shadow-xl rounded-3xl p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <div class="lg:col-span-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="<?= htmlspecialchars($img) ?>" class="w-14 h-14 rounded-full object-cover border shadow-sm">
                        <div>
                            <h3 class="font-bold text-blue-600 text-sm">Dr. <?= htmlspecialchars($med_info['nom']) ?></h3>
                            <p class="text-xs text-slate-400 font-medium"><?= htmlspecialchars($med_info['specialite']) ?></p>
                        </div>
                    </div>
                    <div class="text-[11px] text-slate-500 space-y-1.5 pt-3 border-t border-slate-100">
                        <p> <?= htmlspecialchars($med_info['description']) ?></p>
                        <p> Conventionné secteur 1</p>
                    </div>
                </div>

                <div class="lg:col-span-8 border border-slate-100 rounded-2xl p-4 bg-slate-50/50">
                    
                    <div class="grid grid-cols-5 text-center border-b border-slate-200 pb-2 mb-3">
                        <?php foreach ($jours_planning as $j): 
                            $nom_j = $trad_jours[$j['nom_jour']] ?? $j['nom_jour'];
                            $nom_m = $trad_mois[$j['mois']] ?? $j['mois'];
                        ?>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-clinicPrimary"><?= $nom_j ?></span>
                                <span class="text-[10px] text-slate-400 font-medium"><?= $j['num_jour'] ?> <?= $nom_m ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="grid grid-cols-5 gap-2 text-center">
                        <?php foreach ($jours_planning as $j): ?>
                            <div class="space-y-2 flex flex-col items-center">
                                <?php 
                              
                                $schedules = $repository->getCreneauxDisponibles($id_medecin_selectionne, $j['date_db']);

                                if (empty($schedules)): ?>
                                    <span class="text-slate-300 text-xs py-2">—</span>
                                <?php else: ?>
                                    <?php foreach ($schedules as $c): 
                                        $time = date('H:i', strtotime($c['heure_debut']));
                                    ?>
                                        <form action="" method="POST" class="w-full">
                                            <input type="hidden" name="id_creneau" value="<?= $c['id'] ?>">
                                            <input type="hidden" name="id_medecin" value="<?= $id_medecin_selectionne ?>">
                                            <button type="submit" name="confirmer_creneau" class="w-full bg-sky-50 hover:bg-sky-500 text-sky-700 hover:text-white border border-sky-100 font-bold text-xs py-2 rounded-lg transition shadow-sm cursor-pointer block text-center">
                                                <?= $time ?>
                                            </button>
                                        </form>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>