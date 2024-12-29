<template>
<Header></Header>
<NuxtLink class="back" :to="`/communities/${proposal['PRO_community_NB']}`">Retour au groupe</NuxtLink>
<h1>{{ proposal['PRO_title_VC'] }}</h1>
<main class="proposal">
<section class="section-1">
    <div v-if="initiator" class="proposal__initiator">
        <p class="profil-initials">{{ getInitials(initiator['USR_firstname_VC'], initiator['USR_lastname_VC']) }}</p>
        <div>
            <p>{{ initiator['USR_firstname_VC'] }} <b>{{ initiator['USR_lastname_VC'] }}</b></p>
            <p class="legende">le {{ formatDate(new Date(proposal['PRO_creation_DATE'])) }}</p>
        </div>
    </div>
    <div v-if="proposal['PRO_status_VC'] != 'En cours'">
        <p>
            La proposition a été {{ proposal['PRO_status_VC']?.toLowerCase() }}.
        </p>
    </div>
    <div v-if="proposal" class="proposal__informations">
        <p v-if="proposal['PRO_theme_VC']"><img src="/images/icons/theme.svg" alt="icons-theme">{{ proposal['PRO_theme_VC'] }}</p>
        <div v-if="budgetTheme">
            <p :class="{error: (proposal['PRO_budget_NB'] > (budgetTheme['BUT_amount_NB'] - budgetTheme['BUT_used_budget_NB']))}">
                <img src="/images/icons/budget.svg" alt="icons-theme">
                {{ proposal['PRO_budget_NB'] ? proposal['PRO_budget_NB'] : '- - -'}} €
            </p>
                <p class="edit" @click="editBudgetModal = true">Modifier</p>
        </div>
        <p v-if="proposal['PRO_location_VC']"><img src="/images/icons/location.svg" alt="icons-theme">{{ proposal['PRO_location_VC'] }}</p>
        <p v-if="proposal['PRO_period_YEAR']"><img src="/images/icons/date.svg" alt="icons-theme">{{ proposal['PRO_period_YEAR'] }}</p>
    </div>

    <div class="proposal__opinions" v-if="reactions">
        <h3>Avis</h3>
        <p>
            {{ (reactions["nblove"] + reactions["nblike"]) }} aiment / {{ (reactions["nbhate"] + reactions["nbdislike"]) }} n'aiment pas
        </p>
    </div>
    <div class="proposal__actions">
        <button class="btn btn--small">Plannification du vote</button>
        <div v-if="proposal['PRO_status_VC'] ==='En cours' && votesAreFinished && budgetTheme">
            <button class="btn btn--small btn--full" @click="approveProposal(true)" :disabled="(proposal['PRO_budget_NB'] > (budgetTheme['BUT_amount_NB'] - budgetTheme['BUT_used_budget_NB']))">Adopter</button>
            <button class="btn btn--small btn--full" @click="approveProposal(false)">Refuser</button>
        </div>
        <button class="btn btn--small delete" @click="deleteModal = true">Supprimer la proposition</button>
    </div>

</section>

<section class="section-2">
    <div>
        <p v-if="new Date() < discussionDate">En discussion jusqu'au {{ formatDate(discussionDate) }}</p>
        <p v-else>Discussion terminée</p>
    </div>
    <div class="proposal__description">
        <h3>Description</h3>
        <p v-if="proposal['PRO_description_TXT'] != ''">{{ proposal['PRO_description_TXT'] }}</p>
        <p v-else>La proposition n'a pas de description</p>
    </div>
    <div class="proposal__formal">
        <h3>Demandes formelles</h3>
        <p v-if="formalRequest['PRO_request_count_NB'] === 0">Il n'y a aucune demande</p>
        <p v-if="formalRequest['PRO_request_count_NB'] === 1">Il y a une demande</p>
        <p v-if="formalRequest['PRO_request_count_NB'] > 1">Il y a {{ formalRequest['PRO_request_count_NB'] }} demandes</p>
    </div>
    <section class="vote-section">
        <div class="vote__results"v-if="results.length > 0 && currentVote" v-for="result, key in results">
            <h3>{{ (currentVote['VOT_type_NB'] === 1 || currentVote['VOT_type_NB'] === 2) ? 'Résultat' : `Résultat - Tour ${key+1}`}}</h3>
            <div class="vote__results__container">
                <div v-for="possibility, key in result" class="result">
                    <p>{{ possibility['POS_label_VC'] }}</p>
                    <div class="result__stat">
                        <span class="result__stat__bar" 
                        :style="{
                                width: `${possibility['POS_percentNbVotes_NB']}%`
                            }"></span>
                        <span class="result__stat__value legende">{{ possibility['POS_percentNbVotes_NB'] }}%</span>
                    </div>
                </div>
            </div>
            <div class="vote__results__actions">
                <button class="btn btn--small btn--full">Valider</button>
                <button class="btn btn--small btn--full">Refuser</button>
            </div>
        </div>
    </section>
