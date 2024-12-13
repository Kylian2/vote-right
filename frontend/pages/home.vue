<template>
    <Header type="logged" actif="accueil"></Header>
    <h1> &#x1F44B; Bonjour <span class="shine-2">{{ firstname }}</span></h1>

    <main class="home">
        <div class="communities_contener">

            <div class="communities_contener__header">
                <h2>Les Groupes</h2>
                <NuxtLink to="/communities">Tout voir</NuxtLink>
            </div>

            <div v-if="communities" class="communities_contener__bloc">
                <CardCommunity v-for="community in communities" :community="community"></CardCommunity>
                <p class="communities_contener__bloc__no-community" v-if="communities.length === 0">Vous ne participez à aucune groupe, rejoignez en ou <NuxtLink to="/community/new">créez le votre ici.</NuxtLink></p>
            </div>
        </div>
        <div class="proposals">
            <div class="proposals__block">
                <h3>Proposition en cours</h3>
                <div class="proposals__contener">
                    <CardProposal v-for="proposal in ongoingProposals" :proposal="proposal"></CardProposal>
                    <p class="communities_contener__bloc__no-community" v-if="ongoingProposals && ongoingProposals.length === 0">Aucune proposition en cours.</p>
                </div>
            </div>

            <div class="proposals__block">
                <h3>Proposition terminées</h3>
                <div class="proposals__contener">
                    <CardProposal v-for="proposal in finishedProposals" :proposal="proposal"></CardProposal>
                    <p class="communities_contener__bloc__no-community" v-if="ongoingProposals && finishedProposals.length === 0">Aucune proposition terminées</p>
                </div>
            </div>
        </div>
    </main>
</template>
<script setup>

const config = useRuntimeConfig();

definePageMeta({
    middleware: ["auth"]
})

onMounted(() => {
    fetchData();
})

const communities = useState("communities");
const firstname = useState("firstname");
const ongoingProposals = useState("ongoingProposals");
const finishedProposals = useState("finishedProposals");

const fetchData = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/communities`, {
            credentials: 'include',
        });

        communities.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include',
        });

        firstname.value = response['USR_firstname_VC'];
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/ongoing`, {
            credentials: 'include',
        });

        ongoingProposals.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/finished`, {
            credentials: 'include',
        });

        finishedProposals.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

</script>   