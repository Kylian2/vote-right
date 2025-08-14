<template>
    <Header2 id="navbar"></Header2>
    <main class="theme">
        <div class="theme__interactions">
            <a href="/tutorial/guide" class="theme__interactions__return-guide theme__background">
                <i class="material-icons theme__interactions__return-guide__left-arrow">arrow_back</i>

                <h3 class="theme__interactions__return-guide__guide">Retour au guide</h3>
            </a>

            <div class="theme__interactions__show-summary theme__background">
                <h3 class="theme__interactions__show-summary__summary">Sommaire</h3>
            </div>

            <div class="theme__interactions__sidebar theme__background">
                <i class="material-icons theme__interactions__sidebar__close">close</i>

                <h2 class="theme__interactions__sidebar__section-title">Les propositions</h2>
                <nav>
                    <ul class="theme__interactions__sidebar__summary">
                        <li>
                            <a href="#part1" class="theme__interactions__sidebar__summary__shortens">
                                1. Créer une nouvelle proposition
                            </a>
                        </li>
                        <li>
                            <a href="#part2" class="theme__interactions__sidebar__summary__shortens">
                                2. Discuter sur une proposition
                            </a>
                        </li>
                        <li>
                            <a href="#part3" class="theme__interactions__sidebar__summary__shortens">
                                3. Demander le vote d’une proposition
                            </a>
                        </li>
                        <li>
                            <a href="#part4" class="theme__interactions__sidebar__summary__shortens">
                                4. Signaler un message inapproprié
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="theme__content">
            <section id="part1" class="theme__content__section">
                <h2 class="hero__title">Créer une nouvelle proposition</h2>
                <p class="hero__subtitle">
                    Pour créer une nouvelle proposition au sein de votre groupe, rendez-vous sur la page d’accueil du
                    groupe, puis cliquez sur le bouton « Nouvelle proposition ». Une nouvelle page s’ouvre : remplissez
                    les informations demandées, puis cliquez sur « Valider la création ». Et voilà, votre proposition
                    est lancée ! Vous pouvez dès maintenant en discuter et voter avec les membres du groupe.
                </p>
            </section>

            <section id="part2" class="theme__content__section">
                <h2 class="hero__title">Discuter sur une proposition</h2>
                <p class="hero__subtitle">
                    Pour échanger autour d’une proposition, rendez-vous sur la page d’accueil de votre groupe, puis
                    cliquez sur la proposition qui vous intéresse. En bas de l’écran, vous trouverez un fil de
                    discussion où vous pouvez lire et répondre aux messages des autres membres de la communauté. Un
                    champ de texte situé à la fin de ce fil vous permet d’écrire et d’envoyer vos propres messages.
                </p>
            </section>

            <section id="part3" class="theme__content__section">
                <h2 class="hero__title">Demander le vote d’une proposition</h2>
                <p class="hero__subtitle">
                    Pour demander le déclenchement d’un vote sur une proposition, rendez-vous sur la page de la
                    proposition concernée, puis cliquez sur le bouton « Demande formelle ». Votre demande s’ajoutera à
                    celles des autres membres du groupe ayant la même intention. Un assesseur prendra ensuite
                    connaissance du nombre de demandes reçues et planifiera le vote en conséquence.
                </p>
            </section>

            <section id="part4" class="theme__content__section">
                <h2 class="hero__title">Signaler un message inapproprié</h2>
                <p class="hero__subtitle">
                    Si vous estimez qu’un message dans le fil de discussion d’une proposition est inapproprié, vous
                    pouvez le signaler facilement. Appuyez sur le message concerné, puis cliquez sur le bouton «
                    Signaler » situé à droite. Votre signalement sera ensuite pris en charge par un modérateur ou un
                    administrateur du groupe.
                </p>
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
    const sidebar = document.querySelector('.theme__interactions__sidebar')
    const summaryButton = document.querySelector('.theme__interactions__show-summary')
    const closeButton = document.querySelector('.theme__interactions__sidebar__close')
    const links = document.querySelectorAll('.theme__interactions__sidebar__summary__shortens')

    const offsetter = () => document.getElementById('navbar').getBoundingClientRect().height + 30

    links.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault()
            const targetId = link.getAttribute('href').substring(1)
            const target = document.getElementById(targetId)

            const y = target.getBoundingClientRect().top + window.scrollY - offsetter()
            window.scrollTo({ top: y, behavior: 'smooth' })

            if (sidebar) {
                sidebar.classList.remove('active')
            }
        })
    })

    if (summaryButton && sidebar) {
        summaryButton.addEventListener('click', () => {
            sidebar.classList.add('active')
        })
    }

    if (closeButton && sidebar) {
        closeButton.addEventListener('click', () => {
            sidebar.classList.remove('active')
        })
    }
})
</script>
