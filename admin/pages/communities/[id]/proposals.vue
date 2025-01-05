<template>
    <Header></Header>
    <div class="proposals">
        <NuxtLink class="proposals__return" :to="`/communities/${community['CMY_id_NB']}`">Retour au groupe</NuxtLink>
    </div>
    <h1 class="proposals">Liste des propositions</h1>
    <main class="proposals" v-if="proposals && proposals.length">
        <div>
            <p class="filter" @click="updateFilter" @mouseover="hover = true" @mouseleave="hover = false">{{ hideFilter ? 'Filtrer' : 'Masquer' }}</p>
        </div>
        <div class="filter-container" v-if="!hideFilter">
            <div class="filter-container__block">
                <p>Filtrer par thème : </p>
                <select v-model="filter" name="theme" @change="updateFilteredProposals">
                    <option value="">Filtrer</option>
                    <option :value="theme['THM_id_NB']" v-for="theme in communityThemes">{{ theme["THM_name_VC"] }}</option>
                </select>
            </div>
            <div class="filter-container__block">
                <p>Statut : </p>
                <div>
                    <div class="filter-container__checkboxes" v-for="status in statuses">
                        <input type="checkbox" :id="status" :value="status" v-model="checkedStatus" @change="updateFilteredProposals">
                        <label :for="status">{{ status }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="list-proposals">
            <NuxtLink :to="`/proposals/${proposal['PRO_id_NB']}`" class="proposal-card" v-if="selectedProposals && selectedProposals.length" v-for="proposal in selectedProposals">
                <p><span class="proposal-card__theme">{{ proposal["PRO_theme_VC"] }}</span> - <span class="proposal-card__title">{{ proposal["PRO_title_VC"] }}</span></p>
                <p><b>{{ proposal["PRO_status_VC"] }}</b></p>
            </NuxtLink>
            <p class="error" v-else-if="!hideFilter">Aucune proposition</p>
        </div>
    </main>
</template>

<script setup>

definePageMeta({
  middleware: ["auth", "managed"]
})

const config = useRuntimeConfig();
const route = useRoute();

const community = ref({});
const communityThemes = ref();
const proposals = ref();
const selectedProposals = ref();
const filter = ref('');
const statuses = ["Validée", "Rejetée", "En cours"];
const checkedStatus = ref([]);
const hideFilter = ref(true);
const hover = ref(false);

const fetchData = async () => {
    try{
        const cmy = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        });

        community.value = cmy;
        
        const the = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/themes`, {
            credentials: 'include',
        })

        communityThemes.value = the;

        const pro = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/proposals`, {
            credentials: 'include',
        })

        proposals.value = pro;
        selectedProposals.value = pro;
        
    } catch(error) {
        console.log("An error occured", error);
    }

}

const updateFilteredProposals = () => {
    let filtered = proposals.value;

    if(filter.value){
        filtered = filtered.filter(proposal => proposal['PRO_theme_NB'] == filter.value);
    }

    if(checkedStatus.value.length > 0){
        filtered = filtered.filter(proposal => checkedStatus.value.includes(proposal['PRO_status_VC']));
    }

    selectedProposals.value = filtered;
}

const updateFilter = () => {
    hideFilter.value = !hideFilter.value;

    if(hideFilter.value == true){
        checkedStatus.value = [];
        filter.value = '';
        updateFilteredProposals();
    }
}

onMounted(() => {
    fetchData();
})
</script>