<template>
    <Header></Header>
    <NuxtLink class="back" :to="`/communities/${$route.params.id}`">Retour au groupe</NuxtLink>
    <h1 class="budget__title">Gestion du budget</h1>

    <main class="budget">
        <div class="budget__header">
            <div class="budget__header__toolbar">
                <div class="budget__period">
                    <p>Année :</p>
                    <select v-model="period" @change="fetchDataByPeriod()">
                        <option v-for="p in periods" :value="p" :key="p">{{ p }}</option>
                    </select>
                </div>
                <button class="btn" @click="editBudgetBlock = true">Editer les budgets</button>
            </div>

            <div class="budget__header__cards">
                <div class="budget__card">
                    <h3>Budget Total</h3>
                    <div class="budget__card__amount">
                        <p class="budget__card__amount__main">
                            {{
                                budget['CMY_budget_NB']
                                    ? formatNumber(budget['CMY_budget_NB']) + ' €'
                                    : 'Pas de budget défini'
                            }}
                            <span></span>
                        </p>
                        <p class="budget__card__amount__sub" v-if="budget['CMY_fixed_fees_NB']">
                            dont <span> {{ formatNumber(budget['CMY_fixed_fees_NB'] || 0) }} </span> € de frais fixes
                        </p>
                    </div>
                    <div class="budget__card__percentage" v-if="budget['CMY_budget_NB']">
                        <p>
                            <span>
                                {{
                                    Math.round(
                                        100 -
                                            ((budget['CMY_used_budget_NB'] || 0) / (budget['CMY_budget_NB'] || 0)) * 100
                                    )
                                }}%</span
                            >
                            restant
                        </p>
                    </div>
                </div>

                <div class="budget__card">
                    <h3>Budget Utilisé</h3>
                    <div class="budget__card__amount">
                        <p class="budget__card__amount__main">
                            {{ formatNumber(budget['CMY_used_budget_NB'] || 0) }} <span> €</span>
                        </p>
                    </div>
                </div>

                <div class="budget__card">
                    <h3>Budget Non Attribué</h3>
                    <div class="budget__card__amount">
                        <p class="budget__card__amount__main" :class="{ error: nonSpecifiedBudget < 0 }">
                            {{ formatNumber(nonSpecifiedBudget) }} <span> €</span>
                        </p>
                    </div>
                    <div class="budget__card__percentage" v-if="budget['CMY_budget_NB']">
                        <p>
                            <span>
                                {{
                                    Math.sign(nonSpecifiedBudget) *
                                    Math.round(((nonSpecifiedBudget || 0) / (budget['CMY_budget_NB'] || 0)) * 100)
                                }}%</span
                            >
                            {{ nonSpecifiedBudget > 0 ? 'non catégorisé' : 'supplémentaires attribués' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="budget__edits" :class="{ 'd-none': !editBudgetBlock }">
            <h3>Éditer les budgets</h3>
            <div class="budget__edits__content">
                <div class="edit-budget">
                    <div>
                        <p><b>Budget max :</b></p>
                        <div>
                            <InputNumber
                                name="budget"
                                :placeholder="
                                    budget['CMY_budget_NB'] ? budget['CMY_budget_NB'] + '' : 'Budget non défini'
                                "
                                :step="100"
                            ></InputNumber>
                            <p>€</p>
                            <p class="error" @click="undefinedBudget(0)">
                                <i class="material-icons" id="leaderboard-icon">close</i>
                            </p>
                        </div>
                    </div>
                    <div>
                        <p>Frais fixes :</p>
                        <div>
                            <InputNumber
                                name="feesBudget"
                                :placeholder="
                                    budget['CMY_fixed_fees_NB'] ? budget['CMY_fixed_fees_NB'] + '' : 'Budget non défini'
                                "
                                :step="100"
                            ></InputNumber>
                            <p>€</p>
                            <p class="error" @click="undefinedBudget(-1)">
                                <i class="material-icons" id="leaderboard-icon">close</i>
                            </p>
                        </div>
                    </div>
                    <div v-for="(theme, key) in budget['CMY_budget_theme_NB']">
                        <p>
                            <b>{{ theme['THM_name_VC'] }} :</b>
                        </p>
                        <div>
                            <InputNumber
                                :name="theme['THM_name_VC'] + 'Budget'"
                                :placeholder="
                                    theme['BUT_amount_NB'] ? theme['BUT_amount_NB'] + '' : 'Budget non défini'
                                "
                                :step="100"
                            ></InputNumber>
                            <p>€</p>
                            <p class="error" @click="undefinedBudget(theme['THM_id_NB'])">
                                <i class="material-icons" id="leaderboard-icon">close</i>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="budget__edits__chart">
                    <canvas ref="budgetEditChartCanva"></canvas>
                </div>
            </div>
            <div class="budget__edits__buttons">
                <button class="btn btn--cancel" @click="editBudgetBlock = false">Annuler</button>
                <button class="btn" formmethod="dialog" :disabled="!budgetValid" @click="updateBudget">
                    Valider les modifications
                </button>
            </div>
        </div>

        <div class="budget__chart budget__chart--bar" id="theme-chart">
            <h3>Aperçu par thèmes</h3>
            <div>
                <canvas ref="themeChartCanva"></canvas>
            </div>
        </div>

        <div
            class="budget__chart budget__chart--pie"
            id="theme-chart-pie"
            :class="{ 'd-none': adoptedProposals.length === 0 }"
        >
            <h3>Répartition par thèmes</h3>
            <div>
                <canvas ref="themeChartPieCanva"></canvas>
            </div>
        </div>

        <div
            class="budget__chart budget__chart--pie"
            id="proposals-chart-pie"
            :class="{ 'd-none': adoptedProposals.length === 0 }"
        >
            <h3>Répartition par propositions</h3>
            <div>
                <canvas ref="proposalsChartPieCanva"></canvas>
            </div>
        </div>
    </main>

    <Toast name="budgetToastValid" :type="3" :time="5" :loader="true" class="toast"> Budget Modifié ! </Toast>
    <Toast name="budgetToastError" :type="1" :time="5" :loader="true" class="toast">
        Erreur lors de la modification du budget
    </Toast>
    <Toast name="budgetToastAlert" :type="2" :time="5" :loader="true" class="toast">
        Aucune modification a appliquer
    </Toast>
</template>

<script setup>
import Chart from 'chart.js/auto'
import { useColorPalette } from '~/composables/colors'

const { createPalette, addOpacity, darken } = useColorPalette()

definePageMeta({
    middleware: ['auth', 'decider'],
})

const formatNumber = (number) => {
    return isNaN(number) ? 0 : new Intl.NumberFormat('fr-FR').format(number)
}

const config = useRuntimeConfig()
const route = useRoute()

const community = ref({})
const adoptedProposals = ref({})
const budget = ref({})
const nonSpecifiedBudget = ref(0)

const period = ref(new Date().getFullYear())
const periods = ref([])

const themeChartCanva = ref(null)
const themeChartPieCanva = ref(null)
const proposalsChartPieCanva = ref(null)

let colorsPalette
let mainColor

let themesChart = null
let themeChartPie = null
let proposalsChartPie = null

// Obtenir des données non-réactives pour les graphiques
const getChartData = () => {
    if (!budget.value || !budget.value['CMY_budget_theme_NB'] || !Array.isArray(budget.value['CMY_budget_theme_NB'])) {
        return {
            themeLabels: [],
            budgetAmounts: [],
            usedBudgetAmounts: [],
        }
    }

    // Cloner les données pour éliminer la réactivité
    const budgetThemeData = JSON.parse(JSON.stringify(budget.value['CMY_budget_theme_NB']))

    const proposals = JSON.parse(JSON.stringify(adoptedProposals.value))

    return {
        themeLabels: budgetThemeData.map((row) => row['THM_name_VC']),
        budgetAmounts: budgetThemeData.map((row) => row['BUT_amount_NB']),
        usedBudgetAmounts: budgetThemeData.map((row) => row['BUT_used_budget_NB']),
        proposals: proposals,
    }
}

const fetchData = async () => {
    try {
        const cmy = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

        community.value = cmy

        const bud = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`,
            {
                credentials: 'include',
            }
        )

        budget.value = bud
        colorsPalette = createPalette(community.value['CMY_color_VC'], budget.value['CMY_budget_theme_NB'].length)
        mainColor = community.value['CMY_color_VC']
        nonSpecifiedBudget.value =
            budget.value['CMY_budget_NB'] -
            budget.value['CMY_budget_theme_NB']?.reduce((acc, val) => acc + val['BUT_amount_NB'], 0) -
            budget.value['CMY_fixed_fees_NB']

        const per = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/periods`, {
            credentials: 'include',
        })

        periods.value = per
        periods.value.forEach((p) => {
            if (new Date().getFullYear() == p) {
                period.value = p
            }
        })

        const ado = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/adopted?period=${period.value}`,
            {
                credentials: 'include',
            }
        )

        adoptedProposals.value = ado

        initCharts()
    } catch (error) {
        console.log('An error occurred', error)
    }
}

const fetchDataByPeriod = async () => {
    try {
        const bud = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`,
            {
                credentials: 'include',
            }
        )

        budget.value = bud
        nonSpecifiedBudget.value =
            budget.value['CMY_budget_NB'] -
            budget.value['CMY_budget_theme_NB']?.reduce((acc, val) => acc + val['BUT_amount_NB'], 0) -
            budget.value['CMY_fixed_fees_NB']

        const ado = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/adopted?period=${period.value}`,
            {
                credentials: 'include',
            }
        )

        adoptedProposals.value = ado

        updateCharts()

        editBudgetBlock.value = false
    } catch (error) {
        console.log('An error occurred', error)
    }
}

let themeBudgetInformations = computed(() => {
    const { themeLabels, budgetAmounts, usedBudgetAmounts } = getChartData()

    let tbi = themeLabels.map((label, index) => ({
        label,
        budget: budgetAmounts[index],
        usedBudget: usedBudgetAmounts[index],
    }))
    return tbi
})

const initCharts = () => {
    // Obtenir les données de graphique dans un format non-réactif
    const { themeLabels, budgetAmounts, usedBudgetAmounts, proposals } = getChartData()

    if (themeLabels.length === 0) {
        console.log('No chart data available yet')
        return
    }

    // Détruire les graphiques existants si nécessaire
    destroyCharts()

    themesChart = new Chart(themeChartCanva.value.getContext('2d'), {
        type: 'bar',
        data: {
            labels: themeLabels,
            datasets: [
                {
                    label: 'Budget',
                    data: budgetAmounts,
                    backgroundColor: mainColor,
                    borderColor: darken(mainColor, 0.1),
                    borderWidth: 1,
                    borderRadius: 7,
                },
                {
                    label: 'Budget Utilisé',
                    data: usedBudgetAmounts,
                    backgroundColor: darken(mainColor, 0.4),
                    borderColor: darken(mainColor, 0.7),
                    borderWidth: 1,
                    borderRadius: 7,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...(budgetAmounts.length ? budgetAmounts : [0])) * 1.2,
                },
            },
        },
    })

    themeChartPie = new Chart(themeChartPieCanva.value.getContext('2d'), {
        type: 'pie',
        data: {
            labels: themeLabels,
            datasets: [
                {
                    label: 'Budget Utilisé',
                    data: usedBudgetAmounts,
                    backgroundColor: colorsPalette,
                    borderColor: colorsPalette.map((color) => addOpacity(color, 0.7)),
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'left',
                },
            },
        },
    })

    proposalsChartPie = new Chart(proposalsChartPieCanva.value.getContext('2d'), {
        type: 'pie',
        data: {
            labels: proposals.map((row) => row['PRO_title_VC']),
            datasets: [
                {
                    label: 'Budget',
                    data: proposals.map((row) => row['PRO_budget_NB']),
                    backgroundColor: colorsPalette,
                    borderColor: colorsPalette.map((color) => addOpacity(color, 0.7)),
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index
                    const from = useState('from', () => {
                        return {
                            name: route.name,
                            href: route.href,
                        }
                    })
                    from.value = {
                        name: route.name,
                        href: route.href,
                    }
                    navigateTo(`/proposals/${adoptedProposals.value[index]['PRO_id_NB']}`)
                }
            },
        },
    })
}

const updateCharts = () => {
    if (!themesChart || !themeChartPie) {
        console.error('Charts not initialized')
        initCharts()
        return
    }

    // Obtenir les données de graphique dans un format non-réactif
    const { themeLabels, budgetAmounts, usedBudgetAmounts, proposals } = getChartData()

    themesChart.data.labels = themeLabels
    themesChart.data.datasets[0].data = budgetAmounts
    themesChart.data.datasets[1].data = usedBudgetAmounts

    themeChartPie.data.labels = themeLabels
    themeChartPie.data.datasets[0].data = usedBudgetAmounts

    proposalsChartPie.data.labels = proposals.map((row) => row['PRO_title_VC'])
    proposalsChartPie.data.datasets[0].data = proposals.map((row) => row['PRO_budget_NB'])

    try {
        themesChart.update()
        themeChartPie.update()
        proposalsChartPie.update()
    } catch (error) {
        console.error('Error updating charts:', error)

        destroyCharts()
        nextTick(() => {
            initCharts()
        })
    }
}

const destroyCharts = () => {
    if (themesChart) {
        themesChart.destroy()
        themesChart = null
    }

    if (themeChartPie) {
        themeChartPie.destroy()
        themeChartPie = null
    }
}

const budgetToastValid = useState('budgetToastValidUp', () => false)
const budgetToastError = useState('budgetToastErrorUp', () => false)
const budgetToastAlert = useState('budgetToastAlertUp', () => false)

const editBudgetBlock = ref(false)
const budgetTotalTheme = computed(() => {
    let somme = 0
    for (let i = 0; i < budget.value['CMY_budget_theme_NB']?.length; i++) {
        somme += budget.value['CMY_budget_theme_NB'][i]['BUT_amount_NB']
    }
    return somme
})

const communityBudgetValid = useState('budgetValid', () => true)
const feesBudgetValid = useState('feesBudgetValid', () => true)
let checkCount = ref(0)

//verifie l'ensemble des budgets entrées par l'utilisateur
const budgetValid = computed(() => {
    let valid = true
    valid = valid && feesBudgetValid.value && communityBudgetValid.value
    for (let i = 0; i < budget.value['CMY_budget_theme_NB']?.length; i++) {
        const bud = useState(budget.value['CMY_budget_theme_NB'][i]['THM_name_VC'] + 'BudgetValid')
        valid = valid && bud.value
    }
    checkCount.value++
    return valid
})

const updateBudget = async () => {
    let raw = {}
    const totalBudget = useState('budget')
    if (totalBudget.value != '') {
        raw['0'] = totalBudget.value
    }
    const feesBudget = useState('feesBudget')
    if (feesBudget.value != '') {
        raw['-1'] = feesBudget.value
    }

    budget.value['CMY_budget_theme_NB'].forEach((theme) => {
        const budgetTheme = useState(theme['THM_name_VC'] + 'Budget')
        if (budgetTheme.value != '') {
            raw[theme['THM_id_NB']] = budgetTheme.value
        }
        clearNuxtState(theme['THM_name_VC'] + 'Budget')
    })
    if (Object.keys(raw).length === 0) {
        budgetToastAlert.value = true
        return
    }
    try {
        const response1 = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`,
            {
                method: 'PATCH',
                credentials: 'include',
                body: raw,
            }
        )

        budgetToastValid.value = response1 === true
        budgetToastError.value = response1 !== true

        if (response1 === true) {
            editBudgetBlock.value = false
        }

        const response2 = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`,
            {
                credentials: 'include',
            }
        )

        budget.value = response2
        nonSpecifiedBudget.value =
            budget.value['CMY_budget_NB'] -
            budget.value['CMY_budget_theme_NB']?.reduce((acc, val) => acc + val['BUT_amount_NB'], 0) -
            budget.value['CMY_fixed_fees_NB']
        updateCharts()
    } catch (error) {
        console.log('An error occured', error)
    }
}

/**
 *
 * @param type Le type de budget à rendre null (0 = le budget général du groupe, -1 = les frais fixes du groupes, X = l'id du thème à modifier)
 */
const undefinedBudget = async (type) => {
    let raw = {}

    raw[type] = null

    try {
        const response1 = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`,
            {
                method: 'PATCH',
                credentials: 'include',
                body: raw,
            }
        )

        budgetToastValid.value = response1 === true
        budgetToastError.value = response1 !== true

        const response2 = await $fetch(
            `${config.public.baseUrl}/communities/${route.params.id}/budget?period=${period.value}`,
            {
                credentials: 'include',
            }
        )

        budget.value = response2
        nonSpecifiedBudget.value =
            budget.value['CMY_budget_NB'] -
            budget.value['CMY_budget_theme_NB']?.reduce((acc, val) => acc + val['BUT_amount_NB'], 0) -
            budget.value['CMY_fixed_fees_NB']
        updateCharts()
    } catch (error) {
        console.log('An error occured', error)
    }
}
const budgetEditChartCanva = ref(null)
let budgetEditChart = null

