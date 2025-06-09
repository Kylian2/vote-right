<template>
    <Header type="logged" :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>
    <Banner :community="community" :themes="communityThemes" back>{{ community["CMY_name_VC"] }}</Banner>
    <main class="community-proposals">
        {{ yearRange }}
        {{ selectedRange }}
        <div class="view-selector">
            <p  @click="showFilter = !showFilter" @mouseover="hover = true" @mouseleave="hover = false" :style="{
            color: hover ? community['CMY_color_VC'] : 'inherit' }">{{ showFilter ? 'Masquer' : 'Filtrer' }}</p>
            <div>
                <img v-if="view === 'list'" src="/public/images/icons/list-active.png" alt="Vue liste"/>
                <img v-else @click="view = 'list'" src="/public/images/icons/list-inactive.png" alt="Vue liste inactive"/>
                <img v-if="view === 'grid'" src="/public/images/icons/grid-active.png" alt="Vue grille"/>
                <img v-else @click="view = 'grid'" src="/public/images/icons/grid-inactive.png" alt="Vue grille inactive"/>
            </div>
        </div>
        <div v-if="showFilter" class="filter">
            <select v-model="chekedSort" @change="sortProposals">
                <option value="">Trier par budget ou date</option>
                <option v-for="typeSort in sortList" :value="typeSort">{{ typeSort }}</option>
            </select>
            <select v-model="checkedTheme" @change="updateFilteredProposals">
                <option value="">Filtrer par thème</option>
                <option v-for="theme in communityThemes" :value="theme['THM_id_NB']">{{ theme["THM_name_VC"] }}</option>
            </select>
            <select v-model="checkedStatus" @change="updateFilteredProposals">
                <option value="">Filtrer par statut</option>
                <option v-for="status in statuses" :value="status">{{ status }}</option>
            </select>
            <div class="filter__year">
                <div>
                    <label for="minYearInput">Année min</label>
                    <InputNumber @change="updateFilteredProposals" name="minYear" :min="MIN_YEAR" :max="maxYear" type="number" id="minYearInput" step="1"/>
                    <label for="maxYearInput">max</label>
                    <InputNumber @change="updateFilteredProposals" name="maxYear" :min="minYear" :max="MAX_YEAR" type="number" id="maxYearInput" step="1"/>
                    <i v-if="filterChanged" @click="cancelFilter" class="material-icons">cancel</i>
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
                <p><span style="white-space: nowrap;">{{ proposal["PRO_status_VC"] }}</span></p>
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
const isDataFetched = ref(false);

const sortList = ["Budget croissant", "Budget décroissant", "Date chronologique", "Date antéchronologique"];
const statuses = ["Validée", "Rejetée", "En cours"];

const chekedSort = ref('');
const checkedTheme = ref('');
const checkedStatus = ref('');

const showFilter = ref(false);
const hover = ref(false);
const view = ref('list');

const MIN_YEAR = ref();
const MAX_YEAR = ref();
const NB_YEAR = ref();
const minYear = useState("minYear");
const maxYear = useState("maxYear");

const filterChanged = computed(() => {
    return chekedSort.value || checkedTheme.value || checkedStatus.value || minYear.value != MIN_YEAR.value || maxYear.value != MAX_YEAR.value;
})

const cancelFilter = () => {
    chekedSort.value = '';
    checkedTheme.value = '';
    checkedStatus.value = '';
    minYear.value = MIN_YEAR.value;
    maxYear.value = MAX_YEAR.value;

    selectedProposals.value = proposals.value;
}

const updateFilteredProposals = () => {
    let filtered = proposals.value;

    if (checkedTheme.value) {
        filtered = filtered.filter(proposal => proposal['PRO_theme_NB'] == checkedTheme.value);
    }

    if (checkedStatus.value) {
        filtered = filtered.filter(proposal => proposal['PRO_status_VC'] == checkedStatus.value);
    }

    if (NB_YEAR.value > 0) {
        filtered = filtered.filter(
            proposal => proposal['PRO_period_YEAR'] >= minYear.value && proposal['PRO_period_YEAR'] <= maxYear.value
        )
    }

    selectedProposals.value = filtered;
    sortProposals();
}

const sortProposals = () => {
    switch (chekedSort.value) {
        case "Date chronologique":
            selectedProposals.value = mergeSortProposalsByDate(selectedProposals.value);
            break;
        case "Date antéchronologique":
            selectedProposals.value = mergeSortProposalsByDateDesc(selectedProposals.value);
            break;
        case "Budget croissant":
            selectedProposals.value = mergeSortProposals(selectedProposals.value);
            break;
        case "Budget décroissant":
            selectedProposals.value = mergeSortProposalsDesc(selectedProposals.value);
            break;
        default:
            updateFilteredProposals();
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

        let years = proposals.value.map(proposal => proposal['PRO_period_YEAR']);
        MIN_YEAR.value = Math.min(...years);
        MAX_YEAR.value = Math.max(...years);
        NB_YEAR.value = MAX_YEAR.value - MIN_YEAR.value;
        minYear.value = MIN_YEAR.value;
        maxYear.value = MAX_YEAR.value;  

        isDataFetched.value = true;
    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

onMounted(() => {
    fetchData();
})

</script>