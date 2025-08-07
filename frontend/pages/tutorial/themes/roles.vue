<template>
    <Header2></Header2>
    <main class="theme">
       
		<div class="theme__sidebar">
			<a href="/tutorial/guide" class="theme__sidebar__return-guide"> Retour au guide </a>
			<h2 class="theme__sidebar__section-title"> Les rôles </h2>
			<nav class="theme__sidebar__summary">
				<ul>
					<li><a href="#partie1" class="theme__sidebar__summary__shortens"> 1. Administrateur </a></li>
					<li><a href="#partie2" class="theme__sidebar__summary__shortens"> 2. Décideur </a></li>
					<li><a href="#partie3" class="theme__sidebar__summary__shortens"> 3. Modérateur </a></li>
					<li><a href="#partie4" class="theme__sidebar__summary__shortens"> 4. Assesseur </a></li>
					<li><a href="#partie4" class="theme__sidebar__summary__shortens"> 4. Membre </a></li>
				</ul>
			</nav>
		</div>

		<div class="theme__content">
			<section class="theme__content__section">
				<h2 class="hero__title"> Administrateur </h2>
				<p class="hero__subtitle"> L’administrateur détient toutes les autorisations au sein du groupe. Lorsque vous créez un groupe, vous en devenez automatiquement l’administrateur. Vous avez ensuite le pouvoir de nommer des modérateurs, des décideurs ou encore des assesseurs. Si vous souhaitez quitter le groupe en tant qu’administrateur, vous devrez au préalable désigner un successeur.
 				</p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Décideur </h2>
				<p class="hero__subtitle"> Les décideurs jouent un rôle centré sur l’organisation tactique du groupe, notamment en ce qui concerne la gestion des budgets et des thèmes. Ils ont également la particularité de pouvoir adopter une proposition sans passer par un vote, c’est le seul rôle à disposer de cette capacité. Il a aussi pour rôle d’adopter la proposition une fois que les votes sont clos et validés. </p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Modérateur </h2>
				<p class="hero__subtitle"> Le modérateur a pour mission principale de faire respecter les règles au sein du groupe. Il a la charge du traitement des signalements concernant les commentaires et les propositions. Il peut choisir de clore un signalement ou de supprimer le commentaire ou la proposition concernée. </p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Assesseur </h2>
				<p class="hero__subtitle"> L’assesseur est responsable de la gestion des votes au sein du groupe. Il planifie la durée des discussions ainsi que celle des périodes de vote. Une fois le vote terminé, il en valide les résultats. La proposition peut alors être officiellement adoptée. </p>
			</section>

			<section class="theme__content__section">
				<h2 class="hero__title"> Membre </h2>
				<p class="hero__subtitle"> Le rôle de membre est attribué par défaut lorsqu’un utilisateur rejoint un groupe via une invitation. Ce rôle ne comporte pas de responsabilités particulières, mais permet de participer pleinement à la vie du groupe, notamment en ce qui concerne les propositions. Un membre peut être amené à évoluer vers un rôle avec plus de responsabilités, à la suite d’une promotion décidée par l’administrateur. </p>
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