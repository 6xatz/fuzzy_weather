<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['username'] = 'admin';
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah';
}
?>
<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · Fuzzy Weather</title>
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🌤️%3C/text%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d7ff2",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #0f1419 0%, #1a2332 100%);
            font-family: 'Manrope', sans-serif;
            overflow-x: hidden;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        *::-webkit-scrollbar {
            display: none;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 50px;
            height: 50px;
            background: transparent;
            z-index: 9999;
            pointer-events: none;
        }
        
        .login-container {
            position: relative;
            z-index: 1000;
            width: 100%;
            max-width: 28rem;
        }
        
        /* Weather animation background */
        .weather-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .weather-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            animation: float 6s ease-in-out infinite;
        }
        
        .weather-orb:nth-child(1) {
            width: 200px;
            height: 200px;
            background: rgba(13, 127, 242, 0.1);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .weather-orb:nth-child(2) {
            width: 150px;
            height: 150px;
            background: rgba(93, 212, 255, 0.08);
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .weather-orb:nth-child(3) {
            width: 180px;
            height: 180px;
            background: rgba(139, 92, 246, 0.06);
            bottom: 20%;
            left: 50%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
    </style>
</head>
<body class="dark min-h-screen flex items-center justify-center p-4">
    <div class="weather-bg">
        <div class="weather-orb"></div>
        <div class="weather-orb"></div>
        <div class="weather-orb"></div>
    </div>

    <div class="login-container">
        <form class="bg-slate-900/60 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl hover:shadow-primary/10 transition-all duration-300" method="post" autocomplete="off">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary/20 to-cyan-500/20 rounded-full mb-4 shadow-lg">
                    <span class="text-5xl animate-pulse">🌤️</span>
                </div>
                <h1 class="text-white text-3xl font-bold mb-2 bg-gradient-to-r from-blue-300 to-cyan-300 bg-clip-text text-transparent">
                    Fuzzy Weather
                </h1>
                <p class="text-slate-400 text-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-xs">login</span>
                    Masuk ke Dashboard
                </p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-red-400">error</span>
                        <p class="text-red-400 text-sm"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <div>
                    <label class="block text-slate-300 text-sm font-medium mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">person</span>
                        Username
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        required 
                        class="w-full px-4 py-4 bg-slate-800/60 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all backdrop-blur-sm"
                        placeholder="Masukkan username"
                        autofocus
                    >
                </div>

                <div>
                    <label class="block text-slate-300 text-sm font-medium mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">lock</span>
                        Password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        class="w-full px-4 py-4 bg-slate-800/60 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all backdrop-blur-sm"
                        placeholder="Masukkan password"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full py-4 bg-gradient-to-r from-primary to-cyan-500 hover:from-primary/90 hover:to-cyan-500/90 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-primary/25 flex items-center justify-center gap-3 hover:scale-[1.02] hover:shadow-xl hover:shadow-primary/30"
                >
                    <span>Masuk ke Dashboard</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>

            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <p class="text-slate-400 text-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-xs">info</span>
                    Default: <span class="text-cyan-400 font-medium">admin / admin</span>
                </p>
            </div>
        </form>
    </div>
</body>
</html>




