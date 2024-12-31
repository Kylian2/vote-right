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
                    <option v-for="p in periods" :value="p" :selected="new Date().getFullYear() == p">{{p}}</option>
                </select>
                <p><b>Budget Total : </b> {{formatNumber(budget['CMY_budget_NB'])}} € /an max</p>
                <p><b>Budget Utilisé : </b> {{formatNumber(budget['CMY_used_budget_NB'])}} € /an</p>
                <p><b>Frais fixes :</b> {{formatNumber(budget['CMY_fixed_fees_NB'])}} € /an</p>
                <button v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == DECIDER" class="btn btn--small" @click="editBudgetModale = true"> Modifier le budget maximal</button>
            </div>

            <div class="community__recap-themes">
                <h3>
                    Par thème
                </h3>
                <div>
                    <p v-for="theme, key in budget['CMY_budget_theme_NB']"><b>{{theme['THM_name_VC']}} : </b> {{theme['BUT_used_budget_NB']}} € /an (max: {{theme['BUT_amount_NB']}} €)</p>
                </div>
                <button v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == DECIDER" class="btn btn--small" @click="editBudgetModale = true"> Modifier les budgets</button>
            </div>

        </section>
        <section class="community__main">

            <div class="community__adopted">
                <h3>Proposition adoptées</h3>
                <div>
                    <NuxtLink :to="`/proposals/${proposal['PRO_id_NB']}`" v-for="proposal in adopted" class="proposal-card">
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
                <h3>Action requise</h3>
                <div>
                    <div v-for="proposal, key in voted" class="proposal-card__wrapper">
                        <img class="proposal-card__image" src="/images/like.png" alt="like icon" v-if="false">
                        <NuxtLink class="proposal-card" :class="{'proposal-card--action': false}" :to="`/proposals/${proposal['PRO_id_NB']}`">
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
                        <div v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == DECIDER"  class="proposal-card__btns" :class="{'proposal-card__btns': false}">
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
                <button v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == DECIDER" class="btn btn--small" @click="addThemeModal = true"> Ajouter un thème</button>
                <button class="btn btn--small"> Voir toutes les propositions</button>
                <NuxtLink v-if="role['MEM_role_NB'] == ADMIN" class="btn btn--small" :to="`/communities/${$route.params.id}/modify`"> Modifier le groupe </NuxtLink>
                <NuxtLink v-if="role['MEM_role_NB'] == ADMIN" class="btn btn--small" :to="`/communities/${$route.params.id}/members`"> Gérer les membres</NuxtLink>
                <NuxtLink v-if="role['MEM_role_NB'] == ADMIN || role['MEM_role_NB'] == MODERATOR" class="btn btn--small" :to="`/communities/${$route.params.id}/moderation`"> Accéder aux outils de modérations</NuxtLink>
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
    name="editBudget"
    ok-text="Valider les changements"
    cancel-text="Annuler"
    :disable-valid="!budgetValid"
    :before-ok="() => {
        updateBudget();
    }"
    >
        <template #title>Modifier les budgets</template>
        <template #body>
            <div class="edit-budget">
                <div>
                    <p><b>Budget max :</b></p>
                    <div>
                        <InputNumber name="budget" :placeholder="budget['CMY_budget_NB']+''" :step="100"
                        :rules="[
                            (v) => (v > budget['CMY_fixed_fees_NB'] + budget['CMY_used_budget_NB']) || 'Budget trop bas'
                        ]"
                        ></InputNumber>
                        <p> € /an</p>
                    </div>
                </div>
                <div>
                    <p>Frais fixes :</p>
                    <div>
                        <InputNumber name="feesBudget" :placeholder="budget['CMY_fixed_fees_NB']+''" :step="100"
                        :rules="[
                            (v) => (v < budget['CMY_budget_NB'] - budget['CMY_used_budget_NB']) || 'Frais trop haut'
                        ]"
                        ></InputNumber>
                        <p> € /an</p>
                    </div>
                </div>
            </div>

            <div class="edit-budget section-2">
                <div v-for="theme, key in budget['CMY_budget_theme_NB']">
                    <p><b>{{theme["THM_name_VC"]}} :</b></p>
                    <div>
                        <InputNumber :name="theme['THM_name_VC']+'Budget'" :placeholder="theme['BUT_amount_NB']+''" :step="100"
                        :rules="[
                            (v) => (v < budgetTotalTheme - theme['BUT_amount_NB']) || 'Le budget est trop élévé'
                        ]"></InputNumber>
                        <p> € /an</p>
                    </div>
                </div>
            </div>

        </template>
    </Modal>

    <Toast 
        name="budgetToastValid" 
        :type="3" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Budget Modifié !
    </Toast>
    <Toast 
        name="budgetToastError" 
        :type="1" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Erreur lors de la modification du budget
    </Toast>
    <Toast 
        name="budgetToastAlert" 
        :type="2" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Aucune modification a appliquer
    </Toast>

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
                    <p><b>{{ budget['CMY_budget_NB'] }}</b> € /an</p>
                </div>
                <div>
                    <p>Budget utilisé :</p>
                    <p><b>{{ budget['CMY_used_budget_NB'] }}</b> € /an</p>
                </div>
                <div>
                    <p>Budget restant non attribué :</p>
                    <p><b>{{ budget['CMY_budget_NB']  - budgetTotalTheme - budget['CMY_fixed_fees_NB'] }}</b> € /an</p>
                </div>
            </div>

            <div class="ajouter-theme">
                <p>Nom du thème :</p>
                <Input class="ajouter-theme__name" type="text" name="nameNewTheme" no-label placeholder="Entrez le nom"></Input>
                <div class="ajouter-theme__budget">
                    <InputNumber class="ajouter-theme__budget__input" type="text" name="budgetNewTheme" no-label placeholder="Entrez le budget" :step="100" :min="0"
                    :rules="[
                        (v) => v < budget['CMY_budget_NB']  - budgetTotalTheme - budget['CMY_fixed_fees_NB'] || 'Le budget est trop élevé'
                    ]"
                    ></InputNumber>
                    <p> € /an</p>
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
const period = ref('2024');
const periods = ref([]);
const role = ref({});