</section>

</main>
<Modal
name="delete"
ok-text="Supprimer"
cancel-text="Annuler"
:disable-valid="!deleteValid"
:before-ok="() => {
    deleteProposal();
    deleteValidation = '';
}"
:before-cancel=" () => {
    deleteValidation = '';
}"
>
<template #title>Supprimer la proposition</template>
<template #body>
    <p>Pour confirmer veuillez écrire le nom de la proposition :</p>
    <Input type="text" :placeholder="proposal['PRO_title_VC'].toUpperCase()" class="inline-input"
    name="deleteValidation"
    :rules="[
        (v) => v === proposal['PRO_title_VC'].toUpperCase() || ''
    ]"
    >Réécrivez : </Input>
</template>
</Modal>

<Modal
name="editBudget"
ok-text="Valider"
cancel-text="Annuler"
:disable-valid="!proposalBudgetEditValid"
:before-ok="() => {
    updateBudget();
}"
:before-cancel=" () => {
    proposalBudgetEdit = null;
}"
>
<template #title>Modifier le budget</template>
<template #body>

<div class="budget-infos-container">
    <p><b>Budget max : </b> {{formatNumber(budget['CMY_budget_NB'])}} € /an</p>
    <p>Budget utilisé : {{formatNumber(budget['CMY_used_budget_NB'] + budget['CMY_fixed_fees_NB'])}} € /an</p>
    <p><b>Budget thème max : </b> {{formatNumber(budgetTheme['BUT_amount_NB'])}} € /an</p>
    <p>Budget thème utilisé : {{formatNumber(budgetTheme['BUT_used_budget_NB'])}} € /an</p>
</div>
<div class="budget-input-container">
    <p>Entrez le budget :</p>
    <InputNumber type="text" :placeholder="proposal['PRO_budget_NB'] +''" :min="0"
    name="proposalBudgetEdit" no-label
    :rules="[
        (v) => v >= 0 || 'Le montant doit être supérieur à 0',
        (v) => (v <= budgetTheme['BUT_amount_NB'] - budgetTheme['BUT_used_budget_NB'] + proposal['PRO_budget_NB']) || 'Le budget ne doit pas dépasser le budget du thème',
        (v) => (v <= budget['CMY_budget_NB'] - budget['CMY_used_budget_NB'] + proposal['PRO_budget_NB']) || 'Le budget ne doit pas dépasser le budget du groupe'
    ]"
    > </InputNumber>
    <p> € /an</p>
</div>
</template>
</Modal>
</template>
<script setup>

const config = useRuntimeConfig();
const route = useRoute();

const community = useState("community");
const proposal = ref({});
const initiator = ref();
const reactions = ref();
const formalRequest = ref({});
const budget = ref({});
const budgetTheme = computed(() => {
    return budget.value['CMY_budget_theme_NB']?.filter((b) => b['THM_name_VC'] === proposal.value['PRO_theme_VC'])[0];
})

const discussionDate = computed(() => {
    const creationDate = new Date(proposal.value["PRO_creation_DATE"]);
    const adjustedDate = new Date(creationDate.setDate(creationDate.getDate() + proposal.value["PRO_discussion_duration_NB"]));
    return adjustedDate;
});

