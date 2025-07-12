<template>
    <Header type="logged" actif="sondages"></Header>

    <div class="page__title surveys__title">
        <h1>Sondages</h1>
        <NuxtLink class="btn--full btn--responsive-hidden" to="/survey/new">
            Créer mon sondage
        </NuxtLink>
        <NuxtLink class="btn--full btn--plus btn--responsive" to="/survey/new">
            <span class="btn--plus__plus"></span>
            <span class="btn--plus__plus"></span>
        </NuxtLink>
    </div>

    <main class="surveys">

        <section class="surveys__about">
            <h4>À propos de mes sondages</h4>
            <div class="surveys__about__informations">
            <div class="surveys__about__informations__item surveys__about__informations__item--total">
                <p>Total de sondage</p>
                    <p>64</p>
                </div>
                <div class="surveys__about__informations__item surveys__about__informations__item--responses">
                    <p>Nombre de réponses</p>
                    <p>1554</p>
                </div>
                <div class="surveys__about__informations__item surveys__about__informations__item--average">
                    <p>Taux moyen de réponse</p>
                    <p>78%</p>
                </div>
            </div>
        </section>

        <section>
            <h4>Tout les sondages</h4>

            <div class="surveys__list">
                <Survey v-for="survey in surveys" :survey="survey"/>

                <p class="legend" v-if="false"> Vous n'avez pas encore créé de sondages </p>
            </div>
        </section>

    </main>

</template>
<script setup>

const surveys = [
  {
    SUR_question_VC: "Quel genre de film préférez-vous ?",
    SUR_user_VC: "Pierre",
    SUR_status_VC: "ongoing",
    SUR_description_TXT: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
    SUR_vote_count_NB: 43,
    SUR_participants_NB: 45,
    SUR_color_VC: "#DE3D59"
  },
  {
    SUR_question_VC: "Quelle est votre boisson préférée ?",
    SUR_user_VC: "Claire",
    SUR_status_VC: "finished",
    SUR_description_TXT: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    SUR_vote_count_NB: 30,
    SUR_participants_NB: 50,
    SUR_color_VC: "#8700CF"
  },
  {
    SUR_question_VC: "Quel sport regardez-vous le plus ?",
    SUR_user_VC: "Lucas",
    SUR_status_VC: "ongoing",
    SUR_description_TXT: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem.",
    SUR_vote_count_NB: 40,
    SUR_participants_NB: 50,
    SUR_color_VC: "#5AB7EE"
  },
  {
    SUR_question_VC: "Quelle saison préférez-vous ?",
    SUR_user_VC: "Sophie",
    SUR_status_VC: "finished",
    SUR_description_TXT: "At vero eos et accusamus et iusto odio dignissimos ducimus.",
    SUR_vote_count_NB: 45,
    SUR_participants_NB: 50,
    SUR_color_VC: "#FDBE55"
  },
  {
    SUR_question_VC: "Quel type de musique écoutez-vous le plus ?",
    SUR_user_VC: "Antoine",
    SUR_status_VC: "soon",
    SUR_description_TXT: "Ut enim ad minima veniam, quis nostrum exercitationem ullam.",
    SUR_vote_count_NB: 25,
    SUR_participants_NB: 50,
    SUR_color_VC: "#5AB7EE"
  }
];

</script>