const budgetToastValid = useState('budgetToastValidUp', () => false);
const budgetToastError = useState('budgetToastErrorUp', () => false);
const budgetToastAlert = useState('budgetToastAlertUp', () => false);

const editBudgetModale = useState('editBudgetModal', () => false);
const budgetTotalTheme = computed(() => {
    let somme = 0;
    for (let i = 0; i < budget.value['CMY_budget_theme_NB']?.length; i++){
        somme+=budget.value['CMY_budget_theme_NB'][i]['BUT_amount_NB'];
    }
    return somme;
})

const communityBudget = useState('budget');
const communityBudgetValid = useState('budgetValid', ()=>true);
const feesBudgetValid = useState('feesBudgetValid');

//verifie l'ensemble des budgets entrés par l'utilisateur
const budgetValid = computed(() => {
    let valid = true;
    valid = valid && feesBudgetValid.value && communityBudgetValid.value;
    for (let i = 0; i < budget.value['CMY_budget_theme_NB']?.length; i++){
        const bud = useState(budget.value['CMY_budget_theme_NB'][i]['THM_name_VC']+'BudgetValid');
        valid = valid && bud.value;
    }
    return valid;
}) 


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

    }catch(error){
        console.log("An error occured", error);
    }
}

const updateBudget = async () => {
    let raw = {};
    const totalBudget = useState('budget');
    if(totalBudget.value != ""){
        raw['0'] = totalBudget.value;
    }
    const feesBudget = useState('feesBudget');
    if(feesBudget.value != ""){
        raw['-1'] = feesBudget.value;
    }

    budget.value['CMY_budget_theme_NB'].forEach(theme => {
        const budgetTheme = useState(theme['THM_name_VC']+'Budget');
        if(budgetTheme.value != ""){
            raw[theme['THM_id_NB']] = budgetTheme.value;
        }
    });
    if(Object.keys(raw).length === 0){
        budgetToastAlert.value = true;
        return;
    }
    try{
        const response1 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`, {
            method: 'POST',
            credentials: 'include',
            body: raw
        });

        budgetToastValid.value = response1 === true;
        budgetToastError.value = response1 !== true;

        const response2 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`, {
            credentials: 'include',
        });

        budget.value = response2;
        
    } catch(error) {
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

onMounted(() => {
    fetchData();
})
</script>