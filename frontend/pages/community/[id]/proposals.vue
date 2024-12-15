<template>
    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>

    <Banner :community="community" :themes="communityThemes" back>{{ community["CMY_name_VC"] }}</Banner>

    <main class="community-proposals" v-if="proposals && proposals.length">
        <div>
            <p class="filter" @click="updateFilter" @mouseover="hover = true" @mouseleave="hover = false" :style="{
                color: hover ? community['CMY_color_VC'] : 'inherit' }">{{ hideFilter ? 'Filtrer' : 'Masquer' }}</p>
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
                <div class="filter-container__checkboxes" v-for="status in statuses">
                    <input type="checkbox" :id="status" :value="status" v-model="checkedStatus" @change="updateFilteredProposals">
                    <label :for="status">{{ status }}</label>
                </div>
            </div>
        </div>
        <div class="list-proposals">
            <NuxtLink :to="`/proposal/${proposal['PRO_id_NB']}`" class="proposal-card" v-if="selectedProposals && selectedProposals.length" v-for="proposal in selectedProposals" :style="{ 
                background: community['CMY_color_VC']}">
                <p><span class="proposal-card__theme">{{ proposal["PRO_theme_VC"] }}</span>
                <span class="proposal-card__title">{{ proposal["PRO_title_VC"] }}</span></p>
                <p><span>{{ proposal["PRO_status_VC"] }}</span></p>
            </NuxtLink>
            <p class="error" v-else-if="!hideFilter">Aucune proposition</p>
        </div>
    </main>
</template>
<script setup>

const config = useRuntimeConfig();

definePageMeta({
    middleware: ["auth", "community-member"]
});

const route = useRoute();
const community = useState("community");
const communityThemes = useState("communityThemes");
const proposals = ref();
const selectedProposals = ref();
const filter = ref('');
const statuses = ["Validée", "Refusée", "En cours"];
const checkedStatus = ref([]);
const hideFilter = ref(true);
const hover = ref(false);

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

const fetchData = async () => {
    try{

        //Grace a useState, si le valeur à déja été chargée (dans le cas où il arrive depuis une autre page, pas besoin de refaire une requête)
        if(community.value){
            return;
        }

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

        community.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }

    try{

        if(communityThemes.value){
            return;
        }

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/themes`, {
            credentials: 'include',
        })

        communityThemes.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchProposals = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/proposals`, {
            credentials: 'include',
        })

        proposals.value = response;
        selectedProposals.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

onMounted(() => {
    fetchData();
    fetchProposals();
})

</script>