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

    <div v-if="proposal" class="proposal__informations">
        <p v-if="proposal['PRO_theme_VC']"><img src="/images/icons/theme.svg" alt="icons-theme">{{ proposal['PRO_theme_VC'] }}</p>
        <div><p v-if="proposal['PRO_budget_NB']"><img src="/images/icons/budget.svg" alt="icons-theme">{{ proposal['PRO_budget_NB'] }} €</p><p class="edit">Modifier</p></div>
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
        <div>
            <button class="btn btn--small btn--full">Adopter</button>
            <button class="btn btn--small btn--full">Refuser</button>
        </div>
        <button class="btn btn--small delete">Supprimer la proposition</button>
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
    <section>
        <div class="proposal__formal">
            <h3>Demandes formelles</h3>
            <p v-if="formalRequest['PRO_request_count_NB'] === 0">Il n'y a aucune demande</p>
            <p v-if="formalRequest['PRO_request_count_NB'] === 1">Il y a une demande</p>
            <p v-if="formalRequest['PRO_request_count_NB'] > 1">Il y a {{ formalRequest['PRO_request_count_NB'] }} demandes</p>
        </div>
        <div class="proposal__vote">
            <div>
                <h3>Vote</h3>
                <p class="legende">Le vote est terminé</p>
                <p><b>Résultat:</b></p>
                <ul>
                    <li class="legende">463 POUR</li>
                    <li class="legende">33 CONTRE</li>
                </ul>
            </div>
            <div>
                <p class="legende">Veuillez valider ou refuser le vote</p>
                <button class="btn btn-small btn--full">Valider</button>
                <button class="btn btn-small btn--full">Refuser</button>
            </div>
        </div>
    </section>
</section>

</main>
</template>
<script setup>

const config = useRuntimeConfig();
const route = useRoute();

const community = useState("community");
const proposal = ref({});
const initiator = ref();
const reactions = ref();
const formalRequest = ref({});

const discussionDate = computed(() => {
    const creationDate = new Date(proposal.value["PRO_creation_DATE"]);
    const adjustedDate = new Date(creationDate.setDate(creationDate.getDate() + proposal.value["PRO_discussion_duration_NB"]));
    return adjustedDate;
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

    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }
};

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

onMounted(() => {
    fetchData();
})
</script>