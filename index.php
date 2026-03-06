<?php
session_start();

$isAuthenticated = isset($_SESSION['username']);
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

if (!$isAuthenticated) {
    header('Location: login.php');
    exit;
}

$allowedPages = ['dashboard', 'history'];
if (!in_array($page, $allowedPages, true)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuzzy Weather Dashboard</title>
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🌤️%3C/text%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d7ff2",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            background-color: #101922;
        }
        body {
            -webkit-app-region: no-drag;
        }
        
        html, body {
            overflow-x: hidden;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 60px;
            height: 60px;
            background: var(--background-dark);
            z-index: 99999;
            pointer-events: none;
        }
        
        /* Ensure main content is above overlay */
        .main-wrapper {
            position: relative;
            z-index: 100000;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
    <div class="relative flex min-h-screen w-full">
        <!-- Sidebar -->
        <aside class="flex flex-col w-64 p-4 bg-background-dark/50 border-r border-white/10 shrink-0">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3 px-2">
                    <div class="bg-primary/20 rounded-full size-10 flex items-center justify-center">
                        <span class="text-2xl">🌤️</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-white text-base font-bold leading-normal">Fuzzy Weather</h1>
                        <p class="text-slate-400 text-sm font-normal leading-normal">Dashboard</p>
                    </div>
                </div>
            </div>
            <nav class="flex flex-col gap-2 mt-8">
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo (($_GET['page']??'dashboard')==='dashboard') ? 'bg-primary/20 text-primary' : 'text-slate-300 hover:bg-white/10'; ?> transition-colors" href="index.php?page=dashboard">
                    <span class="material-symbols-outlined">dashboard</span>
                    <p class="text-sm font-medium leading-normal">Dashboard</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo (($_GET['page']??'dashboard')==='history') ? 'bg-primary/20 text-primary' : 'text-slate-300 hover:bg-white/10'; ?> transition-colors" href="index.php?page=history">
                    <span class="material-symbols-outlined">history</span>
                    <p class="text-sm font-medium leading-normal">History</p>
                </a>
            </nav>
            <div class="mt-auto">
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 mb-2">
                    <span class="material-symbols-outlined">account_circle</span>
                    <p class="text-sm font-medium leading-normal"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-400 hover:bg-red-500/10 transition-colors" href="logout.php">
                    <span class="material-symbols-outlined">logout</span>
                    <p class="text-sm font-medium leading-normal">Keluar</p>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <?php require __DIR__ . "/pages/{$page}.php"; ?>
        </main>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>
