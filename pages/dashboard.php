<div class="space-y-8">
    <div class="relative mb-8">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-600/10 via-slate-500/10 to-slate-400/10 rounded-3xl blur-xl"></div>
		<!-- Header -->
        <div class="relative bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-3xl p-8">
            <div class="flex flex-wrap items-center justify-between gap-6">
                <div class="space-y-3">
                    <h1 class="text-5xl font-black text-white flex items-center gap-4">
                        <span class="text-6xl animate-bounce">🌤️</span>
                        Weather Intelligence
                    </h1>
                    <p class="text-slate-300 flex items-center gap-3 text-lg">
                        <span class="material-symbols-outlined text-xl text-slate-400">schedule</span>
                        <?php echo date("l, F j, Y • g:i A"); ?>
                        <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-sm font-medium rounded-full border border-slate-600/50 ml-4">
                            AI POWERED
                        </span>
                    </p>
                </div>
                
                <button id="refresh-btn" class="group relative overflow-hidden px-8 py-4 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-2xl hover:shadow-lg transition-all duration-300 border border-slate-600">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined group-hover:rotate-180 transition-transform duration-500">refresh</span>
                        <span>Refresh Data</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 space-y-8">
            <div class="mb-8">
                <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-600/50 rounded-2xl p-6 hover:border-blue-400/50 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-white">location_on</span>
                        </div>
                        <h3 class="text-xl font-bold text-white">Select Location</h3>
                    </div>
                    
                    <div class="relative">
                        <select id="location-select" class="w-full bg-slate-900/80 border-2 border-slate-600/50 text-white rounded-xl px-4 py-4 text-lg font-medium focus:border-blue-400 focus:ring-4 focus:ring-blue-400/20 transition-all duration-300 appearance-none cursor-pointer">
                            <option value="" class="bg-slate-800">🌍 Choose your city...</option>
                        </select>
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="weather-box" class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-cyan-600/20 to-teal-600/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-500"></div>
                
                <div class="relative bg-slate-800/30 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 hover:border-blue-400/50 transition-all duration-500 shadow-2xl shadow-blue-900/20">
                    <div class="text-center text-slate-300 py-12">
                        <div class="text-8xl mb-6 animate-pulse">🌍</div>
                        <h2 class="text-2xl font-bold text-white mb-2">Select a City</h2>
                        <p class="text-slate-400">Choose a location to see current weather conditions</p>
                    </div>
                </div>
            </div>

            <!-- AI Recommendations Card -->
            <div id="reco-box" class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-pink-600/20 to-rose-600/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-500"></div>
                
                <div class="relative bg-slate-800/30 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 hover:border-purple-400/50 transition-all duration-500 shadow-2xl shadow-purple-900/20">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl">
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">AI Recommendations</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="px-3 py-1 bg-purple-500/20 text-purple-300 text-xs font-bold rounded-full border border-purple-400/30">
                                    FUZZY LOGIC
                                </div>
                                <div class="px-3 py-1 bg-pink-500/20 text-pink-300 text-xs font-bold rounded-full border border-pink-400/30">
                                    REAL-TIME
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center text-slate-300 py-8">
                        <div class="relative">
                            <span class="material-symbols-outlined text-6xl mb-4 block animate-pulse text-purple-400">auto_awesome</span>
                            <div class="absolute inset-0 bg-purple-400/20 rounded-full blur-xl"></div>
                        </div>
                        <p class="text-lg text-slate-300">AI is ready to analyze weather patterns...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="xl:col-span-1 space-y-8">
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 via-teal-600/20 to-cyan-600/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-500"></div>
                
                <div class="relative bg-slate-800/30 backdrop-blur-2xl border border-white/10 rounded-3xl p-6 hover:border-emerald-400/50 transition-all duration-500 shadow-2xl shadow-emerald-900/20">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-white">tune</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Manual Input</h3>
                            <p class="text-emerald-300 text-sm">Custom Analysis</p>
                        </div>
                    </div>
                    
                    <form id="manual-form" class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-slate-300 text-sm font-medium flex items-center gap-2">
                                <span class="text-orange-400">🌡️</span>
                                Temperature (°C)
                            </label>
                            <input name="t" type="number" step="0.1" placeholder="25.0" 
                                   class="w-full bg-slate-900/80 border-2 border-slate-600/50 text-white rounded-xl px-4 py-3 focus:border-orange-400 focus:ring-4 focus:ring-orange-400/20 transition-all duration-300">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-slate-300 text-sm font-medium flex items-center gap-2">
                                <span class="text-blue-400">💧</span>
                                Humidity (%)
                            </label>
                            <input name="h" type="number" step="0.1" placeholder="60.0" 
                                   class="w-full bg-slate-900/80 border-2 border-slate-600/50 text-white rounded-xl px-4 py-3 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/20 transition-all duration-300">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-slate-300 text-sm font-medium flex items-center gap-2">
                                <span class="text-cyan-400">💨</span>
                                Wind Speed (km/h)
                            </label>
                            <input name="w" type="number" step="0.1" placeholder="10.0" 
                                   class="w-full bg-slate-900/80 border-2 border-slate-600/50 text-white rounded-xl px-4 py-3 focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-300">
                        </div>
                        
                        <!-- Quick Presets -->
                        <div class="space-y-2">
                            <label class="text-slate-300 text-sm font-medium">Quick Presets</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="setPreset(30, 70, 5)" 
                                        class="px-3 py-2 bg-orange-500/20 text-orange-300 text-xs rounded-lg border border-orange-400/30 hover:bg-orange-500/30 transition-all">
                                    🔥 Hot Day
                                </button>
                                <button type="button" onclick="setPreset(15, 40, 15)" 
                                        class="px-3 py-2 bg-blue-500/20 text-blue-300 text-xs rounded-lg border border-blue-400/30 hover:bg-blue-500/30 transition-all">
                                    ❄️ Cool Day
                                </button>
                                <button type="button" onclick="setPreset(25, 80, 3)" 
                                        class="px-3 py-2 bg-green-500/20 text-green-300 text-xs rounded-lg border border-green-400/30 hover:bg-green-500/30 transition-all">
                                    🌿 Humid
                                </button>
                                <button type="button" onclick="setPreset(20, 30, 25)" 
                                        class="px-3 py-2 bg-purple-500/20 text-purple-300 text-xs rounded-lg border border-purple-400/30 hover:bg-purple-500/30 transition-all">
                                    💨 Windy
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-emerald-500/25 transition-all duration-300 hover:scale-105 transform">
                            <span class="material-symbols-outlined mr-2">analytics</span>
                            Analyze Weather
                        </button>
                    </form>
                    
                    <!-- Results Container -->
                    <div id="manual-result" class="mt-6">
					<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
