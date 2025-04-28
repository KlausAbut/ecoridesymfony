import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import 'bootstrap';
import './styles/app.css';

document.addEventListener('DOMContentLoaded', function () {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownContent = document.getElementById('dropdownContent');
    const dropdownArrow = document.getElementById('dropdownArrow');
  
    dropdownButton.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = dropdownContent.style.display === 'block';
      dropdownContent.style.display = isOpen ? 'none' : 'block';
      dropdownArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    });
  
    document.addEventListener('click', function () {
      dropdownContent.style.display = 'none';
      dropdownArrow.style.transform = 'rotate(0deg)';
    });
  });
  


