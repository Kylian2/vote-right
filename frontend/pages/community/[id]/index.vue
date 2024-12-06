<template>
    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>

    <div v-if="community" class="community__hero-banner__wrapper"
        :style="{ 
            background: `url(/images/communities/${community['CMY_image_VC']}) 0% 15% / cover`,
            backgroundSize: `cover`
        }"
    >
        <div class="community__hero-banner">
            <div></div>
            <div class="community__hero-banner__infos">
                <h1>{{ community['CMY_name_VC'] }}</h1>
                <div v-if="communityThemes">
                    <span v-for="theme in communityThemes" class="community__hero-banner__infos__theme" :style="{ 
                        background: community['CMY_color_VC'],
                    }">{{ theme['THM_name_VC'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <main class="community" v-if="community">

        <div class="community__action-block">
            <NuxtLink to="#" class="btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">Nouvelle proposition</NuxtLink>
            <NuxtLink :to="`${$route.params.id}/members`" class="btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">Voir les membres</NuxtLink>
            <NuxtLink v-if="role && role['MEM_role_NB'] != 5" to="#" class="btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">ADMINPANEL</NuxtLink>
        </div>

        <div class="community__description">
            <h2>Description</h2>
            <p v-if="community['CMY_description_TXT']">{{ community['CMY_description_TXT'] }}</p>
            <p v-else> Aucune description pour le groupe</p>
        </div>

        <div class="community__ongoing-proposals">
            <h2>Propositions en cours</h2>
            <div class="community__ongoing-proposals__list" v-if="ongoingProposals && ongoingProposals.length">
                <CardProposal v-for="proposal in ongoingProposals" :proposal="proposal"></CardProposal>
            </div>
            <p v-else>Aucune propositions en cours</p>
        </div>

        <div class="community__finished-proposals">
            <div>
                <h2>Propositions terminées</h2>
                <NuxtLink to="#">Tout voir</NuxtLink>
            </div>
            <div class="community__ongoing-proposals__list" v-if="finishedProposals && finishedProposals.length">
                <CardProposal v-for="proposal in finishedProposals" :proposal="proposal"></CardProposal>
            </div>
            <p v-else>Aucune propositions en cours</p>
        </div>
    </main>
</template>
<script setup>

const config = useRuntimeConfig();

definePageMeta({
    middleware: ["auth"]
})

const route = useRoute();

const community = useState("community");
const communityThemes = useState("communityThemes");
const role = ref();
const ongoingProposals = ref();
const finishedProposals = ref();

const fetchData = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

        community.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }

    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/themes`, {
            credentials: 'include',
        })

        communityThemes.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }

}

const fetchRole = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/users/me/role/${route.params.id}`, {
            credentials: 'include',
        })

        role.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchOngoingProposal = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/ongoing`, {
            credentials: 'include',
        })

        ongoingProposals.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchFinishedProposal = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/finished`, {
            credentials: 'include',
        })

        finishedProposals.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

onMounted(()=>{
    fetchData();
    fetchRole();
    fetchOngoingProposal();
    fetchFinishedProposal();
})

</script>