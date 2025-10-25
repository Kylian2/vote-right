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
                <h2 class="theme__interactions__sidebar__section-title">Les votes</h2>
                <nav>
                    <ol class="theme__interactions__sidebar__summary">
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part1')" class="theme__interactions__sidebar__summary__shortens">
                                Personnaliser les options d’un vote
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part2')" class="theme__interactions__sidebar__summary__shortens">
                                Valider les résultats après le vote
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part3')" class="theme__interactions__sidebar__summary__shortens">
                                Participer à un vote
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="theme__content">
            <section id="part1" class="theme__content__section">
                <h2 class="hero__title">Personnaliser les options d’un vote</h2>
                <p class="hero__subtitle">
                    Pour personnaliser les options d’un vote, rendez-vous dans l’interface d’administration,
                    sélectionnez la proposition concernée, puis cliquez sur le bouton « Planifier un vote ». Vous
                    pourrez alors choisir le mode de scrutin, définir la durée de la discussion et celle du vote, ainsi
                    que composer les choix proposés aux votants. Une fois les paramètres configurés, cliquez sur «
                    Valider » pour enregistrer la planification.
                </p>
            </section>

            <section id="part2" class="theme__content__section">
                <h2 class="hero__title">Valider les résultats après le vote</h2>
                <p class="hero__subtitle">
                    Une fois la durée du vote écoulée pour une proposition, vous pourrez en valider ou refuser les
                    résultats selon vos critères. Si le vote est validé, la proposition pourra alors être adoptée.
                </p>
            </section>

            <section id="part3" class="theme__content__section">
                <h2 class="hero__title">Participer à un vote</h2>
                <p class="hero__subtitle">
                    Pour participer à un vote, rien de plus simple : rendez-vous sur la page de la proposition
                    concernée. Si le vote a été planifié et que la période de discussion est terminée, un panneau de
                    vote s’affiche sur la droite de l’écran. Il vous suffit alors de faire votre choix et de le valider
                    pour que votre vote soit pris en compte.
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
