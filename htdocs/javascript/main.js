  // Récupération du bouton de menu hamburger et des éléments de menu
  var menuToggle = document.getElementById('menu-toggle');
  var menu = document.getElementById('menu');

  // Ajout d'un écouteur d'événements pour le clic sur le bouton de menu hamburger
  menuToggle.addEventListener('click', function() {
    // Ajout ou suppression de la classe "hidden" pour afficher ou masquer les éléments de menu
    menu.classList.toggle('hidden');
  });