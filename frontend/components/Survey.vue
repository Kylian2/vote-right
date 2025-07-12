<template>
    <div class="survey__card" :class="colorToClass(survey['SUR_color_VC'])">   
        <div class="survey__card__header">
            <h5>{{ survey["SUR_question_VC"] }}</h5>
            <div>
                <p class="survey__card__creator">Créé par {{ survey["SUR_user_VC"] }}</p>
                <p class="survey__card__status" :class="statusToClass(survey['SUR_status_VC'])">{{ statusToText(survey["SUR_status_VC"]) }}</p>
            </div>
        </div>
        <p>
            {{ survey["SUR_description_TXT"] }}
        </p>
        <div class="survey__card__progress">
            <div>
                <p><b>Participation totale</b></p>
                <p><b>{{ (100*survey["SUR_vote_count_NB"]/survey["SUR_participants_NB"]).toFixed(0) }}%</b>({{ survey["SUR_vote_count_NB"] }}/ {{ survey["SUR_participants_NB"] }})</p>
            </div>
            <div class="survey__card__progress__bar">
                <span class="survey__card__progress__indicator"></span>
            </div>
        </div>
        <NuxtLink to="/survey/1" class="btn btn--full">Voir le sondage</NuxtLink>
    </div>
</template>
<script setup>
const props = defineProps({

survey: {
    type: Object,
    required: true,
}

})

const statusToClass = (status) => {
    switch (status) {
        case "ongoing":
            return "survey__card__status--ongoing";
        case "finished":
            return "survey__card__status--finished";
        case "soon":
            return "survey__card__status--soon";
        default:
            return "";
    }
}

const statusToText = (status) => {
    switch (status) {
        case "ongoing":
            return "En cours";
        case "finished":
            return "Terminé";
        case "soon":
            return "Fini bientôt";
        default:
            return "";
    }
}

const colorToClass = (color) => {
    return `survey__card--${color.slice(1)}`; // Remove the '#' from the color code
}
</script>