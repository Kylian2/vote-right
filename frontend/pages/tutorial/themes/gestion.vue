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
                <h2 class="theme__interactions__sidebar__section-title">Outil de gestion</h2>
                <nav>
                    <ol class="theme__interactions__sidebar__summary">
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part1')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Modifier les budgets d’un groupe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part2')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Ajouter un thème à un groupe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part3')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Modifier les informations d’un groupe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part4')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Modifier ou supprimer une proposition
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part5')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Planifier le vote d’une proposition
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part6')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Gérer les rôles au sein d’un groupe
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part7')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Valider les votes d’une proposition
                            </a>
                        </li>
                        <li @click="closeSidebar">
                            <a
                                @click.prevent="scrollToSection('part8')"
                                class="theme__interactions__sidebar__summary__shortens"
                            >
                                Traiter un signalement
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="theme__content">
            <section id="part1" class="theme__content__section">
                <h2 class="hero__title">Modifier les budgets d’un groupe</h2>
                <p class="hero__subtitle">
                    Pour les rôles disposant de cette fonctionnalité, la gestion du budget est accessible depuis la page
                    d’accueil de l’interface d’administration. Cliquez sur « Détail du budget » pour afficher les
                    informations budgétaires correspondant à l’année sélectionnée. Pour modifier la répartition du
                    budget total ou par thème, cliquez sur le bouton noir en haut à droite.
                </p>
            </section>

            <section id="part2" class="theme__content__section">
                <h2 class="hero__title">Ajouter un thème à un groupe</h2>
                <p class="hero__subtitle">
                    Dans l’interface d’administration, cliquez sur le bouton « Ajouter un thème ». Une fenêtre pop-up
                    s’ouvre, vous invitant à saisir le nom du nouveau thème ainsi qu’à lui attribuer un budget. Pour
                    valider, cliquez sur « Ajouter le thème ». Attention : cette action peut nécessiter un ajustement de
                    la répartition de votre budget.
                </p>
            </section>

            <section id="part3" class="theme__content__section">
                <h2 class="hero__title">Modifier les informations d’un groupe</h2>
                <p class="hero__subtitle">
                    Seul l’administrateur peut modifier un groupe. Pour cela, rendez-vous sur la page d’accueil du
                    groupe et cliquez sur le bouton « Modifier le groupe ». Vous pourrez alors mettre à jour le nom, la
                    description, la couleur ou encore la photo du groupe.
                </p>
            </section>

            <section id="part4" class="theme__content__section">
                <h2 class="hero__title">Modifier ou supprimer une proposition</h2>
                <p class="hero__subtitle">
                    Il est possible de modifier le budget d’une proposition ou de la supprimer uniquement si celle-ci
                    n’a pas encore été acceptée ou refusée. Pour cela, accédez à la proposition concernée depuis la
                    première page de l’interface d’administration, puis cliquez dessus. Ensuite, utilisez le bouton
                    rouge pour effectuer la modification ou la suppression.
                </p>
            </section>

            <section id="part5" class="theme__content__section">
                <h2 class="hero__title">Planifier le vote d’une proposition</h2>
                <p class="hero__subtitle">
                    Cette fonctionnalité est principalement utilisée par les assesseurs, mais peut également être
                    accessible aux administrateurs. Elle se trouve dans l’interface d’administration : une fois
                    positionné sur une proposition, cliquez sur le bouton « Planification du vote ». Vous pourrez alors
                    configurer les paramètres du futur vote.
                </p>
            </section>

            <section id="part6" class="theme__content__section">
                <h2 class="hero__title">Gérer les rôles au sein d’un groupe</h2>
                <p class="hero__subtitle">
                    C’est l’une des fonctionnalités clés, réservée uniquement aux administrateurs. La gestion des rôles
                    est accessible via le bouton « Gérer les membres ». L’administrateur peut alors promouvoir un membre
                    en modifiant son rôle, ou bien le supprimer du groupe en cliquant sur la petite croix rouge, puis en
                    confirmant la suppression dans la fenêtre pop-up qui s’affiche.
                </p>
            </section>

            <section id="part7" class="theme__content__section">
                <h2 class="hero__title">Valider les votes d’une proposition</h2>
                <p class="hero__subtitle">
                    Une fois le vote terminé, l’assesseur peut le valider en cliquant sur la proposition concernée, puis
                    sur le bouton « Valider » dans la rubrique « Vote » de la fenêtre qui s’affiche. Une fois validée,
                    la proposition entre officiellement en vigueur.
                </p>
            </section>

            <section id="part8" class="theme__content__section">
                <h2 class="hero__title">Traiter un signalement</h2>
                <p class="hero__subtitle">
                    Pour qu’un modérateur ou un administrateur puisse traiter un signalement, il doit cliquer sur le
                    bouton « Accéder aux outils de modération ». Sur la page qui s’ouvre, tous les signalements en cours
                    sont visibles. Le modérateur ou l’administrateur peut alors examiner les contenus concernés et, si
                    nécessaire, supprimer les commentaires jugés problématiques.
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
