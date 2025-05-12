import './bootstrap.js';
// PAS besoin d'importer bootstrap.js ici (inutile ici car on gère le dropdown nous-mêmes)

import './styles/app.css';

document.addEventListener('DOMContentLoaded', function () {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownContent = document.getElementById('dropdownContent');

    dropdownButton.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownContent.classList.toggle('show');
    });

    document.addEventListener('click', function () {
        dropdownContent.classList.remove('show');
    });

    console.log('✅ Dropdown JS custom activé');
});

const form = document.querySelector('#formRecherche');
const spinner = document.querySelector('#loadingSpinner'); // <--- id du spinner HTML

form?.addEventListener('submit', async function (e) {
    e.preventDefault();

    spinner.style.display = 'block';
    const container = document.getElementById('resultatsTrajets');
    container.innerHTML = '';

    const depart = document.querySelector('#depart').value;
    const arrivee = document.querySelector('#arrivee').value;
    const date = document.querySelector('#date').value;

    try {
        const response = await fetch(`/ajax/recherche?depart=${depart}&arrivee=${arrivee}&date=${date}`);
        const trajets = await response.json();

        spinner.style.display = 'none';

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
        spinner.style.display = 'none';
        container.innerHTML = `<p class="text-danger">Erreur lors de la recherche</p>`;
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('#avisCarousel');
    if (carousel) {
        const items = carousel.querySelectorAll('.carousel-item');
        let current = 0;

        const showSlide = (index) => {
            items.forEach((item, i) => {
                item.classList.remove('active');
                if (i === index) {
                    item.classList.add('active');
                }
            });
        };

        setInterval(() => {
            current = (current + 1) % items.length;
            showSlide(current);
        }, 3000); // 4 secondes par avis
    }
});

