<template>
    <Header2 id="navbar"></Header2>
    <main class="theme">
        <div class="theme__interactions">
            <a href="/tutorial/guide" class="theme__interactions__return-guide theme__background">
                <i class="material-icons theme__interactions__return-guide__left-arrow">arrow_back</i>

                <h3 class="theme__interactions__return-guide__guide">Retour au guide</h3>
            </a>

            <div @click="openSidebar" class="theme__interactions__show-summary theme__background">
                <h3 class="theme__interactions__show-summary__summary">Sommaire</h3>
            </div>

            <div :class="{ active: isSidebarOpen }" class="theme__interactions__sidebar theme__background">
                <i @click="closeSidebar" class="material-icons theme__interactions__sidebar__close">close</i>
                <h2 class="theme__interactions__sidebar__section-title">Les propositions</h2>
                <nav>
                    <ol class="theme__interactions__sidebar__summary">
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part1')" class="theme__interactions__sidebar__summary__shortens">
                                Créer une nouvelle proposition
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part2')" class="theme__interactions__sidebar__summary__shortens">
                                Discuter sur une proposition
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part3')" class="theme__interactions__sidebar__summary__shortens">
                                Demander le vote d’une proposition
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part4')" class="theme__interactions__sidebar__summary__shortens">
                                Signaler un message inapproprié
                            </a>
                        </li>
                    </ol>
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

    <Footer id="contact"></Footer>
</template>

<script setup>

import { ref } from 'vue'

const isSidebarOpen = ref(false)

const openSidebar = () => (isSidebarOpen.value = true)
const closeSidebar = () => (isSidebarOpen.value = false)

const offsetter = () => document.getElementById('navbar').getBoundingClientRect().height + 30

const scrollToSection = (id) => {
  const target = document.getElementById(id)
  if (!target) return
  const y = target.getBoundingClientRect().top + window.scrollY - offsetter()
  window.scrollTo({ top: y, behavior: 'smooth' })
  closeSidebar()
}

</script>
