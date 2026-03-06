const $$ = (sel, ctx=document) => ctx.querySelector(sel);
const $$$ = (sel, ctx=document) => Array.from(ctx.querySelectorAll(sel));
window.currentWeatherData = null;

// TOAST NOTIFICATION SYSTEM
function showToast(message, type = 'success') {
	const container = document.getElementById('toast-container') || createToastContainer();
	const toast = document.createElement('div');
	
	const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
	const colors = {
		success: 'from-green-500 to-emerald-500',
		error: 'from-red-500 to-rose-500',
		info: 'from-blue-500 to-cyan-500',
		warning: 'from-yellow-500 to-orange-500'
	};
	
	toast.className = `transform translate-x-full transition-all duration-500 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 min-w-[300px] border border-white/20`;
	toast.innerHTML = `<span class="text-2xl">${icons[type]}</span><span class="font-medium">${message}</span>`;
	
	container.appendChild(toast);
	setTimeout(() => toast.classList.replace('translate-x-full', 'translate-x-0'), 100);
	setTimeout(() => {
		toast.classList.replace('translate-x-0', 'translate-x-full');
		setTimeout(() => toast.remove(), 500);
	}, 3000);
}

function createToastContainer() {
	const container = document.createElement('div');
	container.id = 'toast-container';
	container.className = 'fixed top-4 right-4 z-50 space-y-2';
	document.body.appendChild(container);
	return container;
}

async function api(path, opts={}){
	try {
		const res = await fetch(path, {headers:{'Content-Type':'application/json'}, credentials:'same-origin', ...opts});
		if(!res.ok) {
			const errorText = await res.text();
			throw new Error(`HTTP ${res.status}: ${errorText}`);
		}
		return await res.json();
	} catch(e) {
		console.error('API Error:', e);
		throw e;
	}
}

// SAVE SNAPSHOT FUNCTIONS
async function saveSnapshot() {
	if (!window.currentWeatherData) {
		showToast('Tidak ada data untuk disimpan', 'error');
		return;
	}
	
	const btn = $$('#save-snapshot-btn');
	if (!btn) return;
	
	const originalHTML = btn.innerHTML;
	
	try {
		btn.disabled = true;
		btn.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-xl animate-spin">refresh</span><span>Saving...</span></div>';
		
		console.log('📤 Saving snapshot:', window.currentWeatherData);
		
		const response = await api('api/save_snapshot.php', {
			method: 'POST',
			body: JSON.stringify(window.currentWeatherData)
		});
		
		if (response.ok) {
			showToast('Snapshot berhasil disimpan! 🎉', 'success');
			btn.classList.add('animate-bounce');
			setTimeout(() => btn.classList.remove('animate-bounce'), 1000);
			console.log('✅ Snapshot saved');
		} else {
			throw new Error(response.error || 'Failed to save');
		}
	} catch(e) {
		console.error('❌ Save failed:', e);
		showToast('Gagal menyimpan: ' + e.message, 'error');
	} finally {
		btn.disabled = false;
		btn.innerHTML = originalHTML;
	}
}

function injectSaveButton() {
	const recoBox = $$('#reco-box');
	if (!recoBox) return;
	
	let header = recoBox.querySelector('.flex.items-center.justify-between');
	if (!header) {
		header = recoBox.querySelector('.flex.items-center');
		if (header) {
			header.classList.add('justify-between');
			header.classList.add('mb-8');
		}
	}
	
	if (!header) {
		console.warn('⚠️ Header not found in reco-box');
		return;
	}
	
	if ($$('#save-snapshot-btn')) return;
	
	const saveBtn = document.createElement('button');
	saveBtn.id = 'save-snapshot-btn';
	saveBtn.className = 'hidden group/save px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-green-500/25 transition-all duration-300 hover:scale-105 transform border border-green-400';
	saveBtn.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-xl">save</span><span>Save Snapshot</span></div>';
	saveBtn.onclick = saveSnapshot;
	
	header.appendChild(saveBtn);
	console.log('✅ Save button injected');
}

