import './bootstrap';
import './styles/app.css';

// ── Toast ──────────────────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const id = 'toast-' + Date.now();
    const icon = type === 'success'
        ? `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>`;
    const toast = document.createElement('div');
    toast.id = id;
    toast.className = `eco-toast eco-toast--${type}`;
    toast.innerHTML = `<span class="eco-toast__icon">${icon}</span><span>${message}</span><button onclick="document.getElementById('${id}').remove()" class="eco-toast__close">×</button>`;
    container.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('eco-toast--show'));
    setTimeout(() => { toast.classList.remove('eco-toast--show'); setTimeout(() => toast.remove(), 350); }, 4000);
}
window.showToast = showToast;

// ── Skeleton cards ─────────────────────────────────────────────────────────
function skeletonCards(n = 6) {
    return Array.from({ length: n }, () => `
      <div class="col-md-6 col-lg-4">
        <div class="card p-3 h-100">
          <div class="skeleton skeleton-line" style="width:70%;height:18px;margin-bottom:12px;"></div>
          <div class="skeleton skeleton-line" style="width:50%;height:13px;margin-bottom:8px;"></div>
          <div class="skeleton skeleton-line" style="width:40%;height:13px;margin-bottom:8px;"></div>
          <div class="skeleton skeleton-line" style="width:55%;height:13px;"></div>
        </div>
      </div>`).join('');
}

// ── Build result card ──────────────────────────────────────────────────────
function buildCard(t) {
    const stars = Array.from({ length: 5 }, (_, i) =>
        `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="${i < Math.round(t.note) ? '#f59e0b' : 'none'}" stroke="#f59e0b" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`
    ).join('');
    const placesColor = t.nbPlace <= 2 ? '#ef4444' : t.nbPlace <= 5 ? '#f59e0b' : 'var(--eco-secondary)';
    const shareUrl = `${window.location.origin}/covoiturage/show/${t.id}`;

    return `
      <div class="col-md-6 col-lg-4" data-prix="${t.prix}" data-note="${t.note}" data-date="${t.dateRaw || 0}">
        <div class="card p-3 h-100 search-card">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="fw-semibold" style="color:var(--eco-text);font-size:1rem;">
              ${t.lieuDepart} <span style="color:var(--eco-secondary);">→</span> ${t.lieuArrivee}
            </div>
            <button class="btn-share-card" title="Copier le lien" onclick="copyLink('${shareUrl}',event)">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/></svg>
            </button>
          </div>
          <div class="text-muted small mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            ${t.date} à ${t.heure}
          </div>
          <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="text-muted small">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              ${t.conducteur}
            </div>
            <div style="display:flex;align-items:center;gap:2px;">${stars}<span class="text-muted" style="font-size:0.72rem;margin-left:3px;">${t.note > 0 ? t.note : '—'}</span></div>
          </div>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="text-muted small">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              <strong style="color:var(--eco-secondary);">${t.prix} €</strong> / pers.
            </div>
            <span style="font-size:0.72rem;font-weight:600;color:${placesColor};">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:2px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              ${t.nbPlace} place${t.nbPlace > 1 ? 's' : ''}
            </span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            ${t.ecologique ? `<span class="badge-eco" style="font-size:0.7rem;display:inline-flex;align-items:center;gap:3px;"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="var(--eco-primary)" stroke="var(--eco-primary)" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>Écologique</span>` : ''}
            <span style="font-size:0.7rem;color:#6b7280;display:inline-flex;align-items:center;gap:3px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22 16 8"/><path d="M3.47 12.53 5 11l1.53 1.53a3.5 3.5 0 0 1 0 4.94L5 19l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z"/><path d="M7.47 8.53 9 7l1.53 1.53a3.5 3.5 0 0 1 0 4.94L9 15l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z"/><path d="M11.47 4.53 13 3l1.53 1.53a3.5 3.5 0 0 1 0 4.94L13 11l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z"/></svg>
              ~2.1 kg CO₂ économisé
            </span>
          </div>
          <a href="/covoiturage/show/${t.id}" class="stretched-link"></a>
        </div>
      </div>`;
}

