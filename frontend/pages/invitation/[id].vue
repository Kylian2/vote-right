<template>
    <Header type="notlogged"></Header>
    <main>
        <div v-if="invitationUnavailable">
            <h3 style="text-align: center; margin-top: 100px; margin-bottom: 100px;"> Cette invitation est inaccessible </h3>
        </div>

        <div v-else class="invitation">
            <div class="invitation__image" v-if="community" 
                :style= "{ background: `url('/images/communities/${community['CMY_image_VC']}') 0% 15% / cover` }">
            </div>

            <div v-if="invitationExpired">
                <h3 style="text-align: center; margin-top: 20px; margin-bottom: 20px;"> Cette invitation a expiré </h3>
            </div>

            <div v-else class="invitation__show">
                <div class="invitation__show__content" v-if="invitation && community">
                    <h2> {{ invitation['INV_sender_firstname_VC'] }} {{ invitation['INV_sender_lastname_VC'] }} 
                        vous invite à rejoindre le groupe 
                        <span :style="{ color: `${community['CMY_color_VC']}`}"> {{ community["CMY_name_VC"] }} </span>
                    </h2>
                    <p> En rejoignant le groupe, vous pourrez faire des propositions et participer aux votes. </p>
                </div>

                <div class="invitation__show__community">
                    <div class="invitation__show__community__themes">
                        <p class="invitation__show__community__themes__header"> Le groupe traite des thèmes : </p>
                        <div class="invitation__show__community__themes__list" v-if="communityThemes && communityThemes.length">
                            <p v-for="theme in communityThemes"> {{ theme['THM_name_VC'] }} </p>
                        </div>
                    </div>
                </div>

                <div class="invitation__show__actions">
                    <div class="invitation__show__actions__input">
                        <InputNumber
                            name="securityCode" placeholder="(ex : 132592)" required :min="100000" :max="999999"
                            :rules="[
                                (v) => Boolean(v) || 'Un code de sécurité est requis', 
                                (v) => (v >= 100000 && 999999 >= v) || 'Le code de sécurité doit comporter 6 chiffres', 
                            ]" 
                            > Code de sécurité 
                        </InputNumber>
                    </div>
                        
                    <div class="invitation__show__actions__button">
                        <Button class="btn btn--full" :disabled="!validCode" @click="acceptInvitation()"> Accepter </Button>
                        <Button class="btn btn--cancel" :disabled="!validCode" @click="rejectInvitation()"> Refuser </Button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
  
<script setup>
import Input from '~/components/Input.vue';

const config = useRuntimeConfig();
const route = useRoute();

onMounted(() => {
    fetchData();
})

const invitation = ref();
const community = ref();
const communityThemes = ref([]);

const securityCode = useState("securityCode");
const validCode = useState("securityCodeValid");

const invitationExpired = ref();
const invitationUnavailable = ref();

const acceptInvitation = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/invitation/${route.params.id}/accept`, {
        method: 'POST',
            body: {
                codeSend: securityCode.value,
                communityId: invitation.value.INV_community_NB,
                newMemberId: invitation.value.INV_recipient_NB,
            }
        });

        if(response["Invalid code"]){
            alert('Le code de validation que vous avez entré est incorrect');
            return ;
        }else{
            navigateTo(`/community/${invitation.value.INV_community_NB}`);
        }
        
    } catch (error) {
        console.error('An error occurred : ', error);
    }
}

const rejectInvitation = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/invitation/${route.params.id}/reject`, {
        method: 'POST',
            body: {
                codeSend: securityCode.value,
            }
        });

        if(response["Invalid code"]){
            alert('Le code de validation que vous avez entré est incorrect');
        }else{
            navigateTo('/login');
        }
    } catch (error) {
        console.error('An error occurred : ', error);
    }
}

const fetchData = async () => {
    try {
        const response1 = await $fetch(`${config.public.baseUrl}/invitation/${route.params.id}`, {
            credentials: 'include',
        });

        if(response1.invitation_status){
            invitationUnavailable.value = true;
            return ;
        }

        if (response1.INV_joursDepuisInvitation_NB > 7) {
            invitationExpired.value = true;
        } else {
            invitationExpired.value = false;
        }

        invitation.value = response1;

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