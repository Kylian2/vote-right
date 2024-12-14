<template>
    <Header type="notlogged"></Header>
    <main class="invitation">
        <div class="invitation__image" v-if="community" 
            :style= "{ background: `url('/images/communities/${community['CMY_image_VC']}') 0% 15% / cover` }">
        </div>
        <div class="invitation__content" v-if="invitation && community">
            <h2> {{ invitation['INV_sender_firstname_VC'] }} {{ invitation['INV_sender_lastname_VC'] }} vous invite à rejoindre le groupe "{{ community["CMY_name_VC"] }}"</h2>
            <p> En rejoignant le groupe, vous pourrez faire des propositions et participer aux votes. </p>
        </div>
        <div class="invitation__community">
            <div class="invitation__community-themes">
                <p class="invitation__community-themes-header"> Le groupe traite des thèmes : </p>
                <ul class="invitation__community-themes-list" v-if="communityThemes && communityThemes.length">
                    <li v-for="theme in communityThemes"> {{ theme['THM_name_VC'] }} </li>
                </ul>
            </div>
            <div class="invitation__community-verification">
                <Input 
                    type="text" name="codeSecurite" placeholder="(ex : 132592)" required
                    :rules="[
                        (v) => Boolean(v) || 'Un code de sécurité est requis', 
                        (v) => v.length == 6 || 'Le code de sécurité doit comporter 6 chiffres', 
                    ]" 
                > Code de sécurité </Input>
            </div>
        </div>
        <div class="invitation__user-actions">
            <Button class="btn btn--full" :disabled="!codeCorrect"  @click="visualisationGroupe()"> Accepter </Button>
            <Button class="btn btn--cancel" @click="redirectionLogin()"> Refuser </Button>
        </div>
    </main>
</template>
  
<script setup>

const config = useRuntimeConfig();
const route = useRoute();

onMounted(() => {
    fetchData();
})

const invitation = ref();
const community = ref();
const communityThemes = ref([]);

const codeSecurite = useState("codeSecurite");

const codeCorrect  = computed(() => {
    return codeSecurite.value.length == 6;
})

const redirectionLogin = async () => {
    try {
        navigateTo('/login');

    } catch (error) {
        console.error('An error occurred : ', error);
    }
}

const visualisationGroupe = async () => {
    try {
        if(codeSecurite == invitation.value.INV_code_VC){
            const response = await $fetch(`${config.public.baseUrl}/communities/registration`, {
            method: 'POST',
            body: {
                memberId: invitation.value.INV_recipient_NB,
                communityId: invitation.value.INV_community_NB,
            }
            });

            if(response){
                navigateTo('/community/${invitation.value.INV_community_NB}');
            }
        }
        else{
            alert("Information : Le code que vous avez entré n'est pas valide");
        }

    } catch (error) {
        console.error('An error occurred : ', error);
    }
}

const fetchData = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/invitation/${route.params.id}`, {
            credentials: 'include',
        });

        invitation.value = response;

        const response2 = await $fetch(`${config.public.baseUrl}/communities/${invitation.value.INV_community_NB}`, {
            credentials: 'include',
        });

        community.value = response2;

        const response3 = await $fetch(`${config.public.baseUrl}/communities/${invitation.value.INV_community_NB}/themes`, {
            credentials: 'include',
        });

        communityThemes.value = response3;

    } catch (error) {
        console.error("An error occurred : ", error);
    }
}

</script>