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
 
 <section class="max-w-xl mx-auto px-4 py-12">
        <div class="bg-white border border-slate-200 shadow-xl rounded-3xl p-8">
            <div class="text-center mb-6">
            <div class="h-12 w-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-black font-black text-2xl shadow-[0_0_20px_rgba(16,185,129,0.4)] mx-auto mb-4"><a href="../../public/index.php">M</a></div>

                <span class="text-xs font-bold text-clinicGreen uppercase tracking-widest">Nouveau sur MedFlow ?</span>
                <h2 class="text-2xl font-black text-clinicPrimary mt-1">Créer un compte Patient 👤</h2>
                <p class="text-xs text-slate-400 mt-1">Inscrivez-vous pour planifier vos consultations et suivre vos ordonnances.</p>
            </div>

           
            <form action="register_patient.php" method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Nom</label>
                        <input type="text" name="nom" required placeholder="Alami"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-clinicGreen transition text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Prénom</label>
                        <input type="text" name="prenom" required placeholder="Ahmed"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-clinicGreen transition text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Adresse Email</label>
                    <input type="email" name="email" required placeholder="patient@example.com"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-clinicGreen transition text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Mot de passe</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-clinicGreen transition text-sm">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-clinicGreen hover:bg-clinicGreenHover text-white font-black text-sm rounded-xl shadow-[0_4px_12px_rgba(156,201,67,0.3)] transition uppercase tracking-wider">
                        Créer mon compte
                    </button>
                </div>
            </form>
        </div>
    </section>

    
</body>
</html>