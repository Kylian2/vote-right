<template>
    <Header type="logged" :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>
    <Banner :community="community" :themes="communityThemes" back>{{ community["CMY_name_VC"] }}</Banner>
    <main class="community-proposals">
        <div class="filter">
            <p @click="updateFilter" @mouseover="hover = true" @mouseleave="hover = false"
                :style="{ color: hover ? community['CMY_color_VC'] : 'inherit' }">
                {{ hideFilter ? 'Filtrer' : 'Masquer' }}</p>
            <img src="/public/images/icons/grid-active.png" alt="Vue grille" v-if="view === 'grid'"
                @click="view = 'grid'"/>
            <img src="/public/images/icons/grid-inactive.png" alt="Vue grille inactive" v-else
                @click="view = 'grid'"/>
            <img src="/public/images/icons/list-active.png" alt="Vue liste" v-if="view === 'list'"
                @click="view = 'list'"/>
            <img src="/public/images/icons/list-inactive.png" alt="Vue liste inactive" v-else
                @click="view = 'list'"/>
        </div>
        <div v-if="!hideFilter" class="filter-container">
            <div class="filter-container__block">
                <select v-model="filter" name="theme" @change="updateFilteredProposals">
                    <option value="">Filtrer</option>
                    <option :value="theme['THM_id_NB']" v-for="theme in communityThemes">{{ theme["THM_name_VC"] }}</option>
                </select>
            </div>
            <div class="filter-container__block">
                <div>
                    <div class="filter-container__checkboxes" v-for="status in statuses">
                        <input type="checkbox" :id="status" :value="status" v-model="checkedStatus" @change="updateFilteredProposals">
                        <label :for="status">{{ status }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="selectedProposals && selectedProposals.length && view === 'list'" class="list-proposals">
            <NuxtLink :to="`/proposal/${proposal['PRO_id_NB']}`" 
            class="proposal-card"
            :class="{'card-proposal__finished' : proposal['PRO_status_VC'] != 'En cours'}"
            v-for="proposal in selectedProposals" :style="{
            background: community['CMY_color_VC']}">
                <p><span class="proposal-card__theme">{{ proposal["PRO_theme_VC"] }}</span>
                <span class="proposal-card__title">{{ proposal["PRO_title_VC"] }}</span></p>
                <p><span>{{ proposal["PRO_status_VC"] }}</span></p>
            </NuxtLink>
        </div>
        <div v-if="selectedProposals && selectedProposals.length && view === 'grid'" class="grid-proposals">
            <NuxtLink :to="`/proposal/${proposal['PRO_id_NB']}`"
            class="proposal-card-grid"
            :class="{'card-proposal__finished': proposal['PRO_status_VC'] != 'En cours'}"
            :style="{background: community['CMY_color_VC']}"
            v-for="proposal in selectedProposals">
                <div class="proposal-card-grid__header">
                    <span>{{ proposal['PRO_theme_VC'] }}</span>
                    <span class="proposal-card-grid__header__title">{{ proposal['PRO_title_VC'] }}</span>
                    <span>{{ proposal['PRO_status_VC'] }}</span>
                </div>
                <div class="proposal-card-grid__description">
                    <span>{{ proposal['PRO_description_TXT'] }}</span>
                </div>
                <div class="proposal-card-grid__footer">
                    <div>
                        <p><i class="material-icons">calendar_month</i><span class="proposal-card-grid__footer__date">{{ proposal['PRO_period_YEAR'] }}</span></p>
                        <p v-if="proposal['PRO_budget_NB']"><i class="material-icons">savings</i><span>{{ proposal['PRO_budget_NB'] }}€</span></p>
                    </div>
                    <div>
                        <p v-if="proposal['PRO_location_VC']"><i class="material-icons">location_on</i><span>{{ proposal['PRO_location_VC'] }}</span></p>
                    </div>
                </div>
            </NuxtLink>
        </div>
        <div v-if="isDataFetched && (!selectedProposals || !selectedProposals.length)" class="proposal-not-found">
            <p class="error">Aucune proposition</p>
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
const statuses = ["Validée", "Rejetée", "En cours"];
const checkedStatus = ref([]);
const hideFilter = ref(true);
const hover = ref(false);
const view = ref('list');
const isDataFetched = ref(false);

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

        const cmy = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

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

        isDataFetched.value = true;
    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

onMounted(() => {
    fetchData();
})

</script>