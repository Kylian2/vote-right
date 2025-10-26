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
                <h2 class="theme__interactions__sidebar__section-title">Les groupes</h2>
                <nav>
                    <ol class="theme__interactions__sidebar__summary">
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part1')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Créer un groupe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part2')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Rejoindre un groupe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part3')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Voir tous les membres d’un groupe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part4')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Quitter un groupe
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="theme__content">
            <section id="part1" class="theme__content__section">
                <h2 class="hero__title">Créer un groupe</h2>
                <p class="hero__subtitle">
                    Pour créer un groupe, connectez-vous sur le site avec vos identifiants, puis, rendez-vous dans
                    l’onglet « Groupes ». Cliquez sur le bouton coloré en haut à droite : une fenêtre s’ouvre, il ne
                    vous reste plus qu’à renseigner les informations demandées, puis à cliquer sur « Valider la création
                    ». Et voilà, votre groupe est créé ! Vous pouvez désormais le retrouver dans l’onglet d’accueil.
                </p>
            </section>

            <section id="part2" class="theme__content__section">
                <h2 class="hero__title">Rejoindre un groupe</h2>
                <p class="hero__subtitle">
                    Il n’est pas possible de rejoindre un groupe en le recherchant via un champ de texte. Pour en faire
                    partie, vous devez être invité directement par l’administrateur. Vous recevrez une invitation par
                    mail, contenant un code à 6 chiffres ainsi qu’un lien vers une page dédiée sur notre site. Sur cette
                    page, entrez simplement le code reçu, puis cliquez sur le bouton pour accepter l’invitation. Et
                    voilà, félicitations ! Vous faites désormais partie d’une nouvelle communauté !
                </p>
            </section>

            <section id="part3" class="theme__content__section">
                <h2 class="hero__title">Voir tous les membres</h2>
                <p class="hero__subtitle">
                    Depuis la page d’accueil de votre groupe, vous pouvez consulter la liste de tous les membres ainsi
                    que leur rôle. Pour cela, cliquez sur le bouton « Voir les membres ». Une fenêtre s’ouvre avec la
                    liste complète des membres du groupe. Vous pouvez également utiliser un filtre pour afficher
                    uniquement certains rôles.
                </p>
            </section>

            <section id="part4" class="theme__content__section">
                <h2 class="hero__title">Quitter un groupe</h2>
                <p class="hero__subtitle">
                    Si vous souhaitez quitter l’un des groupes dont vous êtes membre, rendez-vous sur la page d’accueil
                    du groupe, puis cliquez sur le bouton gris « Quitter le groupe ». Une fenêtre de confirmation
                    s’affiche : validez votre choix, et le groupe disparaîtra de votre page d’accueil.
                </p>
            </section>
        </div>
    </main>

    <Footer id="contact"></Footer>
</template>

<script setup>
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
