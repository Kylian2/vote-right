<template>

<Header></Header>
<NuxtLink class="back" :to="`/communities/${proposal['PRO_community_NB']}`">Retour au groupe</NuxtLink>
<div class="vote__title">
    <h1>{{ proposal['PRO_title_VC'] }}</h1>
    <h4>Modalité de vote</h4>
</div>
<main class="vote">

    <section class="vote__informations">
        <h3>Informations du vote</h3>
        <Select name="votingType" :options="systems.map((s)=> [s['SYS_label_VC'], s['SYS_id_NB']])" placeholder="Selectionner">Selectionnez un mode de scrutin</Select>
        <div class="duration">
            <InputNumber 
            name="discussionDuration"
            placeholder="ex : 4"
            :min="1"
            required
            :rules="[
                (v) => Boolean(v) || 'Champ requis',
                (v) => v > 0 || 'La discussion doit durer au moins 1 jour'
            ]"
            >Durée de discution</InputNumber>
            <p class="duration__unit">jours</p>
        </div>
        <div class="duration">
            <InputNumber 
            name="voteDuration"
            placeholder="ex : 4"
            :min="1"
            required
            :rules="[
                (v) => Boolean(v) || 'Champ requis',
                (v) => v > 0 || 'Le vote doit durer au moins 1 jour'
            ]"
            >Durée de vote</InputNumber>
            <p class="duration__unit">jours</p>
        </div>
    </section>
    <section v-if="!(votingType == 1 || votingType == 2)" class="vote__possibilities">
        <h3>Possibilités</h3>
        <p>Composez la liste de possibilité du vote</p>

        <div class="vote__possibilities__container">
            <div v-for="possibility, key in possibilities">
                <input class="input--classique"
                type="text" placeholder="Indiquer une possibilité" no-label
                :name="'possibility'+key" v-model="possibilities[key]">
                </input>
                <button tabindex="-1" class="error btn"@click="removePossibility(key)">X</button>
            </div> 
        </div>
        <button class="btn btn--small" @click="addPossibility()">Ajouter une possibilité</button>
    </section>
    <section class="vote__actions">
        <button class="btn btn--small"
        :disabled="!(voteDurationValid || discussionDurationValid)"
        >Valider</button>
        <button class="btn btn--cancel btn--small">Annuler</button>
    </section>

</main>

</template>

<script setup>

const config = useRuntimeConfig();
const route = useRoute();

definePageMeta({
  middleware: ["auth", "proposal"]
})

const proposal = ref({});
const types = ref([]);
const possibilities = ref(['']);
const systems = ref(['']);

const voteDurationValid = useState('voteDurationValid');
const discussionDurationValid = useState('discussionDurationValid');

const votingType = useState("votingType");

const fetchData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}`, {
            credentials: 'include',
        });
        proposal.value = response;

        const sys = await $fetch(`${config.public.baseUrl}/votes/systems`, {
            credentials: 'include',
        });
        systems.value = sys;
    }catch(error){
        console.error('An unexpected error occurred:', error);
    }
}

const addPossibility = () => {
    possibilities.value.push('');
}

const removePossibility = (index) => {
    possibilities.value.splice(index, 1);
}

onMounted(() => {
    fetchData();
})

</script>