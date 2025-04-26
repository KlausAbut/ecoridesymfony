import './bootstrap.js';
import 'bootstrap/dist/css/bootstrap.min.css';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const dropdownTriggers = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    dropdownTriggers.forEach(trigger => {
        new bootstrap.Dropdown(trigger);
    });
    console.log('✅ Dropdown Bootstrap activé');
});

import './styles/app.css';



console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
