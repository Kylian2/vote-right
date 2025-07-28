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
                <div
                    class="surveys__about__informations__item surveys__about__informations__item--total"
                >
                    <p>Total de sondage</p>
                    <p>{{ surveysData?.['survey_count'] }}</p>
                </div>
                <div
                    class="surveys__about__informations__item surveys__about__informations__item--responses"
                >
                    <p>Nombre de réponses</p>
                    <p>{{ surveysData?.['survey_answers_count'] }}</p>
                </div>
                <!--<div
                    class="surveys__about__informations__item surveys__about__informations__item--average"
                >
                    <p>Taux moyen de réponse</p>
                    <p>78%</p>
                </div>-->
            </div>
        </section>

        <section>
            <h4>Tout les sondages</h4>

            <div class="surveys__list">
                <Survey v-for="survey in surveys" :survey="survey" />

                <p class="legend" v-if="false">
                    Vous n'avez pas encore créé de sondages
                </p>
            </div>
            <p v-if="!surveys || surveys.length === 0">Aucun sondages</p>
        </section>
    </main>
</template>
<script setup>
const surveys = ref()
const surveysData = ref()
const config = useRuntimeConfig()

onMounted(() => {
    fetchData()
})

const fetchData = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/surveys`, {
            credentials: 'include',
        })

        surveys.value = response
    } catch (error) {
        console.log('An error occured', error)
    }

    try {
        const response = await $fetch(`${config.public.baseUrl}/surveys/data`, {
            credentials: 'include',
        })

        surveysData.value = response
    } catch (error) {
        console.log('An error occured', error)
    }
}
</script>
