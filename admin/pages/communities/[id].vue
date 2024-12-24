<template>

    <Header></Header>

    <h1>Club Espace</h1>

    <main class="community">

        <section class="community__lateral">

            <div class="community__recap-budget">
                <h3>
                    Récapitulatif
                </h3>
                <p><b>Budget Total : </b> {{formatNumber(budget['CMY_budget_NB'])}} € /an max</p>
                <p><b>Budget Utilisé : </b> {{formatNumber(budget['CMY_used_budget_NB'])}} € /an</p>
                <p><b>Frais fixes :</b> {{formatNumber(budget['CMY_fixed_fees_NB'])}} € /an</p>
                <button class="btn btn--small"> Modifier le budget maximal</button>
            </div>

            <div class="community__recap-themes">
                <h3>
                    Par thème
                </h3>
                <div>
                    <p v-for="theme, key in budget['CMY_budget_theme_NB']"><b>{{theme['THM_name_VC']}} : </b> {{theme['BUT_used_budget_NB']}} € /an (max: {{theme['BUT_amount_NB']}} €)</p>
                </div>
                <button class="btn btn--small"> Modifier les budgets</button>
            </div>

            <div class="community__actions">
                <button class="btn btn--small"> Ajouter un thème</button>
                <button class="btn btn--small"> Voir toutes les propositions</button>
                <button class="btn btn--small"> Modifier le groupe</button>
                <button class="btn btn--small"> Gérer les membres</button>
                <button class="btn btn--small"> Accéder aux outils de modérations</button>
            </div>

            <div class="community__legend">
                <div>
                    <img src="/images/like.png" alt="">
                    <p class="legend">: proposition appréciée</p>
                </div>
            </div>
        </section>
        <section class="community__main">

            <div class="community__adopted">
                <h3>Proposition adoptées</h3>
                <div>
                    <div v-for="proposal in adopted" class="community-card">
                        <div>
                            <p class="community-card__theme">{{proposal['PRO_theme_VC']}}</p>
                            <p>{{proposal['PRO_title_VC']}}</p>
                        </div>
                        <div>
                            <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                            <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                        </div>
                    </div>
                    <p v-if="adopted.length === 0">Aucune proposition</p>
                </div>
            </div>

            <div class="community__ongoing">
                <h3>Action requise</h3>
                <div>
                    <div v-for="proposal, key in voted" class="community-card__wrapper">
                        <img src="/images/like.png" alt="like icon" v-if="false">
                        <div class="community-card" :class="{'community-card--action': false}">
                            <div>
                                <p class="community-card__theme">{{proposal["PRO_theme_VC"]}}</p>
                                <p>{{proposal["PRO_title_VC"]}}</p>
                            </div>
                            <div>
                                <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                                <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                            </div>
                        </div>
                        <button class="btn btn--small">Adopter</button>
                        <button class="btn btn--small">Refuser</button>
                    </div>
                    <p v-if="voted.length === 0">Aucune proposition</p>
                </div>
            </div>

            <div class="community__ongoing">
                <h3>Proposition en cours</h3>
                <div>
                    <div v-for="proposal in ongoing" class="community-card">
                        <div>
                            <p class="community-card__theme">{{proposal['PRO_theme_VC']}}</p>
                            <p>{{proposal['PRO_title_VC']}}</p>
                        </div>
                        <div>
                            <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                            <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                        </div>
                    </div>
                    <p v-if="ongoing.length === 0">Aucune proposition</p>
                </div>
            </div>
        </section>
    </main>

</template>
<script setup>

definePageMeta({
  middleware: ["auth"]
})

const config = useRuntimeConfig();
const route = useRoute();

const ongoing = ref([]);
const adopted = ref([]);
const voted = ref([]);
const budget = ref({});

const formatNumber = (number) => {
    return isNaN(number) ? 0 : new Intl.NumberFormat('fr-FR').format(number);
}

const fetchData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/ongoing`, {
            credentials: 'include',
        });

        ongoing.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/adopted?period=2024`, {
            credentials: 'include',
        });

        adopted.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/voted`, {
            credentials: 'include',
        });

        voted.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=2024`, {
            credentials: 'include',
        });

        budget.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

onMounted(() => {
    fetchData();
})
</script>