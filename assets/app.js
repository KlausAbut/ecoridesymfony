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
