<template>

    <Header></Header>

    <h1>Club Espace</h1>

    <main class="community">

        <section class="community__lateral">

            <div class="community__recap-budget">
                <h3>
                    Récapitulatif
                </h3>
                <p><b>Budget Total : </b> 79 000 € /an max</p>
                <p><b>Budget Utilisé : </b> 79 000 € /an</p>
                <button class="btn btn--small"> Modifier le budget maximal</button>
            </div>

            <div class="community__recap-themes">
                <h3>
                    Par thème
                </h3>
                <div class="community__recap-themes__list">
                    <p><b>Animation : </b> 79 000 € /an (max: 5000 €)</p>
                    <p><b>Aventure : </b> 15 000 € /an (max: 35000 €)</p>
                    <p><b>Sciences : </b> 6 700 € /an (max: 19000 €)</p>
                </div>
                <div>
                    <p><b>Frais fixes :</b> 4200 € /an</p>
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
                    <div v-for="x in 10" class="community-card">
                        <div>
                            <p class="community-card__theme">Animation</p>
                            <p>Journée portes ouvertes</p>
                        </div>
                        <div>
                            <p>32/23</p>
                            <p>1300€</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="community__ongoing">
                <h3>Action requise</h3>
                <div>
                    <div v-for="x in 10" class="community-card__wrapper">
                        <img src="/images/like.png" alt="like icon" v-if="x%2">
                        <div class="community-card" :class="{'community-card--action': x%2}">
                            <div>
                                <p class="community-card__theme">Animation</p>
                                <p>Journée portes ouvertes</p>
                            </div>
                            <div>
                                <p>32/23</p>
                                <p>1300€</p>
                            </div>
                        </div>
                        <button v-if="x%2" class="btn btn--small">Adopter</button>
                        <button v-if="x%2" class="btn btn--small">Refuser</button>
                    </div>
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

const fetchData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/ongoing`, {
            credentials: 'include',
        });

        ongoing.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

onMounted(() => {
    fetchData();
})
</script>