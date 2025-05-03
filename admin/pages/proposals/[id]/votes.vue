<template>

<Header></Header>
<NuxtLink class="back" :to="`/proposals/${$route.params.id}`">Retour à la proposition</NuxtLink>
<div class="vote__title">
    <h1>{{ proposal['PRO_title_VC'] }}</h1>
    <h4>Modalité de vote</h4>
</div>
<main class="vote">
    <section class="vote__informations">
        <h3>Informations du vote</h3>
        <Select v-if="(votes.length > 0 ? !hasPassed(votes[0]['VOT_start_DATE']) : true)" name="system" :options="systems.map((s)=> [s['SYS_label_VC'], s['SYS_id_NB']])" 
            placeholder="Selectionner" 
            >Selectionnez un mode de scrutin</Select>
        <p v-else><b>Type de scrution :</b> {{ votes[0]['VOT_type_VC'] }}</p>

        <div v-if="(votes.length > 0 ? !hasPassed(votes[0]['VOT_start_DATE']) : true)" class="duration">
            <InputNumber 
            name="discussionDuration"
            placeholder="ex : 4"
            :min="1"
            required
            :rules="[
                (v) => Boolean(v) || 'Champ requis',
                (v) => v > 0 || 'La discussion doit durer au moins 1 jour'
            ]"
            >Durée de discussion</InputNumber>
            <p class="duration__unit">jours</p>
        </div>
        <p v-else><b>Durée des discussions :</b> {{ proposal['PRO_discussion_duration_NB'] }} jours</p>

        <div v-if="(votes.length > 0 ? !hasPassed(votes[0]['VOT_start_DATE']) : true)" class="duration">
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
        <p v-else><b>Durée des votes :</b> {{ timeBetween(votes[0]['VOT_start_DATE'], votes[0]['VOT_end_DATE']) }} jours</p>
        <p v-if="!(votes.length > 0 ? !hasPassed(votes[0]['VOT_start_DATE']) : true)" >
            {{ hasPassed(votes[0]['VOT_start_DATE']) ? 'Le vote a commencé le ' + formatDate(new Date(votes[0]['VOT_start_DATE'])) : 'Le vote commence le ' + formatDate(new Date(votes[0]['VOT_start_DATE'])) }}
        </p>
    </section>
    <section v-if="!(system == 1 || system == 2) && (votes.length > 0 ? !hasPassed(votes[0]['VOT_start_DATE']) : true)" class="vote__possibilities">
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

    <section class="vote__actions" v-if="(votes.length > 0 ? !hasPassed(votes[0]['VOT_start_DATE']) : true)">
        <button class="btn btn--small btn--valid"
        :disabled="!(voteDurationValid || discussionDurationValid)"
        @click="sendData"
        >Valider</button>
    </section>
    <!--Affichage de la liste des possibilités quand le vote a déjà commencé -->
    <section v-if="!(system == 1 || system == 2) && (votes.length > 0 ? hasPassed(votes[0]['VOT_start_DATE']) : false)" class="vote__possibilities">
        <h3>Possibilités</h3>
        <p>Voici la liste des possibilités du vote : </p>

        <div v-if="votes[0]" class="vote__possibilities__container">
            <ul v-for="possibility, key in votes[0]['VOT_possibilities_TAB']">
                <li>{{ possibility[0] }}</li>
            </ul> 
        </div>
    </section>
</main>

<Toast 
    name="forbidden" 
    :type="1" 
    :time="5" 
    :loader="true"
    class="toast"
>
Vous n'avez pas les droits pour effectuer cette action
</Toast>

<Toast 
    name="errorSendingData" 
    :type="1" 
    :time="5" 
    :loader="true"
    class="toast"
>
Une erreur est survenue
</Toast>

<Toast 
    name="dataIsSend" 
    :type="3" 
    :time="5" 
    :loader="true"
    class="toast"
>
Paramètres enregistrés
</Toast>

</template>

<script setup>

const config = useRuntimeConfig();
const route = useRoute();

definePageMeta({
  middleware: ["auth", "assessor"]
})

const proposal = ref({});
const possibilities = ref(['']);
const systems = ref([]);
const votes = ref([]);

const voteDuration = useState('voteDuration', () => null);
const voteDurationValid = useState('voteDurationValid', () => null);
const discussionDuration = useState('discussionDuration', () => null);
const discussionDurationValid = useState('discussionDurationValid', () => null);

const system = useState("system");

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

        const vot = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/votes`, {
            credentials: 'include',
        });
        votes.value = vot;

        if (votes.value.length > 0){
            voteDuration.value = timeBetween(votes.value[0]['VOT_start_DATE'], votes.value[0]['VOT_end_DATE']);
            discussionDuration.value = proposal.value['PRO_discussion_duration_NB'];
            possibilities.value = votes.value[0]['VOT_possibilities_TAB'].map((p) => p[0])
            system.value = votes.value[0]['VOT_type_NB']
        }

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

const timeBetween = (date1, date2) => {

    const d1 = new Date(date1);
    const d2 = new Date(date2);
    
    if (isNaN(d1) || isNaN(d2)) {
        return "Invalid dates";
    }

    let diff = d2 - d1;
    diff = diff / (1000 * 60 * 60 * 24);
    return Math.abs(Math.floor(diff)); // Math.abs pour ne pas avoir de valeur négative
}

const hasPassed = (date) => {
    return new Date() > new Date(date);
}

const formatDate = (date) => {
    if (!date) return "";
    return date.toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const forbidden = useState('forbiddenUp');
const errorSendingData = useState('errorSendingDataUp');
const dataIsSend = useState('dataIsSendUp');
const sendData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/votes`, {
            method: `${votes.value.length === 0 ? 'POST' : 'PUT'}`,
            credentials: 'include',
            body: {
                system: system.value,
                discussionDuration: discussionDuration.value,
                voteDuration: voteDuration.value,
                possibilities: possibilities.value,
            }
        });

        if(response == true){
            dataIsSend.value = true;
        }else{
            errorSendingData.value = true;
        }

        const vot = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/votes`, {
            credentials: 'include',
        });
        votes.value = vot;
    }catch (error){
        console.log('An unexpected error occured : ', error);
        if(error.status === 403){
            forbidden.value = true;
        }
        if(error.status === 422){
            errorSendingData.value = true;
        }
        if(error.status === 400){
            errorSendingData.value = true;
        }
    }

    
}

onMounted(() => {
    fetchData();
})

onBeforeUnmount(() => {
    useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    })
})

</script>