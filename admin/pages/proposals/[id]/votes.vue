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
        <Select name="votingType" :options="types">Selectionnez un mode de scrutin</Select>
        <div class="duration">
            <InputNumber 
            name="discussionDuration"
            placeholder="ex : 4"
            >Durée de discution</InputNumber>
            <p class="duration__unit">jours</p>
        </div>
        <div class="duration">
            <InputNumber 
            name="voteDuration"
            placeholder="ex : 4"
            >Durée de vote</InputNumber>
            <p class="duration__unit">jours</p>
        </div>
    </section>
    <section class="vote__possibilities">

        <h3>Possibilités</h3>
        <p>Composez la liste de possibilité du vote</p>

        <div class="vote__possibilities__container">
            <div v-for="key in 5">
                <Input 
                type="text" placeholder="Indiquer une possibilité" no-label
                :name="'possibility'+key">
                </Input>
                <button class="error btn">X</button>
            </div>
        </div>
        <button class="btn btn--small">Ajouter une possibilité</button>
    </section>
    <section class="vote__actions">
        <button class="btn btn--small">Valider</button>
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

const fetchData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}`, {
            credentials: 'include',
        });
        proposal.value = response;
    }catch(error){
        console.error('An unexpected error occurred:', error);
    }
}

onMounted(() => {
    fetchData();
})

</script>