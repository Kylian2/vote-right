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
                <h2 class="theme__interactions__sidebar__section-title">Les rôles</h2>
                <nav>
                    <ol class="theme__interactions__sidebar__summary">
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part1')" class="theme__interactions__sidebar__summary__shortens">
                                Administrateur
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part2')" class="theme__interactions__sidebar__summary__shortens"> Décideur </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part3')" class="theme__interactions__sidebar__summary__shortens"> Modérateur </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part4')" class="theme__interactions__sidebar__summary__shortens"> Assesseur </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part5')" class="theme__interactions__sidebar__summary__shortens"> Membre </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="theme__content">
            <section id="part1" class="theme__content__section">
                <h2 class="hero__title">Administrateur</h2>
                <p class="hero__subtitle">
                    L’administrateur détient toutes les autorisations au sein du groupe. Lorsque vous créez un groupe,
                    vous en devenez automatiquement l’administrateur. Vous avez ensuite le pouvoir de nommer des
                    modérateurs, des décideurs ou encore des assesseurs. Si vous souhaitez quitter le groupe en tant
                    qu’administrateur, vous devrez au préalable désigner un successeur.
                </p>
            </section>

            <section id="part2" class="theme__content__section">
                <h2 class="hero__title">Décideur</h2>
                <p class="hero__subtitle">
                    Les décideurs jouent un rôle centré sur l’organisation tactique du groupe, notamment en ce qui
                    concerne la gestion des budgets et des thèmes. Ils ont également la particularité de pouvoir adopter
                    une proposition sans passer par un vote, c’est le seul rôle à disposer de cette capacité. Il a aussi
                    pour rôle d’adopter la proposition une fois que les votes sont clos et validés.
                </p>
            </section>

            <section id="part3" class="theme__content__section">
                <h2 class="hero__title">Modérateur</h2>
                <p class="hero__subtitle">
                    Le modérateur a pour mission principale de faire respecter les règles au sein du groupe. Il a la
                    charge du traitement des signalements concernant les commentaires et les propositions. Il peut
                    choisir de clore un signalement ou de supprimer le commentaire ou la proposition concernée.
                </p>
            </section>

            <section id="part4" class="theme__content__section">
                <h2 class="hero__title">Assesseur</h2>
                <p class="hero__subtitle">
                    L’assesseur est responsable de la gestion des votes au sein du groupe. Il planifie la durée des
                    discussions ainsi que celle des périodes de vote. Une fois le vote terminé, il en valide les
                    résultats. La proposition peut alors être officiellement adoptée.
                </p>
            </section>

            <section id="part5" class="theme__content__section">
                <h2 class="hero__title">Membre</h2>
                <p class="hero__subtitle">
                    Le rôle de membre est attribué par défaut lorsqu’un utilisateur rejoint un groupe via une
                    invitation. Ce rôle ne comporte pas de responsabilités particulières, mais permet de participer
                    pleinement à la vie du groupe, notamment en ce qui concerne les propositions. Un membre peut être
                    amené à évoluer vers un rôle avec plus de responsabilités, à la suite d’une promotion décidée par
                    l’administrateur.
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