// ── Copy link ──────────────────────────────────────────────────────────────
window.copyLink = function(url, e) {
    e.preventDefault();
    e.stopPropagation();
    navigator.clipboard.writeText(url).then(() => showToast('Lien copié !'));
};

// ── Sort results ───────────────────────────────────────────────────────────
function sortResults(container, key) {
    const cols = [...container.querySelectorAll('[data-prix]')];
    if (!cols.length) return;
    cols.sort((a, b) => {
        if (key === 'prix') return parseFloat(a.dataset.prix) - parseFloat(b.dataset.prix);
        if (key === 'note') return parseFloat(b.dataset.note) - parseFloat(a.dataset.note);
        return parseFloat(a.dataset.date) - parseFloat(b.dataset.date);
    });
    const grid = cols[0].parentNode;
    cols.forEach(c => grid.appendChild(c));
    document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.toggle('sort-btn--active', btn.dataset.sort === key));
}

// ── Recent searches ────────────────────────────────────────────────────────
const SEARCHES_KEY = 'ecoride_recent';
function saveSearch(params) {
    const label = [params.get('depart'), params.get('arrivee')].filter(Boolean).join(' → ') || params.get('energie') || params.get('prix') + '€ max';
    const searches = JSON.parse(localStorage.getItem(SEARCHES_KEY) || '[]');
    const filtered = searches.filter(s => s.label !== label);
    filtered.unshift({ label, params: params.toString() });
    localStorage.setItem(SEARCHES_KEY, JSON.stringify(filtered.slice(0, 5)));
    renderRecentSearches();
}
function renderRecentSearches() {
    const wrap = document.getElementById('recentSearches');
    if (!wrap) return;
    const searches = JSON.parse(localStorage.getItem(SEARCHES_KEY) || '[]');
    if (!searches.length) { wrap.innerHTML = ''; return; }
    wrap.innerHTML = `<div class="d-flex flex-wrap gap-2 align-items-center mt-3">
      <span class="text-muted small">Récentes :</span>
      ${searches.map(s => `<button class="recent-chip" data-params="${s.params}">${s.label}</button>`).join('')}
    </div>`;
    wrap.querySelectorAll('.recent-chip').forEach(btn => {
        btn.addEventListener('click', () => {
            const p = new URLSearchParams(btn.dataset.params);
            if (p.get('depart'))  document.getElementById('depart').value  = p.get('depart')  || '';
            if (p.get('arrivee')) document.getElementById('arrivee').value = p.get('arrivee') || '';
            if (p.get('date'))    document.getElementById('date').value    = p.get('date')    || '';
            if (p.get('prix'))    document.getElementById('prix').value    = p.get('prix')    || '';
            if (p.get('energie')) document.getElementById('energie').value = p.get('energie') || '';
            document.getElementById('formRecherche')?.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {

    // Flash messages → toasts
    document.querySelectorAll('.flash-toast').forEach(el => {
        showToast(el.dataset.message, el.dataset.type || 'success');
    });

    // Dropdown
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownContent = document.getElementById('dropdownContent');
    dropdownButton?.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownContent?.classList.toggle('show');
    });
    document.addEventListener('click', function () {
        dropdownContent?.classList.remove('show');
    });

    // Recent searches on load
    renderRecentSearches();

    // Sort bar click
    document.addEventListener('click', function (e) {
        if (e.target.closest('.sort-btn')) {
            const btn = e.target.closest('.sort-btn');
            const resultatsTrajets = document.getElementById('resultatsTrajets');
            if (resultatsTrajets) sortResults(resultatsTrajets, btn.dataset.sort);
        }
    });

    // ── Recherche AJAX ───────────────────────────────────────────────────
    const form      = document.querySelector('#formRecherche');
    const spinner   = document.querySelector('#loadingSpinner');
    const container = document.querySelector('#resultatsTrajets');

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!spinner || !container) return;

        const depart  = document.querySelector('#depart')?.value.trim();
        const arrivee = document.querySelector('#arrivee')?.value.trim();
        const date    = document.querySelector('#date')?.value;
        const prix    = document.querySelector('#prix')?.value;
        const energie = document.querySelector('#energie')?.value;
        const places  = document.querySelector('#places')?.value;

        if (!depart && !arrivee && !date && !prix && !energie && !places) {
            container.innerHTML = `<p class="text-muted text-center">Entrez au moins un critère de recherche.</p>`;
            return;
        }

        // Skeleton loader
        spinner.classList.add('d-none');
        container.innerHTML = `<div class="row g-3 mt-1">${skeletonCards()}</div>`;

        const params = new URLSearchParams();
        if (depart)  params.set('depart', depart);
        if (arrivee) params.set('arrivee', arrivee);
        if (date)    params.set('date', date);
        if (prix)    params.set('prix', prix);
        if (energie) params.set('energie', energie);
        if (places)  params.set('places', places);

        saveSearch(params);

        try {
            const response = await fetch(`${window.routes.recherche}?${params}`);
            const trajets  = await response.json();

            container.innerHTML = '';

            if (!Array.isArray(trajets) || trajets.length === 0) {
                container.innerHTML = `
                  <div class="text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--eco-border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:0.8rem;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <p class="text-muted mb-0">Aucun trajet trouvé pour ces critères.</p>
                  </div>`;
                return;
            }

            // Sort bar
            const sortBar = document.createElement('div');
            sortBar.className = 'd-flex align-items-center gap-2 mb-3 flex-wrap';
            sortBar.innerHTML = `
              <span class="text-muted small">Trier :</span>
              <button class="sort-btn sort-btn--active" data-sort="date">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                Date
              </button>
              <button class="sort-btn" data-sort="prix">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Prix ↑
              </button>
              <button class="sort-btn" data-sort="note">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Note ↓
              </button>
              <span class="text-muted small ms-auto">${trajets.length} trajet${trajets.length > 1 ? 's' : ''} trouvé${trajets.length > 1 ? 's' : ''}</span>`;
            container.appendChild(sortBar);

            const grid = document.createElement('div');
            grid.className = 'row g-3';

            trajets.forEach(t => {
                const tmp = document.createElement('div');
                tmp.innerHTML = buildCard(t);
                grid.appendChild(tmp.firstElementChild);
            });

            container.appendChild(grid);

        } catch (err) {
            container.innerHTML = `<p class="text-danger text-center">Erreur lors de la recherche.</p>`;
        }
    });

    // Burger menu toggle
    const burger = document.getElementById('burger');
    const menu   = document.getElementById('menu');
    if (burger && menu) {
        burger.addEventListener('click', () => menu.classList.toggle('show'));
    }

    // ── Chatbot ──────────────────────────────────────────────────────────
    const chatToggle  = document.getElementById('chatToggle');
    const chatWindow  = document.getElementById('chatWindow');
    const chatClose   = document.getElementById('chatClose');
    const chatForm    = document.getElementById('chatForm');
    const chatInput   = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');

    chatToggle?.addEventListener('click', () => {
        chatWindow?.classList.toggle('chat--open');
        chatToggle?.classList.toggle('chat-toggle--open');
        if (chatWindow?.classList.contains('chat--open') && chatMessages?.children.length === 0) {
            appendBotMessage('Bonjour ! 👋 Je suis l\'assistant EcoRide. Comment puis-je vous aider ?');
        }
    });
    chatClose?.addEventListener('click', () => {
        chatWindow?.classList.remove('chat--open');
        chatToggle?.classList.remove('chat-toggle--open');
    });

    chatForm?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const msg = chatInput.value.trim();
        if (!msg) return;
        chatInput.value = '';

        appendUserMessage(msg);
        const typing = appendBotMessage('…', true);

        try {
            const res = await fetch('/chatbot', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            typing.remove();
            appendBotMessage(data.reply || 'Désolé, une erreur est survenue.');
        } catch {
            typing.remove();
            appendBotMessage('Impossible de contacter le serveur.');
        }
    });

    function appendUserMessage(text) {
        const div = document.createElement('div');
        div.className = 'chat-msg chat-msg--user';
        div.textContent = text;
        chatMessages?.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    function appendBotMessage(text, isTyping = false) {
        const div = document.createElement('div');
        div.className = 'chat-msg chat-msg--bot' + (isTyping ? ' chat-msg--typing' : '');
        div.textContent = text;
        chatMessages?.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return div;
    }

    console.log('✅ JS actif');
});
