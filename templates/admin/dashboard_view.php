<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow | Dashboard</title>
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
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-clinicGreen/10 text-clinicGreen font-semibold text-sm border-l-4 border-clinicGreen">Dashboard</a>
                    <a href="medcins.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">Gestion Médecins</a>
                    <a href="specialites.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium transition">Spécialités</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 font-semibold">Mode Administrateur</div>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 flex items-center justify-center">
            
            <div class="max-w-2xl w-full bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm text-center">
                <div class="h-14 w-14 bg-clinicGreen/10 rounded-2xl flex items-center justify-center text-clinicGreen text-2xl font-bold mx-auto mb-4">
                    ⚡
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-clinicPrimary tracking-tight">Tableau de Bord Administrateur</h1>
                <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">Bienvenue sur votre espace de gestion MedFlow. Choisissez une section pour commencer à gérer la clinique.</p>
                
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="medcins.php" class="px-6 py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-xs rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.2)] transition uppercase tracking-wider">
                        Gérer Médecins
                    </a>
                    <a href="specialites.php" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-clinicPrimary font-bold text-xs rounded-xl border border-slate-200 transition uppercase tracking-wider">
                        Gérer Spécialités
                    </a>
                </div>
            </div>

        </main>
    </div>

</body>
</html>