window.currentWeatherData = null;

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    const icons = { 
        success: '✅', 
        error: '❌', 
        info: 'ℹ️', 
        warning: '⚠️' 
    };
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-rose-500',
        info: 'from-blue-500 to-cyan-500',
        warning: 'from-yellow-500 to-orange-500'
    };
    
    toast.className = `transform translate-x-full transition-all duration-500 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 min-w-[300px] border border-white/20`;
    toast.innerHTML = `
        <span class="text-2xl">${icons[type]}</span>
        <span class="font-medium">${message}</span>
    `;
    
    container.appendChild(toast);
    setTimeout(() => toast.classList.replace('translate-x-full', 'translate-x-0'), 100);
    setTimeout(() => {
        toast.classList.replace('translate-x-0', 'translate-x-full');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

async function saveSnapshot() {
    if (!window.currentWeatherData) {
        showToast('Tidak ada data untuk disimpan', 'error');
        return;
    }
    
    const btn = document.getElementById('save-snapshot-btn');
    if (!btn) return;
    
    const originalHTML = btn.innerHTML;
    
    try {
        btn.disabled = true;
        btn.innerHTML = `
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-xl animate-spin">refresh</span>
                <span>Saving...</span>
            </div>
        `;
        
        const response = await fetch('api/save_snapshot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify(window.currentWeatherData)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.ok) {
            showToast('Snapshot berhasil disimpan! 🎉', 'success');
            btn.classList.add('animate-bounce');
            setTimeout(() => btn.classList.remove('animate-bounce'), 1000);
        } else {
            throw new Error(result.error || 'Gagal menyimpan');
        }
    } catch(e) {
        console.error('Save failed:', e);
        showToast('Gagal: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

function injectSaveButton() {
    const recoBox = document.getElementById('reco-box');
    if (!recoBox) {
        console.log('⚠️ Reco box not found');
        return;
    }
    
    let header = recoBox.querySelector('.flex.items-center.justify-between');
    if (!header) {
        header = recoBox.querySelector('.flex.items-center');
        if (header) {
            header.classList.add('justify-between');
            header.classList.add('mb-8');
        }
    }
    
    if (!header) {
        console.log('⚠️ Header not found in reco box');
        return;
    }
    
    if (document.getElementById('save-snapshot-btn')) {
        console.log('ℹ️ Save button already exists');
        return;
    }
    
    const saveBtn = document.createElement('button');
    saveBtn.id = 'save-snapshot-btn';
    saveBtn.className = 'hidden group/save px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-green-500/25 transition-all duration-300 hover:scale-105 transform border border-green-400';
    saveBtn.innerHTML = `
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-xl">save</span>
            <span>Save Snapshot</span>
        </div>
    `;
    saveBtn.onclick = saveSnapshot;
    
    header.appendChild(saveBtn);
    console.log('✅ Save button injected');
}

function toggleSaveButton(show) {
    const btn = document.getElementById('save-snapshot-btn');
    if (!btn) {
        console.log('⚠️ Button not found, injecting...');
        injectSaveButton();
        setTimeout(() => toggleSaveButton(show), 100);
        return;
    }
    
    if (show) {
        btn.classList.remove('hidden');
        btn.style.opacity = '0';
        btn.style.transform = 'scale(0.8)';
        setTimeout(() => {
            btn.style.transition = 'all 0.3s ease';
            btn.style.opacity = '1';
            btn.style.transform = 'scale(1)';
        }, 100);
    } else {
        btn.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing Save Snapshot feature...');
    injectSaveButton();
    
    const observer = new MutationObserver(() => {
        if (!document.getElementById('save-snapshot-btn')) {
            injectSaveButton();
        }
    });
    
    const recoBox = document.getElementById('reco-box');
    if (recoBox) {
        observer.observe(recoBox, { childList: true, subtree: true });
    }
});

setTimeout(() => {
    if (!document.getElementById('save-snapshot-btn')) {
        injectSaveButton();
    }
}, 1000);
</script>
                    </div>
                </div>