<template>
    <Header2></Header2>
    <main class="theme">
		
		<div class="theme__sidebar">
			<a href="/tutorial/guide" class="theme__sidebar__return-guide"> Retour au guide </a>
			<h2 class="theme__sidebar__section-title"> Les votes </h2>
			<nav class="theme__sidebar__summary">
				<ul>
					<li><a href="#partie1" class="theme__sidebar__summary__shortens"> 1. Personnaliser les options d’un vote </a></li>
					<li><a href="#partie2" class="theme__sidebar__summary__shortens"> 2. Valider les résultats après le vote </a></li>
					<li><a href="#partie3" class="theme__sidebar__summary__shortens"> 3. Participer à un vote </a></li>
				</ul>
			</nav>
		</div>

		<div class="theme__content">
			<section class="theme__content__section">
				<h2 class="hero__title"> Personnaliser les options d’un vote </h2>
				<p class="hero__subtitle"> Pour personnaliser les options d’un vote, rendez-vous dans l’interface d’administration, sélectionnez la proposition concernée, puis cliquez sur le bouton « Planifier un vote ». Vous pourrez alors choisir le mode de scrutin, définir la durée de la discussion et celle du vote, ainsi que composer les choix proposés aux votants. Une fois les paramètres configurés, cliquez sur « Valider » pour enregistrer la planification. </p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Valider les résultats après le vote </h2>
				<p class="hero__subtitle"> Une fois la durée du vote écoulée pour une proposition, vous pourrez en valider ou refuser les résultats selon vos critères. Si le vote est validé, la proposition pourra alors être adoptée. </p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Participer à un vote </h2>
				<p class="hero__subtitle"> Pour participer à un vote, rien de plus simple : rendez-vous sur la page de la proposition concernée. Si le vote a été planifié et que la période de discussion est terminée, un panneau de vote s’affiche sur la droite de l’écran. Il vous suffit alors de faire votre choix et de le valider pour que votre vote soit pris en compte. </p>
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