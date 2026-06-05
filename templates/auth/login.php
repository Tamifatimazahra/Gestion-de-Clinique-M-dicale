<?php
require_once __DIR__ . '/../../src/Controller/AuthController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Connexion Unifiée</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0b0f19; background-image: radial-gradient(circle at 50% 0%, #1e2640 0%, #0b0f19 70%); }
        .glass-card { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="text-slate-200 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md glass-card rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        
        <div class="text-center mb-8">
            <div class="h-12 w-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-black font-black text-2xl shadow-[0_0_20px_rgba(16,185,129,0.4)] mx-auto mb-4"><a href="../../public/index.php">M</a></div>
            <h1 class="text-2xl font-black text-white tracking-tight">Connexion Med<span class="text-emerald-400">Flow</span></h1>
            <p class="text-xs text-slate-400 mt-1">Accédez à votre espace sécurisé selon votre profil</p>
        </div>

        <?php if (!empty($erreur)): ?>
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-medium p-4 rounded-xl mb-6 text-center">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Adresse Email</label>
                <input type="email" name="email" required placeholder="nom@medflow.ma" class="w-full bg-slate-900/60 border border-slate-800 rounded-xl p-3.5 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-emerald-500/50 transition text-sm">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Mot de passe</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-900/60 border border-slate-800 rounded-xl p-3.5 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-emerald-500/50 transition text-sm">
            </div>

            <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs rounded-xl tracking-wider uppercase transition duration-200 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                Se Connecter
            </button>
        </form>

    </div>

</body>
</html>