function toggleSaveButton(show) {
	const btn = $$('#save-snapshot-btn');
	if (!btn) {
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

// LEGACY ITEMS FUNCTIONS (sebelum Glassmorphism)
async function loadItems(){
	const data = await api('api/items.php');
	const tbody = $$('#items-tbody');
	if (!tbody) return;
	tbody.innerHTML = data.items.map(item => `
		<tr>
			<td>${item.id}</td>
			<td>${item.name}</td>
			<td>${item.category}</td>
			<td><span class="badge">${item.location}</span></td>
			<td class="row-actions">
				<button class="btn secondary" data-edit="${item.id}">Edit</button>
				<button class="btn danger" data-delete="${item.id}">Hapus</button>
			</td>
		</tr>
	`).join('');

	$$$('button[data-delete]').forEach(btn => btn.addEventListener('click', async (e)=>{
		const id = e.currentTarget.getAttribute('data-delete');
		if(!confirm('Yakin hapus item?')) return;
		await api('api/items.php', {method:'POST', body: JSON.stringify({action:'delete', id})});
		loadItems();
	}));

	$$$('button[data-edit]').forEach(btn => btn.addEventListener('click', (e)=>{
		const id = e.currentTarget.getAttribute('data-edit');
		const row = e.currentTarget.closest('tr');
		const [_, nameCell, catCell] = row.children;
		$$('#item-id').value = id;
		$$('input[name="name"]').value = nameCell.textContent;
		$$('input[name="category"]').value = catCell.textContent;
		$$('#submit-btn').textContent = 'Update';
		window.scrollTo({top:0,behavior:'smooth'});
	}));
}

async function submitForm(e){
	e.preventDefault();
	const id = $$('#item-id').value || null;
	const name = $$('input[name="name"]').value.trim();
	const category = $$('input[name="category"]').value.trim();
	const location = $$('select[name="location"]').value;
	if(!name || !category) return alert('Isi semua field');
	const action = id ? 'update' : 'create';
	await api('api/items.php', {method:'POST', body: JSON.stringify({action, id, name, category, location})});
	($$('#item-form')).reset();
	$$('#item-id').value = '';
	$$('#submit-btn').textContent = 'Tambah';
	loadItems();
}

async function loadLocations(){
	const data = await api('api/locations.php');
	const select = $$('select[name="location"]');
	if (!select) return;
	select.innerHTML = data.locations.map(l=>`<option value="${l}">${l}</option>`).join('');
}

// WEATHER FUNCTIONS (with API fetch-ing)
async function refreshWeather(city){
	try{
		console.log('🌤️ Loading weather for:', city);
		
		const w = await api('api/weather.php?q=' + encodeURIComponent(city));
		const box = $$('#weather-box');
		
		window.currentWeatherData = {
			city: w.city,
			temperature: w.temperature,
			humidity: w.humidity || 0,
			wind: w.wind,
			action: '',
			confidence: 0
		};
		
		console.log('📊 Weather data stored:', window.currentWeatherData);
		
		box.innerHTML = `
			<div class="relative bg-slate-800/30 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 hover:border-blue-400/50 transition-all duration-500 shadow-2xl shadow-blue-900/20">
				<div class="flex items-center gap-3 mb-8">
					<div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
						<span class="material-symbols-outlined text-white">thermostat</span>
					</div>
					<h2 class="text-2xl font-bold text-white">Current Weather</h2>
				</div>
				<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
					<div class="text-center p-6 bg-gradient-to-br from-slate-800/80 to-slate-700/60 rounded-2xl border border-slate-600/50 shadow-lg hover:scale-105 transition-transform">
						<div class="text-slate-300 text-sm font-medium mb-3">📍 Location</div>
						<div class="text-white text-xl font-bold">${w.city}</div>
					</div>
					<div class="text-center p-6 bg-gradient-to-br from-orange-900/40 to-red-900/40 rounded-2xl border border-orange-600/50 shadow-lg hover:scale-105 transition-transform">
						<div class="text-orange-300 text-sm font-medium mb-3">🌡️ Temperature</div>
						<div class="text-white text-xl font-bold">${w.temperature?.toFixed ? w.temperature.toFixed(1) : w.temperature}°C</div>
					</div>
					<div class="text-center p-6 bg-gradient-to-br from-blue-900/40 to-cyan-900/40 rounded-2xl border border-blue-600/50 shadow-lg hover:scale-105 transition-transform">
						<div class="text-blue-300 text-sm font-medium mb-3">💧 Humidity</div>
						<div class="text-white text-xl font-bold">${w.humidity || 'N/A'}%</div>
					</div>
					<div class="text-center p-6 bg-gradient-to-br from-cyan-900/40 to-teal-900/40 rounded-2xl border border-cyan-600/50 shadow-lg hover:scale-105 transition-transform">
						<div class="text-cyan-300 text-sm font-medium mb-3">💨 Wind Speed</div>
						<div class="text-white text-xl font-bold">${w.wind} km/h</div>
					</div>
				</div>
			</div>`;
		
		const rec = await api('api/recommend.php?q=' + encodeURIComponent(city));
		const r = rec.recommendation;
		
		window.currentWeatherData.action = r.action;
		window.currentWeatherData.confidence = r.confidence;
		
		console.log('🤖 Recommendation stored:', r.action, r.confidence);
		
		const recoBox = $$('#reco-box');
		recoBox.innerHTML = `
			<div class="relative bg-slate-800/30 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 hover:border-purple-400/50 transition-all duration-500 shadow-2xl shadow-purple-900/20">
				<div class="flex items-center justify-between mb-8">
					<div class="flex items-center gap-4">
						<div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl">
							<span class="material-symbols-outlined text-white text-xl">psychology</span>
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
				</div>
				
				<div class="bg-gradient-to-br from-slate-800/80 to-slate-700/60 rounded-2xl p-6 border border-slate-600/50 shadow-lg">
					<div class="flex items-center justify-between mb-4">
						<div class="text-white text-2xl font-bold">${r.action}</div>
						<div class="px-5 py-2 bg-gradient-to-r from-green-500/20 to-blue-500/20 text-green-300 text-base font-bold rounded-full border border-green-400/40">
							${(r.confidence*100).toFixed(0)}% CONFIDENCE
						</div>
					</div>
					<div class="text-slate-300 text-sm mb-4 p-4 bg-slate-900/40 rounded-lg border-l-4 border-purple-400">
						<strong class="text-purple-300">🤖 Membership Analysis:</strong><br>
						Suhu ${formatTop(r.memberships.temp)}, Kelembaban ${formatTop(r.memberships.humidity)}, Angin ${formatTop(r.memberships.wind)}
					</div>
					
					<!-- Membership Details -->
					<div class="grid grid-cols-3 gap-4 mt-4">
						<div class="bg-slate-900/40 rounded-lg p-3 border border-orange-500/30">
							<div class="text-orange-300 text-xs font-bold mb-2">🌡️ Temperature</div>
							${Object.entries(r.memberships.temp || {}).map(([key, val]) => 
								`<div class="flex justify-between text-xs text-slate-300 mb-1">
									<span>${key}:</span>
									<span class="text-orange-400 font-medium">${(val*100).toFixed(0)}%</span>
								</div>`
							).join('')}
						</div>
						<div class="bg-slate-900/40 rounded-lg p-3 border border-blue-500/30">
							<div class="text-blue-300 text-xs font-bold mb-2">💧 Humidity</div>
							${Object.entries(r.memberships.humidity || {}).map(([key, val]) => 
								`<div class="flex justify-between text-xs text-slate-300 mb-1">
									<span>${key}:</span>
									<span class="text-blue-400 font-medium">${(val*100).toFixed(0)}%</span>
								</div>`
							).join('')}
						</div>
						<div class="bg-slate-900/40 rounded-lg p-3 border border-cyan-500/30">
							<div class="text-cyan-300 text-xs font-bold mb-2">💨 Wind</div>
							${Object.entries(r.memberships.wind || {}).map(([key, val]) => 
								`<div class="flex justify-between text-xs text-slate-300 mb-1">
									<span>${key}:</span>
									<span class="text-cyan-400 font-medium">${(val*100).toFixed(0)}%</span>
								</div>`
							).join('')}
						</div>
					</div>
				</div>
			</div>`;
		
		injectSaveButton();
		toggleSaveButton(true);
		
		showToast('Weather data loaded successfully!', 'success');
		
	}catch(e){
		console.error('❌ Weather load failed:', e);
		const recoBox = $$('#reco-box');
		recoBox.innerHTML = `
			<div class="bg-red-500/10 border border-red-500/50 rounded-xl p-6">
				<div class="flex items-center gap-3">
					<span class="material-symbols-outlined text-red-400 text-3xl">error</span>
					<div>
						<div class="text-red-400 font-bold text-lg">Failed to load weather</div>
						<div class="text-red-300 text-sm">${e.message}</div>
					</div>
				</div>
			</div>`;
		showToast('Failed to load weather: ' + e.message, 'error');
	}
}

function topLabel(obj){
	let bestK = '-'; let bestV = -1;
	for (const k in obj){ if (obj[k] > bestV){ bestV = obj[k]; bestK = k; } }
	return [bestK, bestV];
}

function formatTop(obj){
	const [k,v] = topLabel(obj);
	return `${k} (${(v*100).toFixed(0)}%)`;
}

async function initLocationSelect(){
	const sel = $$('#location-select');
	if (!sel) return;
	
	try{
		const locs = await api('api/locations.php');
		sel.innerHTML = '<option value="" style="background:#1e293b;color:#cbd5e1;">🌍 Choose your city...</option>';
		locs.locations.forEach(loc => {
			sel.innerHTML += `<option value="${loc}" style="background:#1e293b;color:#f1f5f9;">${loc}</option>`;
		});
		console.log('✅ Locations loaded:', locs.locations.length);
	} catch(e){
		console.error('Failed to load locations:', e);
		sel.innerHTML = '<option value="">Error loading cities</option>';
	}
}

function setPreset(temp, humidity, wind) {
	$$('input[name="t"]').value = temp;
	$$('input[name="h"]').value = humidity;
	$$('input[name="w"]').value = wind;
	showToast('Preset applied!', 'info');
}

// HISTORY SECTIONS
async function exportHistory() {
	try {
		const response = await fetch('data/snapshots.json');
		const data = await response.json();
		
		if (!data || data.length === 0) {
			showToast('No data to export', 'warning');
			return;
		}
		
		const headers = ['Date', 'Time', 'City', 'Temperature', 'Humidity', 'Wind Speed', 'Recommendation', 'Confidence', 'User'];
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
		
		showToast('History exported successfully!', 'success');
	} catch(e) {
		console.error('Export failed:', e);
		showToast('Export failed: ' + e.message, 'error');
	}
}

async function clearHistory() {
	if (!confirm('Are you sure? This cannot be undone!')) return;
	
	try {
		const response = await api('api/clear_history.php', { method: 'POST' });
		
		if (response.ok) {
			showToast('History cleared successfully!', 'success');
			setTimeout(() => window.location.reload(), 1500);
		} else {
			throw new Error(response.message || 'Unknown error');
		}
	} catch(e) {
		console.error('Clear failed:', e);
		showToast('Clear failed: ' + e.message, 'error');
	}
}

document.addEventListener('DOMContentLoaded', ()=>{
	console.log('🚀 App initializing...');
	createToastContainer();
	injectSaveButton();
	
	const refreshBtn = $$('#refresh-btn');
	if (refreshBtn) {
		refreshBtn.addEventListener('click', async (e)=>{
			e.preventDefault();
			const sel = $$('#location-select');
			
			if (sel && sel.value) {
				refreshBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span><span>Refreshing...</span>';
				refreshBtn.disabled = true;
				
				try {
					await refreshWeather(sel.value);
				} catch(e) {
					showToast('Refresh failed: ' + e.message, 'error');
				} finally {
					refreshBtn.innerHTML = '<div class="flex items-center gap-3"><span class="material-symbols-outlined group-hover:rotate-180 transition-transform duration-500">refresh</span><span>Refresh Data</span></div>';
					refreshBtn.disabled = false;
				}
			} else {
				showToast('Please select a location first', 'warning');
			}
		});
	}

	loadLocations().then(()=> loadItems()).catch(e => console.error('Failed to load:', e));
	const sel = $$('#location-select');
	if (sel) {
		sel.addEventListener('change', ()=>{
			if(sel.value) refreshWeather(sel.value);
		});
	}

	const manual = $$('#manual-form');
	if (manual){
		manual.addEventListener('submit', async (e)=>{
			e.preventDefault();
			const t = parseFloat($$('input[name="t"]').value);
			const h = parseFloat($$('input[name="h"]').value);
			const w = parseFloat($$('input[name="w"]').value);
			
			if(isNaN(t) || isNaN(h) || isNaN(w)) {
				showToast('Please enter valid numbers', 'error');
				return;
			}
			
			window.currentWeatherData = {
				city: 'Manual Input',
				temperature: t,
				humidity: h,
				wind: w,
				action: '',
				confidence: 0
			};
			
			const resultBox = $$('#manual-result');
			resultBox.innerHTML = `
				<div class="flex items-center justify-center py-8">
					<div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
					<span class="ml-3 text-slate-400">Analyzing...</span>
				</div>
			`;
			
			try{
				const rec = await api('api/recommend.php', {method:'POST', body: JSON.stringify({temperature:t, humidity:h, wind:w})});
				const r = rec.recommendation;
				
				window.currentWeatherData.action = r.action;
				window.currentWeatherData.confidence = r.confidence;
				
				resultBox.innerHTML = `
					<div class="flex items-center gap-3 mb-6">
						<div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-500 rounded-lg flex items-center justify-center">
							<span class="material-symbols-outlined text-white text-sm">check_circle</span>
						</div>
						<h3 class="text-lg font-bold text-slate-100">Analysis Complete</h3>
					</div>
					<div class="bg-gradient-to-br from-slate-800/80 to-slate-700/60 rounded-xl p-5 border border-slate-600/50 shadow-lg">
						<div class="flex items-center justify-between mb-4">
							<div class="text-slate-100 text-xl font-bold">${r.action}</div>
							<div class="px-4 py-2 bg-gradient-to-r from-green-500/20 to-blue-500/20 text-green-300 text-sm font-bold rounded-full border border-green-400/40">
								${Math.round(r.confidence * 100)}% CONFIDENCE
							</div>
						</div>
						<div class="text-slate-300 text-sm mb-5 p-3 bg-slate-900/40 rounded-lg border-l-4 border-blue-400">
							<strong class="text-blue-300">🤖 AI Analysis:</strong> Based on fuzzy logic calculations
						</div>
						<div class="grid grid-cols-3 gap-3">
							<div class="bg-gradient-to-br from-slate-700/60 to-slate-600/40 rounded-lg p-4 text-center border border-slate-600/30">
								<div class="text-slate-300 text-xs mb-2 font-medium">🌡️ Temperature</div>
								<div class="text-slate-100 text-lg font-bold">${t}°C</div>
							</div>
							<div class="bg-gradient-to-br from-slate-700/60 to-slate-600/40 rounded-lg p-4 text-center border border-slate-600/30">
								<div class="text-slate-300 text-xs mb-2 font-medium">💧 Humidity</div>
								<div class="text-slate-100 text-lg font-bold">${h}%</div>
							</div>
							<div class="bg-gradient-to-br from-slate-700/60 to-slate-600/40 rounded-lg p-4 text-center border border-slate-600/30">
								<div class="text-slate-300 text-xs mb-2 font-medium">💨 Wind Speed</div>
								<div class="text-slate-100 text-lg font-bold">${w} km/h</div>
							</div>
						</div>
					</div>
				`;
				
				injectSaveButton();
				toggleSaveButton(true);
				
				showToast('Analysis complete!', 'success');
				
			} catch(e) {
				console.error('Analysis failed:', e);
				resultBox.innerHTML = `
					<div class="bg-red-500/10 border border-red-500/50 rounded-xl p-4">
						<div class="flex items-center gap-2">
							<span class="material-symbols-outlined text-red-400">error</span>
							<p class="text-red-400 text-sm">Analysis failed: ${e.message}</p>
						</div>
					</div>
				`;
				showToast('Analysis failed: ' + e.message, 'error');
			}
		});
	}
	
	console.log('✅ App initialized');
});

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initLocationSelect);
} else {
	initLocationSelect();
}