watch(
    editBudgetBlock,
    (n, o) => {
        if (n) {
            const budgetAmounts = themeBudgetInformations.value.map((row) => row.budget)

            if (budgetEditChart) {
                budgetEditChart.destroy()
            }

            budgetEditChart = new Chart(budgetEditChartCanva.value.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: themeBudgetInformations.value.map((row) => row.label),
                    datasets: [
                        {
                            label: 'Budget',
                            data: budgetAmounts,
                            backgroundColor: mainColor,
                            borderColor: darken(mainColor, 0.1),
                            borderWidth: 1,
                            borderRadius: 7,
                        },
                        {
                            label: 'Budget Utilisé',
                            data: themeBudgetInformations.value.map((row) => row.usedBudget),
                            backgroundColor: darken(mainColor, 0.4),
                            borderColor: darken(mainColor, 0.7),
                            borderWidth: 1,
                            borderRadius: 7,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...(budgetAmounts.length ? budgetAmounts : [0])) * 1.2,
                        },
                    },
                },
            })
        }
    },
    { deep: true, immediate: false }
)

// Peut-etre une cause de ralentissement de l'application ?
watch(
    checkCount,
    (n, o) => {
        if (editBudgetBlock.value) {
            let budgetEdited = []
            let infos = themeBudgetInformations.value

            for (let i = 0; i < infos.length; i++) {
                const bud = useState(infos[i].label + 'Budget')
                budgetEdited.push({
                    label: infos[i].label,
                    budget: bud.value == '' ? infos[i].budget : bud.value,
                    usedBudget: infos[i].usedBudget,
                })
            }

            budgetEditChart.data.datasets[0].data = budgetEdited.map((row) => row.budget)
            budgetEditChart.update()
        } else {
            if (budgetEditChart) {
                budgetEditChart.destroy()
            }
        }
    },
    { deep: true, immediate: false }
)

onMounted(async () => {
    await fetchData()
})

onBeforeUnmount(() => {
    destroyCharts()

    useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    })
})
</script>
