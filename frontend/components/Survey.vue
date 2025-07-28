<template>
    <div class="survey__card" :class="colorToClass(survey['SUR_color_VC'])">
        <div class="survey__card__header">
            <h5>{{ survey['SUR_title_VC'] }}</h5>
            <div>
                <p class="survey__card__creator">
                    Créé par {{ survey['SUR_user_USER']['USR_firstname_VC'] }}
                </p>
                <p class="survey__card__status" :class="statusToClass(status)">
                    {{ statusToText(status) }}
                </p>
            </div>
        </div>
        <p>
            {{ survey['SUR_description_TXT'] }}
        </p>
        <div class="survey__card__progress">
            <div>
                <p><b>Participation totale</b></p>
                <p>
                    <b
                        >{{
                            (
                                (100 * survey['SUR_answer_count_NB']) /
                                survey['SUR_participant_count_NB']
                            ).toFixed(0)
                        }}%</b
                    >({{ survey['SUR_answer_count_NB'] }}/{{
                        survey['SUR_participant_count_NB']
                    }})
                </p>
            </div>
            <div class="survey__card__progress__bar">
                <span
                    class="survey__card__progress__indicator"
                    :style="{
                        width:
                            (
                                (100 * survey['SUR_answer_count_NB']) /
                                survey['SUR_participant_count_NB']
                            ).toFixed(0) + '%',
                    }"
                ></span>
            </div>
        </div>
        <NuxtLink :to="`/survey/${survey['SUR_id_NB']}`" class="btn btn--full"
            >Voir le sondage</NuxtLink
        >
    </div>
</template>
<script setup>
const props = defineProps({
    survey: {
        type: Object,
        required: true,
    },
})

const status = computed(() => {
    const start = new Date(props.survey['SUR_start_DATE'])
    const end = new Date(props.survey['SUR_end_DATE'])
    const now = new Date()
    if (now < start) {
        return 'soon'
    } else if (now >= start && now <= end) {
        return 'ongoing'
    } else {
        return 'finished'
    }
})

const statusToClass = (status) => {
    switch (status) {
        case 'ongoing':
            return 'survey__card__status--ongoing'
        case 'finished':
            return 'survey__card__status--finished'
        case 'soon':
            return 'survey__card__status--soon'
        default:
            return ''
    }
}

const statusToText = (status) => {
    switch (status) {
        case 'ongoing':
            return 'En cours'
        case 'finished':
            return 'Terminé'
        case 'soon':
            return 'Fini bientôt'
        default:
            return ''
    }
}

const colorToClass = (color) => {
    return `survey__card--${color.slice(1)}` // Remove the '#' from the color code
}
</script>
