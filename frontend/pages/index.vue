<template>
    <div class="floating-element floating-element--1"></div>
    <div class="floating-element floating-element--2"></div>
    <div class="floating-element floating-element--3"></div>
    <div class="landing-body" :class="{ 'landing-header--active': hamburgerMenuActive }">
        <header class="landing-header" :class="{ 'landing-header--active': hamburgerMenuActive }">
            <a href="/" class="landing-header__logo">VoteRight</a>

            <nav class="landing-header__nav">
                <a href="#fonctionnalites" class="landing-header__nav-link">Fonctionnalités</a>
                <a href="#communautes" class="landing-header__nav-link">Communautés</a>
                <a href="#contact" class="landing-header__nav-link">Contact</a>
            </nav>

            <div class="landing-header__actions">
                <a href="/login" class="landing-btn landing-btn--secondary">Connexion</a>
                <a href="/register" class="landing-btn landing-btn--primary">Commencer gratuitement</a>
            </div>

            <div class="landing-header__hamburger-menu" @click="hamburgerMenuActive = !hamburgerMenuActive">
                <div class="landing-header__hamburger-menu__line"></div>
                <div class="landing-header__hamburger-menu__line"></div>
                <div class="landing-header__hamburger-menu__line"></div>
            </div>
        </header>

        <section class="hero">
            <div class="badge">Simplifions vos décisions collectives</div>

            <h1 class="hero__title">Reprenez le pouvoir sur vos décisions collectives</h1>

            <p class="hero__subtitle">
                Proposez, débattez, votez et gérez des projets en toute transparence. La démocratie participative à
                portée de votre communauté.
            </p>

            <div class="hero__actions">
                <a href="/register" class="landing-btn landing-btn--primary landing-btn--large">Devenez Beta-Testeur</a>
                <!--<a href="#" class="landing-btn landing-btn--secondary landing-btn--large">Demander une démo</a>-->
            </div>

            <div class="hero__demo-section">
                <div class="hero__mockup">
                    <div class="mockup__header">
                        <div class="mockup__dot"></div>
                        <div class="mockup__dot"></div>
                        <div class="mockup__dot"></div>
                    </div>
                    <div class="mockup__content">
                        <div class="mockup__item">
                            <div class="mockup__icon">
                                <i class="material-icons" id="how_to_vote-icon">how_to_vote</i>
                            </div>
                            <div>
                                <strong>Création de vote</strong><br />
                                <small>Lancez un scrutin en quelques clics</small>
                            </div>
                        </div>
                        <div class="mockup__item">
                            <div class="mockup__icon"><i class="material-icons" id="chat-icon">chat</i></div>
                            <div>
                                <strong>Discussion collaborative</strong><br />
                                <small>Débattez avant de décider</small>
                            </div>
                        </div>
                        <div class="mockup__item">
                            <div class="mockup__icon">
                                <i class="material-icons" id="leaderboard-icon">leaderboard</i>
                            </div>
                            <div>
                                <strong>Résultats transparents</strong><br />
                                <small>Visualisez les décisions prises</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="section__header">
                <div class="badge badge--color-4">Pourquoi VoteRight ?</div>
                <h2 class="section__title">Impliquer tous vos membres, simplement et efficacement</h2>
                <p class="section__text">
                    Que vous soyez une association, une entreprise, une collectivité ou un collectif citoyen, offrez à
                    chacun la possibilité de contribuer activement aux décisions. Notre plateforme met à votre
                    disposition les outils essentiels pour structurer la participation, favoriser le débat et faire
                    avancer les projets communs.
                </p>
            </div>
        </section>

        <section class="features section">
            <div class="section__header">
                <div class="badge badge--color-1">Fonctionnalités</div>
                <h2 class="section__title">
                    Des décisions collectives plus rapides, plus justes, et plus transparentes
                </h2>
            </div>

            <div class="features__grid">
                <div class="feature-card">
                    <div class="feature-card__icon"><i class="material-icons" id="search-icon">search</i></div>
                    <h3 class="feature-card__title">Transparence totale</h3>
                    <p class="feature-card__text">
                        Toutes les prises de décision sont visibles et traçables pour l'ensemble des membres de la
                        communauté.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon"><i class="material-icons" id="chat-icon">chat</i></div>
                    <h3 class="feature-card__title">Participation facilitée</h3>
                    <p class="feature-card__text">
                        Des fils de discussion dédiés à chaque proposition permettent à tous de s'exprimer, même les
                        plus discrets.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="material-icons" id="how_to_vote-icon">how_to_vote</i>
                    </div>
                    <h3 class="feature-card__title">Scrutins adaptés</h3>
                    <p class="feature-card__text">
                        Large choix de modes de vote pour des décisions plus représentatives selon chaque contexte.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon"><i class="material-icons" id="settings-icon">settings</i></div>
                    <h3 class="feature-card__title">Gestion complète</h3>
                    <p class="feature-card__text">
                        Outils de gestion de budget, de modération et algorithmes de recommendations.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="material-icons" id="supervised_user_circle-icon">supervised_user_circle</i>
                    </div>
                    <h3 class="feature-card__title">Rôles dédiés</h3>
                    <p class="feature-card__text">
                        Support spécifique pour les rôles à responsabilité au sein de votre communauté.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="material-icons" id="visibility_off-icon">visibility_off</i>
                    </div>
                    <h3 class="feature-card__title">Anonymat garanti</h3>
                    <p class="feature-card__text">
                        Protection de l'identité et des choix de vote pour tous les membres participants.
                    </p>
                </div>
            </div>

			<div class="features__guide">
				<a href="/tutorial/guide" class="landing-btn landing-btn--blue"> Accéder au guide utilisateur →</a>
			</div>
        </section>

        <section class="audiences section">
            <div class="section__header">
                <div class="badge badge--color-7">Pour qui ?</div>
                <h2 class="section__title">Une solution qui s'adapte à tous les profils de communauté</h2>
            </div>

            <div class="audiences__grid">
                <div class="audience-item">
                    <div class="audience-item__icon">
                        <i class="material-icons" id="volunteer_activism-icon">volunteer_activism</i>
                    </div>
                    <span class="audience-item__text">Associations et ONG</span>
                </div>

                <div class="audience-item">
                    <div class="audience-item__icon">
                        <i class="material-icons" id="corporate_fare-icon">corporate_fare</i>
                    </div>
                    <span class="audience-item__text">Entreprises et startups</span>
                </div>

                <div class="audience-item">
                    <div class="audience-item__icon"><i class="material-icons" id="house-icon">house</i></div>
                    <span class="audience-item__text">Collectivités locales</span>
                </div>

                <div class="audience-item">
                    <div class="audience-item__icon">
                        <i class="material-icons" id="elderly_woman-icon">elderly_woman</i>
                    </div>
                    <span class="audience-item__text">Réseaux citoyens</span>
                </div>

                <div class="audience-item">
                    <div class="audience-item__icon"><i class="material-icons" id="handshake-icon">handshake</i></div>
                    <span class="audience-item__text">Coopératives et syndicats</span>
                </div>

                <div class="audience-item">
                    <div class="audience-item__icon"><i class="material-icons" id="school-icon">school</i></div>
                    <span class="audience-item__text">Projets étudiants et bien d'autres</span>
                </div>
            </div>
        </section>

        <section class="section cta">
            <div class="section__header">
                <h2 class="section__title">Envie de découvrir une nouvelle manière de décider ensemble ?</h2>
                <p class="section__text">Rejoignez celles et ceux qui transforment leurs idées en actions concrètes.</p>
            </div>

            <div class="cta__actions">
                <a href="/register" class="landing-btn landing-btn--primary landing-btn--large">Rejoindre</a>
                <a href="#" class="landing-btn landing-btn--secondary landing-btn--large">Demander une démo</a>
            </div>
        </section>

        <section class="section feedback">
            <div class="feedback__card">
                <div class="badge">Votre avis compte</div>
                <h2 class="section__title">Envie de partager votre avis sur notre site ?</h2>
                <p class="section__text">
                    Nous sommes à l'écoute de tous les retours : n'hésitez pas à nous faire part de vos suggestions,
                    remarques ou critiques !
                </p>

                <a href="https://beta.voteright.fr" class="btn landing-btn landing-btn--primary landing-btn--large"
                    >Donner mon avis maintenant</a
                >
            </div>
        </section>

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
    </div>
</template>
<script setup>
const hamburgerMenuActive = ref(false)
const config = useRuntimeConfig()

definePageMeta({
    middleware: ['guest'],
})

const html = document.getElementsByTagName('html')[0]
html.classList.add('landing-page-background')

onBeforeUnmount(() => {
    html.classList.remove('landing-page-background')
})
</script>
