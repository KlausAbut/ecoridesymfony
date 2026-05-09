import './bootstrap';
import './styles/app.css';

document.addEventListener('DOMContentLoaded', function () {
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

    // Recherche AJAX
    const form = document.querySelector('#formRecherche');
    const spinner = document.querySelector('#loadingSpinner');
    const container = document.querySelector('#resultatsTrajets');

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!spinner || !container) return;

        const depart  = document.querySelector('#depart')?.value.trim();
        const arrivee = document.querySelector('#arrivee')?.value.trim();
        const date    = document.querySelector('#date')?.value;
        const prix    = document.querySelector('#prix')?.value;
        const energie = document.querySelector('#energie')?.value;

        // Au moins un critère requis
        if (!depart && !arrivee && !date && !prix && !energie) {
            container.innerHTML = `<p class="text-muted text-center">Entrez au moins un critère de recherche.</p>`;
            return;
        }

        spinner.classList.remove('d-none');
        container.innerHTML = '';

        const params = new URLSearchParams();
        if (depart)  params.set('depart', depart);
        if (arrivee) params.set('arrivee', arrivee);
        if (date)    params.set('date', date);
        if (prix)    params.set('prix', prix);
        if (energie) params.set('energie', energie);

        try {
            const response = await fetch(`${window.routes.recherche}?${params}`);
            const trajets  = await response.json();

            spinner.classList.add('d-none');
            container.innerHTML = '';

            if (!Array.isArray(trajets) || trajets.length === 0) {
                container.innerHTML = `
                  <div class="text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--eco-border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:0.8rem;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <p class="text-muted mb-0">Aucun trajet trouvé pour ces critères.</p>
                  </div>`;
                return;
            }

            const grid = document.createElement('div');
            grid.className = 'row g-3 mt-1';

            trajets.forEach(t => {
                const col = document.createElement('div');
                col.className = 'col-md-6 col-lg-4';
                col.innerHTML = `
                  <a href="/covoiturage/show/${t.id}" class="text-decoration-none">
                    <div class="card p-3 h-100 hover-shadow-sm">
                      <div class="fw-semibold mb-2" style="color:var(--eco-text);">
                        ${t.lieuDepart} <span style="color:var(--eco-secondary);">→</span> ${t.lieuArrivee}
                      </div>
                      <div class="text-muted small mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        ${t.date} à ${t.heure}
                      </div>
                      <div class="text-muted small mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <strong style="color:var(--eco-secondary);">${t.prix} €</strong> / personne
                      </div>
                      <div class="text-muted small mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        ${t.conducteur}
                      </div>
                      ${t.ecologique ? `<span class="badge-eco" style="font-size:0.72rem;display:inline-flex;align-items:center;gap:4px;"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="var(--eco-primary)" stroke="var(--eco-primary)" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>Écologique</span>` : ''}
                    </div>
                  </a>`;
                grid.appendChild(col);
            });

            container.appendChild(grid);

        } catch (err) {
            spinner.classList.add('d-none');
            container.innerHTML = `<p class="text-danger text-center">Erreur lors de la recherche.</p>`;
        }
    });

    // Carousel avis
    const carousel = document.querySelector('#avisCarousel');
    if (carousel) {
        const items = carousel.querySelectorAll('.carousel-item');
        let current = 0;

        const showSlide = (index) => {
            items.forEach((item, i) => {
                item.classList.remove('active');
                if (i === index) item.classList.add('active');
            });
        };

        setInterval(() => {
            current = (current + 1) % items.length;
            showSlide(current);
        }, 3000);
    }

   // Burger menu toggle
        const burger = document.getElementById('burger');
        const menu = document.getElementById('menu');
        if (burger && menu) {
            burger.addEventListener('click', () => {
                menu.classList.toggle('show');
            });
        }



    console.log('✅ JS actif');
});
