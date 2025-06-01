<template>
    <Header type="logged" :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>
    <Banner :community="community" :themes="communityThemes" back>{{ community["CMY_name_VC"] }}</Banner>
    <main class="community-proposals">
        <div class="filter">
            <p @click="updateSort" :style="{ color: hoverSort ? community['CMY_color_VC'] : 'inherit' }">Trier</p>
            <p @click="updateFilter" :style="{ color: hoverFilter ? community['CMY_color_VC'] : 'inherit' }">Filtrer</p>
            <img v-if="view === 'grid'" src="/public/images/icons/grid-active.png" alt="Vue grille"/>
            <img v-else @click="view = 'grid'" src="/public/images/icons/grid-inactive.png" alt="Vue grille inactive"/>
            <img v-if="view === 'list'" src="/public/images/icons/list-active.png" alt="Vue liste"/>
            <img v-else @click="view = 'list'" src="/public/images/icons/list-inactive.png" alt="Vue liste inactive"/>
        </div>
        <div v-if="showFilter && typeFilter == 'Trier'" class="filter-container">
            <div class="filter-container__block">
                <img src="/public/images/icons/grid-active.png" alt="Date chronologique"
                @click="sortProposals('Date chronologique')"/>
                <img src="/public/images/icons/grid-active.png" alt="Date antéchronologique"
                @click="sortProposals('Date antéchronologique')"/>
                <img src="/public/images/icons/grid-active.png" alt="Budget croissant"
                @click="sortProposals('Budget croissant')"/>
                <img src="/public/images/icons/grid-active.png" alt="Budget décroissant"
                @click="sortProposals('Budget décroissant')"/>
            </div>
        </div>
        <div v-if="showFilter && typeFilter == 'Filtrer'" class="filter-container">
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
const showFilter = ref(false);
const hoverSort = ref(false);
const hoverFilter = ref(false);
const view = ref('list');
const isDataFetched = ref(false);
const typeFilter = ref('');

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

const updateSort = () => {
    if (typeFilter.value == 'Trier') {
        typeFilter.value = '';
        hoverSort.value = false;
        showFilter.value = false;
    } else {
        typeFilter.value = 'Trier';
        hoverSort.value = true;
        hoverFilter.value = false;
        showFilter.value = true;
    }
}

const updateFilter = () => {
    if (typeFilter.value == 'Filtrer') {
        typeFilter.value = '';
        hoverFilter.value = false;
        showFilter.value = false;
    } else {
        typeFilter.value = 'Filtrer';
        hoverFilter.value = true;
        hoverSort.value = false;
        showFilter.value = true;
    }
}

const sortProposals = (choice) => {
    if (choice == "Date chronologique") {
        selectedProposals.value = mergeSortProposalsByDate(selectedProposals.value);
    }
    if (choice == "Date antéchronologique") {
        selectedProposals.value = mergeSortProposalsByDateDesc(selectedProposals.value);
    }
    if (choice == "Budget croissant") {
        selectedProposals.value = mergeSortProposals(selectedProposals.value);
    }
    if (choice == "Budget décroissant") {
        selectedProposals.value = mergeSortProposalsDesc(selectedProposals.value);
    }
}

const mergeSortProposalsByDate = (proposals) => {
    if (proposals.length <= 1) {
        return proposals;
    }

    let middle = Math.floor(proposals.length / 2);
    let left = mergeSortProposalsByDate(proposals.slice(0, middle));
    let right = mergeSortProposalsByDate(proposals.slice(middle));

    return mergeByDate(left, right);
}

const mergeByDate = (left, right) => {
    let result = [];

    while (left.length && right.length) {
        if (left[0]['PRO_period_YEAR'] < right[0]['PRO_period_YEAR']) {
            result.push(left.shift());
        } else {
            result.push(right.shift());
        }
    }

    return result.concat(left, right);
}

const mergeSortProposalsByDateDesc = (proposals) => {
    if (proposals.length <= 1) {
        return proposals;
    }

    let middle = Math.floor(proposals.length / 2);
    let left = mergeSortProposalsByDateDesc(proposals.slice(0, middle));
    let right = mergeSortProposalsByDateDesc(proposals.slice(middle));

    return mergeByDateDesc(left, right);
}

const mergeByDateDesc = (left, right) => {
    let result = [];

    while (left.length && right.length) {
        if (left[0]['PRO_period_YEAR'] > right[0]['PRO_period_YEAR']) {
            result.push(left.shift());
        } else {
            result.push(right.shift());
        }
    }

    return result.concat(left, right);
}

const mergeSortProposals = (proposals) => {
    if (proposals.length <= 1) {
        return proposals;
    }

    let middle = Math.floor(proposals.length / 2);
    let left = mergeSortProposals(proposals.slice(0, middle));
    let right = mergeSortProposals(proposals.slice(middle));

    return merge(left, right);
}

const mergeSortProposalsDesc = (proposals) => {
    if (proposals.length <= 1) {
        return proposals;
    }

    let middle = Math.floor(proposals.length / 2);
    let left = mergeSortProposalsDesc(proposals.slice(0, middle));
    let right = mergeSortProposalsDesc(proposals.slice(middle));

    return mergeDesc(left, right);
}

const merge = (left, right) => {
    let result = [];

    while (left.length && right.length) {
        if (left[0]['PRO_budget_NB'] < right[0]['PRO_budget_NB']) {
            result.push(left.shift());
        } else {
            result.push(right.shift());
        }
    }

    return result.concat(left, right);
}

const mergeDesc = (left, right) => {
    let result = [];

    while (left.length && right.length) {
        if (left[0]['PRO_budget_NB'] > right[0]['PRO_budget_NB']) {
            result.push(left.shift());
        } else {
            result.push(right.shift());
        }
    }

    return result.concat(left, right);
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