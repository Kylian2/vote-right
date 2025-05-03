<template>
    <Header></Header>
    <h1>{{community["CMY_name_VC"]}}</h1>
    <main class="community">

        <section class="community__lateral">

            <div class="community__recap-budget">
                <h3>
                    Récapitulatif
                </h3>
                <select v-model="period" @change="fetchDataByPeriod()">
                    <option v-for="p in periods" :value="p">{{p}}</option>
                </select>
                <p><b>Budget Total : </b> {{formatNumber(budget['CMY_budget_NB'])}} € max</p>
                <p><b>Budget Utilisé : </b> {{formatNumber(budget['CMY_used_budget_NB'])}} €</p>
                <p><b>Frais fixes :</b> {{formatNumber(budget['CMY_fixed_fees_NB'])}} €</p>
            </div>

            <div class="community__recap-themes">
                <h3>
                    Par thème
                </h3>
                <div>
                    <p v-for="theme, key in budget['CMY_budget_theme_NB']"><b>{{theme['THM_name_VC']}} : </b> {{theme['BUT_used_budget_NB']}} € (max: {{theme['BUT_amount_NB']}} €)</p>
                    <p v-if="budget['CMY_budget_theme_NB']?.length === 0">Il n'y a aucun thème, vous pouvez en <span class="underline pointer" @click="addThemeModal = true">ajouter un</span>.</p>
                </div>
            </div>

        </section>
        <section class="community__main">

            <div class="community__adopted">
                <h3>Proposition adoptées</h3>
                <div>
                    <NuxtLink :to="`/proposals/${proposal['PRO_id_NB']}`" v-for="proposal in adopted" class="proposal-card proposal-card">
                        <div>
                            <p class="proposal-card__theme">{{proposal['PRO_theme_VC']}}</p>
                            <p class="proposal-card__title">{{proposal['PRO_title_VC']}}</p>
                        </div>
                        <div>
                            <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                            <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                        </div>
                        <div>
                            <p class="proposal-card__theme">{{proposal['PRO_theme_VC']}}</p>
                            <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                            <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                        </div>
                        <div>
                            <p class="proposal-card__title">{{proposal['PRO_title_VC']}}</p>
                        </div>
                    </NuxtLink>
                    <p v-if="adopted.length === 0">Aucune proposition</p>
                </div>
            </div>

            <div class="community__ongoing">
                <h3>En attente d'adoption</h3>
                <div>
                    <div v-for="proposal, key in voted" class="proposal-card__wrapper">
                        <img class="proposal-card__image" src="/images/like.png" alt="like icon" v-if="false">
                        <NuxtLink class="proposal-card" :class="{'proposal-card--action': (best == proposal['PRO_id_NB']) }" :to="`/proposals/${proposal['PRO_id_NB']}`">
                            <div>
                                <p class="proposal-card__theme">{{proposal['PRO_theme_VC']}}</p>
                                <p class="proposal-card__title">{{proposal['PRO_title_VC']}}</p>
                            </div>
                            <div>
                                <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                                <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                            </div>
                            <div>
                                <p class="proposal-card__theme">{{proposal['PRO_theme_VC']}}</p>
                                <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                                <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                            </div>
                            <div>
                                <p class="proposal-card__title">{{proposal['PRO_title_VC']}}</p>
                            </div>
                        </NuxtLink>
                        <div v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == DECIDER"  class="proposal-card__btns" :class="{'proposal-card__btns--action': (best == proposal['PRO_id_NB'])}">
                            <button class="btn btn--small" @click="approveProposal(true, proposal['PRO_id_NB'])">Adopter</button>
                            <button class="btn btn--small" @click="approveProposal(false, proposal['PRO_id_NB'])">Refuser</button>
                        </div>
                    </div>
                    <p v-if="voted.length === 0">Aucune proposition</p>
                </div>
            </div>

            <div class="community__ongoing">
                <h3>Proposition en cours</h3>
                <div>
                    <NuxtLink v-for="proposal in ongoing" class="proposal-card" :to="`/proposals/${proposal['PRO_id_NB']}`">
                        <div>
                            <p class="proposal-card__theme">{{proposal['PRO_theme_VC']}}</p>
                            <p class="proposal-card__title">{{proposal['PRO_title_VC']}}</p>
                        </div>
                        <div>
                            <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                            <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                        </div>
                        <div>
                            <p class="proposal-card__theme">{{proposal['PRO_theme_VC']}}</p>
                            <p>{{proposal['PRO_like_NB']+proposal['PRO_love_NB']}}/{{proposal['PRO_dislike_NB']+proposal['PRO_hate_NB']}}</p>
                            <p>{{proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '---'}} €</p>
                        </div>
                        <div>
                            <p class="proposal-card__title">{{proposal['PRO_title_VC']}}</p>
                        </div>
                    </NuxtLink>
                    <p v-if="ongoing.length === 0">Aucune proposition</p>
                </div>
            </div>
        </section>
        <section class="community__actions">
            <div class="community__actions__btns">
                <NuxtLink v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == DECIDER" class="btn btn--small" :to="`/communities/${$route.params.id}/budget`">Détails du budget</NuxtLink>
                <button v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == DECIDER" class="btn btn--small" @click="addThemeModal = true"> Ajouter un thème</button>
                <NuxtLink class="btn btn--small" :to="`/communities/${$route.params.id}/proposals`">Voir toutes les propositions</NuxtLink>
                <NuxtLink v-if="role['MEM_role_NB'] == ADMIN" class="btn btn--small" :to="`/communities/${$route.params.id}/edit`"> Modifier le groupe </NuxtLink>
                <NuxtLink v-if="role['MEM_role_NB'] == ADMIN" class="btn btn--small" :to="`/communities/${$route.params.id}/members`"> Gérer les membres</NuxtLink>
                <NuxtLink v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == MODERATOR" class="btn btn--small" :to="`/communities/${$route.params.id}/moderation`"> Accéder aux outils de modérations</NuxtLink>
                <NuxtLink class="btn btn--small" :to="`${config.public.appUrl}/community/${$route.params.id}`"> Retourner au groupe</NuxtLink>
            </div>

            <div class="community__actions__legend">
                <div>
                    <img src="/images/like.png" alt="">
                    <p class="legend">: proposition appréciée</p>
                </div>
            </div>
        </section>
    </main>

    <Modal
    name="addTheme"
    ok-text="Ajouter le theme"
    cancel-text="Annuler"
    :before-ok="() => {
        addTheme();
        }"
    :disable-valid="!budgetNewThemeValid"
    >
        <template #title>Ajouter un thème</template>
        <template #body>

            <div class="edit-budget">
                <div>
                    <p><b>Budget max :</b></p>
                    <p><b>{{ budget['CMY_budget_NB'] }}</b> €</p>
                </div>
                <div>
                    <p>Budget utilisé :</p>
                    <p><b>{{ budget['CMY_used_budget_NB'] }}</b> €</p>
                </div>
                <div>
                    <p>Budget restant non attribué :</p>
                    <p><b>{{ budget['CMY_budget_NB']  - budgetTotalTheme - budget['CMY_fixed_fees_NB'] }}</b> €</p>
                </div>
            </div>

            <div class="ajouter-theme">
                <p>Nom du thème :</p>
                <Input class="ajouter-theme__name" type="text" name="nameNewTheme" no-label placeholder="Entrez le nom"></Input>
                <div class="ajouter-theme__budget">
                    <InputNumber class="ajouter-theme__budget__input" type="text" name="budgetNewTheme" no-label placeholder="Entrez le budget" :step="100" :min="0"
                    :rules="[
                        (v) => v <= budget['CMY_budget_NB']  - budgetTotalTheme - budget['CMY_fixed_fees_NB'] || 'Le budget est trop élevé'
                    ]"
                    ></InputNumber>
                    <p> €</p>
                </div>
            </div>
            <p class="legende mt20">Le budget défini pour le thème sera ajouté pour la période en cours ({{ new Date().getFullYear() }}).</p>

        </template>
    </Modal>

    <Toast 
        name="addThemeValid" 
        :type="3" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Thème ajouté
    </Toast>

    <Toast 
        name="forbidden" 
        :type="1" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Vous n'avez pas les droits pour effectuer cette action
    </Toast>

    <Toast 
        name="limit" 
        :type="1" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Le budget de la proposition est superieur à celui de son thème
    </Toast>
