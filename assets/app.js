import './bootstrap.js';
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

        spinner.classList.remove('d-none');
        container.innerHTML = '';

        const depart = document.querySelector('#depart').value;
        const arrivee = document.querySelector('#arrivee').value;
        const date = document.querySelector('#date').value;

        try {
            const response = await fetch(`/ajax/recherche?depart=${depart}&arrivee=${arrivee}&date=${date}`);
            const trajets = await response.json();

            spinner.classList.add('d-none');
            container.innerHTML = '';

            if (trajets.length === 0 || trajets.error) {
                container.innerHTML = `<p class="text-danger">Aucun trajet trouvé</p>`;
                return;
            }

            container.innerHTML += `<h3 class="text-success">🚗 Trajets trouvés</h3>`;
            trajets.forEach(t => {
                container.innerHTML += `
                    <div class="card mb-2 p-3">
                        <strong>${t.lieuDepart} → ${t.lieuArrivee}</strong><br>
                        🗓️ ${t.date} à ${t.heure}<br>
                        👤 Conducteur : ${t.conducteur}
                    </div>`;
            });
        } catch (err) {
            spinner.classList.add('d-none');
            container.innerHTML = `<p class="text-danger">Erreur lors de la recherche</p>`;
        }
    });

    // Carousel automatique
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

    console.log('✅ JS actif');
});