const deleteModal = useState('deleteModal', () => false);
const deleteValidation = useState('deleteValidationValid');
const deleteValid = useState('deleteValidationValid');

const editBudgetModal = useState('editBudgetModal', () => false);
const proposalBudgetEditValid = useState('proposalBudgetEditValid');
const proposalBudgetEdit = useState('proposalBudgetEdit');

const votes = ref();
const currentVote = ref();
const results = ref([]);

const votesAreFinished = computed(() => {
    if(!votes.value || votes.value.length === 0){
        return false;
    }
    const lastVote = votes.value[votes.value?.length - 1];
    return lastVote?.['VOT_nb_rounds_NB'] == lastVote?.['VOT_round_NB'];
});

const fetchData = async () => {
    try {

        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}`, {
            credentials: 'include',
        });
        proposal.value = response;

        const com = await $fetch(`${config.public.baseUrl}/communities/${proposal.value['PRO_community_NB']}`, {
            credentials: 'include',
        });
        
        community.value = com;

        if (!proposal.value?.PRO_initiator_NB) {
            console.warn('Community number not found in proposal data');
            return;
        }
        
        const user = await $fetch(`${config.public.baseUrl}/users/${proposal.value['PRO_initiator_NB']}`, {
            credentials: 'include',
        });

        initiator.value = user;

        const react = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/reactions`, {
            credentials: 'include',
        })

        reactions.value = react;

        const form = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/requests`, {
            credentials: 'include'
        })

        formalRequest.value = form;

        const bud = await $fetch(`${config.public.baseUrl}/communities/${proposal.value['PRO_community_NB']}/budget?period=${proposal.value['PRO_period_YEAR']}`, {
            credentials: 'include',
        });

        budget.value = bud;

    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }
};

const fetchResult = async (round) => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/${round}/vote`, {
            credentials: 'include',
        })

        results.value.push(response);

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchVote = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/votes`, {
            credentials: 'include',
        })

        votes.value = response;
        if(votes.value.length > 0){
            currentVote.value = votes.value[votes.value.length-1];

            for(let i = 0; i < votes.value.length; i++){
                let rmt = calculateTimeRemaining(new Date(), new Date(votes.value[i]['VOT_end_DATE']?.replace(" ", "T")));
                if(rmt === 0){
                    await fetchResult(i+1);
                }
            }
        }

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

function getInitials(string1, string2) {
    const initial1 = string1.trim().charAt(0).toUpperCase();
    const initial2 = string2.trim().charAt(0).toUpperCase();
    return initial1 + initial2;
}

const formatDate = (date) => {
    if (!date) return "";
    return date.toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const formatNumber = (number) => {
    return isNaN(number) ? 0 : new Intl.NumberFormat('fr-FR').format(number);
}

const calculateTimeRemaining = (date1, date2) => {
    const diffMs = date2 - date1;
    if(diffMs < 0){
        return 0;
    }else{
        return diffMs;
    }
}

const updateBudget = async () => {
    try{
        await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}`, {
            method: 'PATCH',
            credentials: 'include',
            body: {
                budget: proposalBudgetEdit.value
            }
        })

        proposal.value['PRO_budget_NB'] = proposalBudgetEdit.value;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const deleteProposal = async () => {
    try{
        await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}`, {
            method: 'DELETE',
            credentials: 'include',
        })

        navigateTo(`/communities/${proposal.value['PRO_community_NB']}`);

    }catch (error){
            console.log('An unexptected error occured : ', error);
    }
}

const approveProposal = async (status) => {
    try{
        await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/approve`, {
            method: 'POST',
            credentials: 'include',
            body: {
                approve: status
            }
        })

        proposal.value['PRO_status_VC'] = status ? 'Validée' : 'Rejetée';

        if(status){
            const bud = await $fetch(`${config.public.baseUrl}/communities/${proposal.value['PRO_community_NB']}/budget?period=${proposal.value['PRO_period_YEAR']}`, {
                credentials: 'include',
            });

            budget.value = bud;
        }

    }catch (error){
            console.log('An unexptected error occured : ', error);
    }
}

onMounted(() => {
    fetchData();
    fetchVote();
})
</script>