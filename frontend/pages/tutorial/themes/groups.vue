<template>
    <Header2></Header2>
    <main class="theme">
		
		<div class="theme__sidebar">
			<a href="/tutorial/guide" class="theme__sidebar__return-guide"> Retour au guide </a>
			<h2 class="theme__sidebar__section-title"> Les groupes </h2>
			<nav class="theme__sidebar__summary">
				<ul>
					<li><a href="#partie1" class="theme__sidebar__summary__shortens"> 1. Créer un groupe </a></li>
					<li><a href="#partie2" class="theme__sidebar__summary__shortens"> 2. Rejoindre un groupe </a></li>
					<li><a href="#partie3" class="theme__sidebar__summary__shortens"> 3. Voir tous les membres d’un groupe </a></li>
					<li><a href="#partie4" class="theme__sidebar__summary__shortens"> 4. Quitter un groupe </a></li>
				</ul>
			</nav>
		</div>

		<div class="theme__content">
			<section class="theme__content__section">
				<h2 class="hero__title"> Créer un groupe </h2>
				<p class="hero__subtitle"> Pour créer un groupe, connectez-vous sur le site avec vos identifiants, puis, rendez-vous dans l’onglet « Groupes ». Cliquez sur le bouton coloré en haut à droite : une fenêtre s’ouvre, il ne vous reste plus qu’à renseigner les informations demandées, puis à cliquer sur « Valider la création ». Et voilà, votre groupe est créé ! Vous pouvez désormais le retrouver dans l’onglet d’accueil. </p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Rejoindre un groupe </h2>
				<p class="hero__subtitle"> Il n’est pas possible de rejoindre un groupe en le recherchant via un champ de texte. Pour en faire partie, vous devez être invité directement par l’administrateur. Vous recevrez une invitation par mail, contenant un code à 6 chiffres ainsi qu’un lien vers une page dédiée sur notre site. Sur cette page, entrez simplement le code reçu, puis cliquez sur le bouton pour accepter l’invitation. Et voilà, félicitations ! Vous faites désormais partie d’une nouvelle communauté ! </p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Voir tous les membres </h2>
				<p class="hero__subtitle"> Depuis la page d’accueil de votre groupe, vous pouvez consulter la liste de tous les membres ainsi que leur rôle. Pour cela, cliquez sur le bouton « Voir les membres ». Une fenêtre s’ouvre avec la liste complète des membres du groupe. Vous pouvez également utiliser un filtre pour afficher uniquement certains rôles. </p>
			</section>

			<section class="theme__content__section">
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