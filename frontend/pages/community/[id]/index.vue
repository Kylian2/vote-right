<template>
    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>

    <Banner :community="community" :themes="communityThemes">{{ community["CMY_name_VC"] }}</Banner>

    <main class="community" v-if="community">

        <div class="community__action-block">
            <NuxtLink :to="`${route.params.id}/new/proposal`" class="btn btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">Nouvelle proposition</NuxtLink>
            <NuxtLink :to="`${$route.params.id}/members`" class="btn btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">Voir les membres</NuxtLink>
            <NuxtLink v-if="role && role['MEM_role_NB'] != 5" :to="`${config.public.adminUrl}/communities/${route.params.id}`" class="btn btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">ADMINPANEL</NuxtLink>
            <Button class="btn btn--full btn--block" :style="{
                background: '#909090'}" 
                @click="leaveGroupModal = !leaveGroupModal"> Quitter le groupe</Button>
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
            <p v-else>Aucune proposition en cours</p>
        </div>

        <div class="community__finished-proposals">
            <div class="community__finished-proposals__infos">
                <h2>Propositions terminées</h2>
                <NuxtLink :to="`${$route.params.id}/proposals`">Tout voir</NuxtLink>
            </div>
            <div class="community__ongoing-proposals__list" v-if="finishedProposals && finishedProposals.length">
                <CardProposal v-for="proposal in finishedProposals" :proposal="proposal"></CardProposal>
            </div>
            <p v-else>Aucune proposition terminée</p>
        </div>
    </main>

    <Modal 
    name="leaveGroup"
    :ok-text= "leaveModalData.okText"
    :cancel-text= "leaveModalData.cancelText"
    :before-ok= "leaveModalData.beforeOk"
    >
        <template #title> {{ leaveModalData.title }} </template>
        <template #body>
            <p> {{ leaveModalData.body }} </p>
        </template>
    </Modal>

</template>

<script setup>

const config = useRuntimeConfig();

definePageMeta({
    middleware: ["auth", "community-member"],
})

const route = useRoute();

onMounted(()=>{
    fetchData();
    fetchRole();
    fetchOngoingProposal();
    fetchFinishedProposal();
})

const community = useState("community");
const communityThemes = useState("communityThemes");
const ongoingProposals = ref();
const finishedProposals = ref();
const currentUser = ref();
const role = ref();

const leaveGroupModal = useState('leaveGroupModal', () => false);
const initialState = () => {
    return {
        title: 'Quitter le groupe',
        body: 'Êtes-vous sûr de vouloir quitter le groupe ? Vous ne pourrez pas revenir sans invitation.',
        okText: 'Quitter',
        cancelText: 'Rester',
        beforeOk: () => beforeLeave(),
    }
}
const leaveModalData = ref(initialState());

const leaveCommunityToast = useState(`leaveCommunityToastMustUp`, () => false);

const beforeLeave = async () => {
    try {

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/delete/${currentUser.value.USR_id_NB}`, {
            method: 'DELETE',
            credentials: 'include',
        });
        
        if(response == true){
            leaveGroupModal.value = false;
            leaveCommunityToast.value = true;
            navigateTo("/home");
        }else{
            leaveModalData.value.title = "Impossible de quitter le groupe";
            leaveModalData.value.body = "Vous êtes le seul administrateur. Promouvez un autre admin avant de quitter.";
            leaveModalData.value.okText = "J'ai compris";
            leaveModalData.value.cancelText = "";
            leaveModalData.value.beforeOk = () => {
                leaveModalData.value = initialState(); 
                leaveModalData.value.body = `Vous allez quitter le groupe « ${community.value.CMY_name_VC} », vous ne pourrez pas revenir sans invitation.`};
            setTimeout(() => {
                leaveGroupModal.value = true;
            }, 200);
        }

    } catch (error) {
        console.error('An error occurred : ', error);
    }
}

const fetchData = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

        community.value = response;
        leaveModalData.value.body = `Vous allez quitter le groupe « ${community.value.CMY_name_VC} », vous ne pourrez pas revenir sans invitation.`;   

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

    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include',
        })

        currentUser.value = response;

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

</script>