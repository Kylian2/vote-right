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
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option selected value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                </select>
                <p><b>Budget Total : </b> {{formatNumber(budget['CMY_budget_NB'])}} € /an max</p>
                <p><b>Budget Utilisé : </b> {{formatNumber(budget['CMY_used_budget_NB'])}} € /an</p>
                <p><b>Frais fixes :</b> {{formatNumber(budget['CMY_fixed_fees_NB'])}} € /an</p>
                <button class="btn btn--small" @click="editBudgetModale = true"> Modifier le budget maximal</button>
            </div>

            <div class="community__recap-themes">
                <h3>
                    Par thème
                </h3>
                <div>
                    <p v-for="theme, key in budget['CMY_budget_theme_NB']"><b>{{theme['THM_name_VC']}} : </b> {{theme['BUT_used_budget_NB']}} € /an (max: {{theme['BUT_amount_NB']}} €)</p>
                </div>
                <button class="btn btn--small" @click="editBudgetModale = true"> Modifier les budgets</button>
            </div>

        </section>
        <section class="community__main">

            <div class="community__adopted">
                <h3>Proposition adoptées</h3>
                <div>
                    <div v-for="proposal in adopted" class="proposal-card">
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
                    </div>
                    <p v-if="adopted.length === 0">Aucune proposition</p>
                </div>
            </div>

            <div class="community__ongoing">
                <h3>Action requise</h3>
                <div>
                    <div v-for="proposal, key in voted" class="proposal-card__wrapper">
                        <img class="proposal-card__image" src="/images/like.png" alt="like icon" >
                        <div class="proposal-card" :class="{'proposal-card--action': true}">
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
                        </div>
                        <div class="proposal-card__btns">
                            <button class="btn btn--small">Adopter</button>
                            <button class="btn btn--small">Refuser</button>
                        </div>
                    </div>
                    <p v-if="voted.length === 0">Aucune proposition</p>
                </div>
            </div>

            <div class="community__ongoing">
                <h3>Proposition en cours</h3>
                <div>
                    <div v-for="proposal in ongoing" class="proposal-card">
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
                    </div>
                    <p v-if="ongoing.length === 0">Aucune proposition</p>
                </div>
            </div>
        </section>
        <section class="community__actions">
            <div class="community__actions__btns">
                <button class="btn btn--small"> Ajouter un thème</button>
                <button class="btn btn--small"> Voir toutes les propositions</button>
                <button class="btn btn--small"> Modifier le groupe</button>
                <button class="btn btn--small"> Gérer les membres</button>
                <button class="btn btn--small"> Accéder aux outils de modérations</button>
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
                        <InputNumber :name="'budget'" :placeholder="budget['CMY_budget_NB']+''" :step="100"></InputNumber>
                        <p> € /an</p>
                    </div>
                </div>
                <div>
                    <p>Frais fixes :</p>
                    <div>
                        <InputNumber :name="'feesBudget'" :placeholder="budget['CMY_fixed_fees_NB']+''" :step="100"></InputNumber>
                        <p> € /an</p>
                    </div>
                </div>
            </div>

            <div class="edit-budget section-2">
                <div v-for="theme, key in budget['CMY_budget_theme_NB']">
                    <p><b>{{theme["THM_name_VC"]}} :</b></p>
                    <div>
                        <InputNumber :name="theme['THM_name_VC']+'Budget'" :placeholder="theme['BUT_amount_NB']+''" :step="100"></InputNumber>
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
</template>
<script setup>

definePageMeta({
  middleware: ["auth"]
})

const config = useRuntimeConfig();
const route = useRoute();

const community = ref({});
const ongoing = ref([]);
const adopted = ref([]);
const voted = ref([]);
const budget = ref({});
const period = ref('2024');

const budgetToastValid = useState('budgetToastValidUp', () => false);
const budgetToastError = useState('budgetToastErrorUp', () => false);
const budgetToastAlert = useState('budgetToastAlertUp', () => false);

const editBudgetModale = useState('editBudgetModal', () => false);

const formatNumber = (number) => {
    return isNaN(number) ? 0 : new Intl.NumberFormat('fr-FR').format(number);
}

const fetchData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        });

        community.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/ongoing`, {
            credentials: 'include',
        });

        ongoing.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/adopted?period=${period.value}`, {
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
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`, {
            credentials: 'include',
        });

        budget.value = response;
        
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

onMounted(() => {
    fetchData();
})
</script>