<template>
    <Header2 id="navbar"></Header2>
    <main class="guide">
        <section class="hero guide__hero">
            <h1 class="hero__title">Guide utilisateur</h1>
            <p class="hero__subtitle">
                Cliquez sur la section qui vous intéresse afin de trouver l'information que vous cherchez.
            </p>
        </section>

        <section class="guide__menu">
            <div class="guide__menu__grid-container">
                <a href="/tutorial/themes/groups" class="guide__menu__grid-container__cell-container">
                    <h3 class="guide__menu__grid-container__cell-container__item">
                        Les <br />
                        groupes
                    </h3>
                </a>

                <a href="/tutorial/themes/proposals" class="guide__menu__grid-container__cell-container">
                    <h3 class="guide__menu__grid-container__cell-container__item">
                        Les <br />
                        propositions
                    </h3>
                </a>

                <a href="/tutorial/themes/votes" class="guide__menu__grid-container__cell-container">
                    <h3 class="guide__menu__grid-container__cell-container__item">
                        Les <br />
                        votes
                    </h3>
                </a>

                <a href="/tutorial/themes/gestion" class="guide__menu__grid-container__cell-container">
                    <h3 class="guide__menu__grid-container__cell-container__item">
                        Outils <br />
                        de gestion
                    </h3>
                </a>

                <a href="/tutorial/themes/roles" class="guide__menu__grid-container__cell-container">
                    <h3 class="guide__menu__grid-container__cell-container__item">Les rôles</h3>
                </a>

                <a href="/tutorial/themes/account" class="guide__menu__grid-container__cell-container">
                    <h3 class="guide__menu__grid-container__cell-container__item">
                        Gestion <br />
                        du compte
                    </h3>
                </a>
            </div>
        </section>

        <section class="guide__FAQ-redirection">
            <p class="hero__subtitle">
                Consulté la FAQ sur les fonctionnalités pour trouver la réponse aux questions les plus posées !
            </p>

            <div @click="scrollToFAQ" class="guide__FAQ-redirection__content">
                <div class="guide__FAQ-redirection__content__arrow-container">
                    <div
                        class="guide__FAQ-redirection__content__arrow-container__arrow guide__FAQ-redirection__content__arrow-container__arrow--left"
                        src="~/public/down-arrow-60x60.png"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <h1 class="hero__title guide__FAQ-redirection__content__title">FAQ</h1>

                <div class="guide__FAQ-redirection__content__arrow-container">
                    <div
                        class="guide__FAQ-redirection__content__arrow-container__arrow guide__FAQ-redirection__content__arrow-container__arrow--right"
                        src="~/public/down-arrow-60x60.png"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="guide__FAQ">
            <div class="guide__FAQ__list">
                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 0 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(0)">
                        <h2 class="hero__title">À quel moment le vote d’une proposition est-il déclenché ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Le vote d’une proposition peut être déclenché par un administrateur ou un assesseur. De
                            manière générale, la planification du vote repose sur le nombre de demandes formelles
                            émises. Si la majorité des membres du groupe ont formulé une demande formelle le vote est
                            lancé automatiquement. Cependant, les administrateurs peuvent programmer un vote sans cette
                            contrainte.
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 1 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(1)">
                        <h2 class="hero__title">Puis-je donner mon avis de manière informelle ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Oui, il est tout à fait possible de donner son avis de façon informelle sur une proposition.
                            Pour cela, rendez-vous sur la page d’accueil de la proposition concernée. Au centre de la
                            page, vous trouverez un bloc intitulé « Donner mon avis » avec deux boutons : « J’aime » ou
                            « Je n’aime pas ». Ce choix n’est pas définitif : il s’agit simplement d’un indicateur
                            visible par les autres membres du groupe et aux organisateurs de façon anonyme. Cela ne
                            compte pas comme une demande formelle de vote.
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 2 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(2)">
                        <h2 class="hero__title">Où puis-je trouver les outils d’administration ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Pour les personnes disposant d’un rôle spécifique (administrateur, modérateur, etc.),
                            l’interface d’administration est accessible depuis la page d’accueil du groupe en cliquant
                            sur le bouton « ADMINPANEL ». Cet espace donne accès à l’ensemble des outils
                            d’administration, selon les droits associés à votre rôle au sein de la communauté.
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 3 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(3)">
                        <h2 class="hero__title">Qui peut inviter des nouveaux membres ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Seul l’administrateur a la possibilité d’inviter de nouveaux membres au sein du groupe. Il
                            peut le faire depuis l’onglet de gestion des membres dans l’interface d’administration.
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 4 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(4)">
                        <h2 class="hero__title">Est-il possible d’inviter plusieurs personnes en même temps ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Les administrateurs peuvent inviter plusieurs personnes en même temps. Pour cela, dans la
                            fenêtre d’invitation, il suffit de séparer les adresses e-mail par un point-virgule (« ; »).
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 5 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(5)">
                        <h2 class="hero__title">Quels sont les personnalisations disponibles dans les votes ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Pour l’instant, les modes de scrutin suivants sont disponibles : pour/contre, oui/non,
                            scrutin majoritaire à un tour, scrutin majoritaire à deux tours, et scrutin proportionnel.
                            Vous pouvez également définir la durée des phases de discussion et de vote, ainsi que
                            composer une liste de choix.
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 6 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(6)">
                        <h2 class="hero__title">Peut-il y avoir plusieurs administrateurs ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Un groupe peut tout à fait compter plusieurs administrateurs. Ils disposent tous des mêmes
                            droits, que ce soit pour prendre des décisions, promouvoir d’autres membres, ou gérer le
                            groupe.
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 7 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(7)">
                        <h2 class="hero__title">Chaque proposition créée est visible par tous les membres ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Lorsqu’une proposition est créée, elle est visible par tous les membres du groupe. Ainsi, si
                            vous créez une proposition, elle apparaîtra immédiatement dans la liste des propositions en
                            cours, accessible à tous.
                        </p>
                    </div>
                </div>

                <div class="guide__FAQ__list__question" :class="{ 'is-open': openIndex === 8 }">
                    <div class="guide__FAQ__list__question__title" @click="toggle(8)">
                        <h2 class="hero__title">Qu’est-ce qu’une demande formelle ?</h2>
                        <i class="material-icons guide__FAQ__list__question__icon">change_history</i>
                    </div>
                    <div class="guide__FAQ__list__question__text">
                        <p class="hero__subtitle guide__subtitle">
                            Une demande formelle consiste à solliciter le lancement d’un vote sur une proposition. Cela
                            ne reflète pas nécessairement votre avis sur la proposition elle-même. Si le nombre de
                            demandes formelles est suffisamment élevé, l’administrateur ou un assesseur pourra alors
                            déclencher le vote de la proposition concernée.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <Footer id="contact"></Footer>
</template>

<script setup>
const openIndex = ref(null)

function toggle(index) {
    openIndex.value = openIndex.value === index ? null : index
}

function scrollToFAQ() {
    const target = document.getElementById('faq')
    const navbar = document.getElementById('navbar')
    const offsetter = navbar.getBoundingClientRect().height + 35

    const y = target.getBoundingClientRect().top + window.scrollY - offsetter
    window.scrollTo({ top: y, behavior: 'smooth' })
}
</script>
