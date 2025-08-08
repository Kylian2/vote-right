<template>
    <Header2 id="navbar"></Header2>
    <main class="theme">

    	<div class="theme__sidebar">
			<a href="/tutorial/guide" class="theme__sidebar__return-guide"> Retour au guide </a>
			<h2 class="theme__sidebar__section-title"> Gestion du compte </h2>
			<nav>
				<ul class="theme__sidebar__summary">
					<li><a href="#part1" class="theme__sidebar__summary__shortens"> 1. Gérer les paramètres de notification </a></li>
					<li><a href="#part2" class="theme__sidebar__summary__shortens"> 2. Modifier les informations de son compte </a></li>
					<li><a href="#part3" class="theme__sidebar__summary__shortens"> 3. Modifier son mot de passe </a></li>
					<li><a href="#part4" class="theme__sidebar__summary__shortens"> 4. Supprimer son compte </a></li>
				</ul>
			</nav>
		</div>

		<div class="theme__content">
			<section id="part1" class="theme__content__section">
				<h2 class="hero__title"> Gérer les paramètres de notification </h2>
				<p class="hero__subtitle"> Pour modifier vos paramètres de notifications, rendez-vous sur la page d’accueil, puis cliquez sur « Mon compte » en haut à droite. Une fenêtre s’ouvre : dans la rubrique « Notifications », vous pouvez activer ou désactiver les types de notifications souhaités. N’oubliez pas de valider vos changements pour qu’ils soient pris en compte. Cela vous permettra de mieux contrôler les messages envoyés à votre boîte mail. </p>
			</section>

			<section id="part2" class="theme__content__section">
				<h2 class="hero__title"> Modifier les informations de son compte </h2>
				<p class="hero__subtitle"> Comme pour la gestion des notifications, rendez-vous dans l’espace « Mon compte » accessible depuis l’écran d’accueil. Dans la rubrique « Mes informations », activez le curseur « Modifier vos informations ». Les champs contenant vos données actuelles s’affichent alors : vous pouvez les modifier selon vos besoins, puis valider les changements pour les enregistrer. </p>
			</section>

			<section id="part3" class="theme__content__section">
				<h2 class="hero__title"> Modifier son mot de passe </h2>
				<p class="hero__subtitle"> Dans la rubrique « Mes informations » de votre compte, activez le curseur « Modifier votre mot de passe ». En bas de la fenêtre, vous pourrez alors saisir votre nouveau mot de passe, puis valider pour enregistrer la modification. </p>
			</section>

			<section id="part4" class="theme__content__section">
				<h2 class="hero__title"> Supprimer son compte </h2>
				<p class="hero__subtitle"> Toujours dans la rubrique « Mes informations », tout en bas de la fenêtre, cliquez sur le texte rouge « Supprimer le compte ». Une nouvelle fenêtre s’ouvre : il vous sera alors demandé de saisir votre adresse e-mail pour confirmer la suppression. Une fois l’opération validée, votre compte sera définitivement supprimé. </p>
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
  const links = document.querySelectorAll('.theme__sidebar__summary__shortens');
  const sections = document.querySelectorAll('.theme__content__section');

  const offsetter = () => document.getElementById('navbar').getBoundingClientRect().height + 30;

  links.forEach(link => {
    link.addEventListener('click', event => {
      event.preventDefault();
      const targetId = link.getAttribute('href').substring(1);
      const target = document.getElementById(targetId);

      const y = target.getBoundingClientRect().top + window.scrollY - offsetter();
      window.scrollTo({ top: y, behavior: 'smooth' });
    });
  });
});

</script>