<template>
    <Header2 id="navbar"></Header2>
    <main class="theme">

		<div class="theme__interactions">
			<a href="/tutorial/guide" class="theme__interactions__return-guide theme__background"> 
				<img class="theme__interactions__return-guide__left-arrow" src="~/public/back-arrow-46x46.png">
				<h3 class="theme__interactions__return-guide__guide"> Retour au guide </h3>
			</a>

			<div class="theme__interactions__show-summary theme__background">
				<h3 class="theme__interactions__show-summary__summary"> Sommaire </h3>
			</div>

			<div class="theme__interactions__sidebar theme__background">
				<img class="theme__interactions__sidebar__close" src="~/public/cross-46x46.png">
				<h2 class="theme__interactions__sidebar__section-title"> Les groupes </h2>
				<nav>
					<ul class="theme__interactions__sidebar__summary">
					<li><a href="#part1" class="theme__interactions__sidebar__summary__shortens"> 1. Créer un groupe </a></li>
					<li><a href="#part2" class="theme__interactions__sidebar__summary__shortens"> 2. Rejoindre un groupe </a></li>
					<li><a href="#part3" class="theme__interactions__sidebar__summary__shortens"> 3. Voir tous les membres d’un groupe </a></li>
					<li><a href="#part4" class="theme__interactions__sidebar__summary__shortens"> 4. Quitter un groupe </a></li>
				</ul>
				</nav>
			</div>
		</div>

		<div class="theme__content">
			<section id="part1" class="theme__content__section">
				<h2 class="hero__title"> Créer un groupe </h2>
				<p class="hero__subtitle"> Pour créer un groupe, connectez-vous sur le site avec vos identifiants, puis, rendez-vous dans l’onglet « Groupes ». Cliquez sur le bouton coloré en haut à droite : une fenêtre s’ouvre, il ne vous reste plus qu’à renseigner les informations demandées, puis à cliquer sur « Valider la création ». Et voilà, votre groupe est créé ! Vous pouvez désormais le retrouver dans l’onglet d’accueil. </p>
			</section>

			<section id="part2" class="theme__content__section">
				<h2 class="hero__title"> Rejoindre un groupe </h2>
				<p class="hero__subtitle"> Il n’est pas possible de rejoindre un groupe en le recherchant via un champ de texte. Pour en faire partie, vous devez être invité directement par l’administrateur. Vous recevrez une invitation par mail, contenant un code à 6 chiffres ainsi qu’un lien vers une page dédiée sur notre site. Sur cette page, entrez simplement le code reçu, puis cliquez sur le bouton pour accepter l’invitation. Et voilà, félicitations ! Vous faites désormais partie d’une nouvelle communauté ! </p>
			</section>

			<section id="part3" class="theme__content__section">
				<h2 class="hero__title"> Voir tous les membres </h2>
				<p class="hero__subtitle"> Depuis la page d’accueil de votre groupe, vous pouvez consulter la liste de tous les membres ainsi que leur rôle. Pour cela, cliquez sur le bouton « Voir les membres ». Une fenêtre s’ouvre avec la liste complète des membres du groupe. Vous pouvez également utiliser un filtre pour afficher uniquement certains rôles. </p>
			</section>

			<section id="part4" class="theme__content__section">
				<h2 class="hero__title"> Quitter un groupe </h2>
				<p class="hero__subtitle"> Si vous souhaitez quitter l’un des groupes dont vous êtes membre, rendez-vous sur la page d’accueil du groupe, puis cliquez sur le bouton gris « Quitter le groupe ». Une fenêtre de confirmation s’affiche : validez votre choix, et le groupe disparaîtra de votre page d’accueil. </p>
			</section>
		</div>

    </main>

	<footer id="contact" class="footer">
		<div class="footer__content">
			<div class="footer__brand">
				<a href="/" class="footer__logo">VoteRight</a>
				<p class="footer__description">
					La plateforme de démocratie participative qui transforme vos idées en actions concrètes.
				</p>
			</div>
				
			<nav class="footer__nav">
				<ul class="footer__nav-list">
					<li><a href="#fonctionnalites" class="footer__nav-link">Fonctionnalités</a></li>
					<!--<li><a href="#" class="footer__nav-link">Tarifs</a></li>-->
					<li><a href="#contact" class="footer__nav-link">Contact</a></li>
					<li><a href="#" class="footer__nav-link">Mentions légales</a></li>
					<li><a href="#" class="footer__nav-link">Confidentialité</a></li>
					<li><a href="https://beta.voteright.fr" class="footer__nav-link">Support</a></li>
				</ul>
			</nav>
		</div>
				
		<div class="footer__bottom">
			<p>&copy; 2025 VoteRight Team. Tous droits réservés.</p>
		</div>
	</footer>
	
</template>

<script setup>

import { onMounted } from 'vue'

onMounted(() => {
  const sidebar = document.querySelector('.theme__interactions__sidebar');
  const summaryButton = document.querySelector('.theme__interactions__show-summary');
  const closeButton = document.querySelector('.theme__interactions__sidebar__close');
  const links = document.querySelectorAll('.theme__interactions__sidebar__summary__shortens');

  const offsetter = () => document.getElementById('navbar').getBoundingClientRect().height + 30;

  links.forEach(link => {
    link.addEventListener('click', event => {
      event.preventDefault();
      const targetId = link.getAttribute('href').substring(1);
      const target = document.getElementById(targetId);

      const y = target.getBoundingClientRect().top + window.scrollY - offsetter();
      window.scrollTo({ top: y, behavior: 'smooth' });

      if (sidebar) {
        sidebar.classList.remove('active');
      }
    });
  });

  if (summaryButton && sidebar) {
    summaryButton.addEventListener('click', () => {
      sidebar.classList.add('active');
    });
  }

  if (closeButton && sidebar) {
    closeButton.addEventListener('click', () => {
      sidebar.classList.remove('active');
    });
  }
});

</script>