</template>
<script setup>

const ADMIN = 1;
const DECIDER = 2;
const MODERATOR = 4;

definePageMeta({
  middleware: ["auth", "managed"]
})

const config = useRuntimeConfig();
const route = useRoute();

const community = ref({});
const ongoing = ref([]);
const adopted = ref([]);
const voted = ref([]);
const budget = ref({});
const period = ref(new Date().getFullYear());
const periods = ref([]);
const role = ref({});
const communityBudget = ref(0);

const formatNumber = (number) => {
    return isNaN(number) ? 0 : new Intl.NumberFormat('fr-FR').format(number);
}

const fetchData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        });

        community.value = response;

        const response2 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/ongoing`, {
            credentials: 'include',
        });

        ongoing.value = response2;

        const response3 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/adopted?period=${period.value}`, {
            credentials: 'include',
        });

        adopted.value = response3;

        const response4 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/voted`, {
            credentials: 'include',
        });

        voted.value = response4;

        const response5 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`, {
            credentials: 'include',
        });

        budget.value = response5;
        communityBudget.value = budget.value['CMY_budget_NB']

        const response6 = await $fetch(`${config.public.baseUrl}/users/me/role/${route.params.id}/`, {
            credentials: 'include',
        });

        role.value = response6;

        const response7 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/periods`, {
            credentials: 'include',
        });

        periods.value = response7;
        periods.value.forEach(p => {
            if(new Date().getFullYear() == p){
                period.value = p;
            }
        });

        algorithm();
        
    } catch(error) {
        console.log("An error occured", error);
    }

}

const fetchDataByPeriod = async () =>{
    try{
        const bud = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`, {
            credentials:'include', 
        });

        budget.value = bud;

        const ado = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/adopted?period=${period.value}`, {
            credentials:'include', 
        });

        adopted.value = ado;

        algorithm();

    }catch(error){
        console.log("An error occured", error);
    }
}

const addThemeModal = useState('addThemeModal', () => false);
const addThemeToast = useState('addThemeValidUp', () => false);
const nameNewTheme = useState('nameNewTheme');
const budgetNewTheme = useState('budgetNewTheme');
const budgetNewThemeValid = useState('budgetNewThemeValid');

const addTheme = async () => {
    try{
        const response1 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/themes`, {
            method: 'POST',
            credentials: 'include',
            body: {
                name: nameNewTheme.value,
                budget: budgetNewTheme.value,
            }
        });

        addThemeToast.value = 'THM_id_NB' in response1 && !isNaN(response1['THM_id_NB']);

        const response2 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`, {
            credentials: 'include',
        });

        budget.value = response2;
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

const limit = useState('limitUp');
const forbidden = useState('forbiddenUp');

const approveProposal = async (status, proposal) => {
    try{
        await $fetch(`${config.public.baseUrl}/proposals/${proposal}/approve`, {
            method: 'POST',
            credentials: 'include',
            body: {
                approve: status
            }
        })

        if(status){
            const bud = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`, {
                credentials: 'include',
            });

            budget.value = bud;
        }

        const v = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/voted`, {
            credentials: 'include',
        });
        voted.value = v;

        const a = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/adopted?period=${period.value}`, {
            credentials: 'include',
        });
        adopted.value = a;
    }catch (error){
        console.log('An unexptected error occured : ', error);
        if(error.status === 403){
            forbidden.value = true;
        }
        if(error.status === 400){
            limit.value = true;
        }
    }
}

const best = ref();

const algorithm = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/algo?period=${period.value}`, {
            method: 'GET',
            credentials: 'include',
        });

        if(!response){
            best.value = 0;
            return;
        }

        best.value = response[0]["PRO_id_NB"];
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

onMounted(() => {
    fetchData();
})

onBeforeUnmount(() => {
    const from = useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    }); 
    from.value = {
        name: route.name,
        href: route.href,
    }
})
</script>