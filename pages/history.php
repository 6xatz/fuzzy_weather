
<?php
$snapPath = __DIR__ . '/../data/snapshots.json';
if (!file_exists($snapPath)) { @mkdir(dirname($snapPath), 0777, true); file_put_contents($snapPath, json_encode([])); }
$snaps = json_decode(file_get_contents($snapPath) ?: '[]', true);
if (!is_array($snaps)) { $snaps = []; }
usort($snaps, function($a,$b){ return ($b['time'] ?? 0) <=> ($a['time'] ?? 0); });
?>

<title>History · Fuzzy Weather</title>

<div class="space-y-8">
    <div class="flex flex-wrap items-center justify-between gap-6">
        <div class="space-y-2">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text text-transparent">
                🌤️ Weather History
            </h1>
            <p class="text-gray-400 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">history</span>
                Saved weather snapshots and recommendations
            </p>
        </div>
        <a href="index.php?page=dashboard" class="group flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-secondary to-primary text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-secondary/25 transition-all duration-300 hover:scale-105">
            <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform duration-300">arrow_back</span>
            <span>Back to Dashboard</span>
        </a>
    </div>

    <div class="flex justify-end gap-3 mb-6">
        <button onclick="exportHistory()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">download</span>
            <span>Export CSV</span>
        </button>
        <button id="clear-history-btn" onclick="clearHistory()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">delete</span>
            <span>Clear All</span>
        </button>
    </div>

    <div class="bg-surface/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-sm">table_chart</span>
                </div>
                <h2 class="text-xl font-bold text-slate-100">Snapshot Records</h2>
                <div class="ml-auto px-3 py-1 bg-primary/20 text-primary text-xs font-bold rounded-full border border-primary/30">
                    <?php echo count($snaps); ?> Records
                </div>
            </div>
        </div>

        <?php if (!$snaps): ?>
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-gray-600/20 to-gray-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-gray-400">inbox</span>
                </div>
                <h3 class="text-lg font-semibold text-slate-200 mb-2">No Snapshots Yet</h3>
                <p class="text-gray-400 mb-6">Start saving weather data from the dashboard</p>
                <a href="index.php?page=dashboard" class="inline-flex items-center gap-2 px-4 py-2 bg-primary/20 text-primary rounded-lg hover:bg-primary/30 transition-colors">
                    <span class="material-symbols-outlined text-sm">add</span>
                    <span>Create First Snapshot</span>
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    Time
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">location_on</span>
                                    Location
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">thermostat</span>
                                    Temperature
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">water_drop</span>
                                    Humidity
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">air</span>
                                    Wind
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">psychology</span>
                                    Recommendation
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">analytics</span>
                                    Confidence
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">person</span>
                                    User
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <?php foreach ($snaps as $index => $s): ?>
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200 font-medium">
                                        <?php echo date('M j, Y', (int)($s['time'] ?? time())); ?>
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        <?php echo date('H:i:s', (int)($s['time'] ?? time())); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                                        <span class="text-sm font-medium text-slate-200">
                                            <?php echo htmlspecialchars($s['city'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-orange-400">🌡️</span>
                                        <span class="text-sm text-slate-200 font-medium">
                                            <?php echo htmlspecialchars((string)($s['temperature'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>°C
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-blue-400">💧</span>
                                        <span class="text-sm text-slate-200 font-medium">
                                            <?php echo htmlspecialchars((string)($s['humidity'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-cyan-400">💨</span>
                                        <span class="text-sm text-slate-200 font-medium">
                                            <?php echo htmlspecialchars((string)($s['wind'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?> km/h
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-300 border border-purple-400/30">
                                            <?php echo htmlspecialchars($s['action'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (isset($s['confidence'])): ?>
                                        <div class="flex items-center gap-2">
                                            <div class="w-12 bg-gray-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full" style="width: <?php echo round($s['confidence']*100); ?>%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-slate-300">
                                                <?php echo round($s['confidence']*100); ?>%
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-sm">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-gradient-to-br from-accent to-primary rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">
                                                <?php echo strtoupper(substr($s['user'] ?? 'U', 0, 1)); ?>
                                            </span>
                                        </div>
                                        <span class="text-sm text-slate-300">
                                            <?php echo htmlspecialchars($s['user'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
async function clearHistory() {
    if (!confirm('Yakin mau hapus semua history? Ini tidak bisa dibatalkan!')) {
        return;
    }
    
    const btn = document.getElementById('clear-history-btn');
    const originalHTML = btn.innerHTML;
    
    try {
        btn.disabled = true;
        btn.innerHTML = `
            <span class="material-symbols-outlined text-sm animate-spin">refresh</span>
            <span>Clearing...</span>
        `;
        
        const response = await fetch('api/clear_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.ok) {
            alert('✅ History berhasil dihapus!');
            window.location.reload();
        } else {
            throw new Error(result.error || 'Unknown error');
        }
        
    } catch(e) {
        console.error('Clear failed:', e);
        alert('❌ Gagal hapus history: ' + e.message);
        
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

async function exportHistory() {
    try {
        const response = await fetch('data/snapshots.json');
        const data = await response.json();
        
        if (!data || data.length === 0) {
            alert('Tidak ada data untuk di-export');
            return;
        }
        
        const headers = ['Date', 'Time', 'City', 'Temperature', 'Humidity', 'Wind', 'Recommendation', 'Confidence', 'User'];
        const csvContent = [
            headers.join(','),
            ...data.map(item => [
                new Date(item.time * 1000).toLocaleDateString(),
                new Date(item.time * 1000).toLocaleTimeString(),
                item.city,
                item.temperature,
                item.humidity || 'N/A',
                item.wind,
                `"${item.action}"`,
                Math.round(item.confidence * 100) + '%',
                item.user
            ].join(','))
        ].join('\n');
        
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `weather-history-${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
        
        alert('✅ Data berhasil di-export!');
        
    } catch(e) {
        console.error('Export failed:', e);
        alert('❌ Export gagal: ' + e.message);
    }
}
</script>
