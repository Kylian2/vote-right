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
                <h2 class="theme__interactions__sidebar__section-title">Gestion du compte</h2>
                <nav>
                    <ol class="theme__interactions__sidebar__summary">
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part1')" class="theme__interactions__sidebar__summary__shortens">
                                Gérer les paramètres de notification
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part2')" class="theme__interactions__sidebar__summary__shortens">
                                Modifier les informations de son compte
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part3')" class="theme__interactions__sidebar__summary__shortens">
                                Modifier son mot de passe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a @click.prevent="scrollToSection('part4')" class="theme__interactions__sidebar__summary__shortens">
                                Supprimer son compte
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="theme__content">
            <section id="part1" class="theme__content__section">
                <h2 class="hero__title">Gérer les paramètres de notification</h2>
                <p class="hero__subtitle">
                    Pour modifier vos paramètres de notifications, rendez-vous sur la page d’accueil, puis cliquez sur «
                    Mon compte » en haut à droite. Une fenêtre s’ouvre : dans la rubrique « Notifications », vous pouvez
                    activer ou désactiver les types de notifications souhaités. N’oubliez pas de valider vos changements
                    pour qu’ils soient pris en compte. Cela vous permettra de mieux contrôler les messages envoyés à
                    votre boîte mail.
                </p>
            </section>

            <section id="part2" class="theme__content__section">
                <h2 class="hero__title">Modifier les informations de son compte</h2>
                <p class="hero__subtitle">
                    Comme pour la gestion des notifications, rendez-vous dans l’espace « Mon compte » accessible depuis
                    l’écran d’accueil. Dans la rubrique « Mes informations », activez le curseur « Modifier vos
                    informations ». Les champs contenant vos données actuelles s’affichent alors : vous pouvez les
                    modifier selon vos besoins, puis valider les changements pour les enregistrer.
                </p>
            </section>

            <section id="part3" class="theme__content__section">
                <h2 class="hero__title">Modifier son mot de passe</h2>
                <p class="hero__subtitle">
                    Dans la rubrique « Mes informations » de votre compte, activez le curseur « Modifier votre mot de
                    passe ». En bas de la fenêtre, vous pourrez alors saisir votre nouveau mot de passe, puis valider
                    pour enregistrer la modification.
                </p>
            </section>

            <section id="part4" class="theme__content__section">
                <h2 class="hero__title">Supprimer son compte</h2>
                <p class="hero__subtitle">
                    Toujours dans la rubrique « Mes informations », tout en bas de la fenêtre, cliquez sur le texte
                    rouge « Supprimer le compte ». Une nouvelle fenêtre s’ouvre : il vous sera alors demandé de saisir
                    votre adresse e-mail pour confirmer la suppression. Une fois l’opération validée, votre compte sera
                    définitivement supprimé.
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
