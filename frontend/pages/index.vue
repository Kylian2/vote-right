<template>
	<Header type="notlogged"></Header>

	<main class="index">
	
		<section class="hero">
			<div class="hero__title-container">
				<h1>Simplifiez la démocratie participative dans votre communauté</h1>
				<p class="legende">Proposez, votez, discutez et gérez des projets collectifs en toute transparence.</p>
				<div>
					<NuxtLink class="btn btn--full" to="/register">Essayer maintenant</NuxtLink>
					<NuxtLink class="btn btn--secondary" to="#">Découvrir nos fonctionnalités</NuxtLink>
				</div>
			</div>
		</section>

		<section class="why">
			<h2>Pourquoi VoteRight ?</h2>
			<p>
				La solution idéale pour collectivités et associations.
			</p>
			<ul>
				<li>Favorisez la participation citoyenne en ligne.</li>
				<li>centralisez la gestion des propositions et des votes.</li>
				<li>Transparence et efficacité dans la gestion des budgets.</li>
			</ul>
		</section>

		<section class="function-overview">
			<div>
				<h2>
					Découvrez tout ce que VoteRight peut faire pour vous.
				</h2>
				<p class="legende">VoteRight permet à votre communauté de s'engager activement dans les décisions collectives grâce à des outils simples et efficaces.</p>
				<NuxtLink class="btn btn--full" to="/register">Utiliser</NuxtLink>
			</div>
			<div class="function-overview__container">
				<div class="function-overview__function">
					<img src="/images/icons/vote.svg" alt="discussion icon" class="no-mobile">
					<h5>Propositions citoyennes</h5>
					<p>Chaque membre peut soumettre ses idées pour améliorer la collectivité ou l'association.</p>
				</div>
				<div class="function-overview__function">
					<img src="/images/icons/talking.svg" alt="discussion icon" class="no-mobile">
					<h5>Discussions collaboratives</h5>
					<p>Créez des espaces d'échange pour discuter des propositions, enrichir les idées et prendre des décisions ensemble.</p>
				</div>
				<div class="function-overview__function">
					<img src="/images/icons/budget.svg" alt="discussion icon" class="no-mobile">
					<h5>Gestion des budgets</h5>
					<p>Suivez les dépenses et gérez les budgets annuels par thèmes, avec une vue claire et intuitive.</p>
				</div>
				<div class="function-overview__function">
					<img src="/images/icons/algo.svg" alt="discussion icon" class="no-mobile">
					<h5>Outils d'administration</h5>
					<p>Modérez, personnalisez les paramètres des votes, et obtenez des rapports détaillés pour une gestion optimale.</p>
				</div>
			</div>
		</section>

		<section class="join">
			<h2>Rejoindre VoteRight, c'est facile !</h2>
			<div class="join__step-container">
				<div class="join__step">
					<span class="join__step__number">1</span>
					<p class="join__step__detail">Créez votre groupe communautaire.</p>
				</div>
				<div class="join__step">
					<span class="join__step__number">2</span>
					<p class="join__step__detail">Invitez vos membres.</p>
				</div>
				<div class="join__step">
					<span class="join__step__number">3</span>
					<p class="join__step__detail">Proposez, discutez et votez !</p>
				</div>
			</div>
			<NuxtLink class="btn btn--full" to="/register">Créer</NuxtLink>
		</section>

	</main>

	<footer>
        <div class="footer-partie-haute">
            <ul>
                <li><h4>Navigation</h4></li>
                <li><a href="/">Accueil</a></li>
                <li><a href="/login">Se connecter</a></li>
                <li><a href="/register">Créer un compte</a></li>
                <li><a href="https://admin.voteright.fr">Admin</a></li>
            </ul>
			<ul>
                <li><h4>Kylian</h4></li>
                <li><a href="https://github.com/Kylian2">Github</a></li>
                <li><a href="https://www.linkedin.com/in/kylian-richard/">Linkedin</a></li>
            </ul>
			<ul>
                <li><h4>Esteban</h4></li>
                <li><a href="https://github.com/Kylian2">Github</a></li>
                <li><a href="#">Linkedin</a></li>
            </ul>
			<ul>
                <li><h4>Mathieu</h4></li>
                <li><a href="#">Github</a></li>
                <li><a href="#">Linkedin</a></li>
            </ul>
        </div>
        <div class="footer-partie-basse">
            <div>
                <p>© all rights reserved</p>
            </div>
        </div>
    </footer>

</template>
<script setup>

definePageMeta({
  	middleware: ["guest"]
})

useHead({
	title: 'VoteRight',
	meta: [
		{ name: 'description', content: 'VoteRight : donnez à votre communauté le pouvoir de proposer, voter, discuter et gérer des projets collectifs. Idéal pour collectivités et associations engagées !' }
	],
})
</script>