<template>
    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>

    <Banner v-if="proposal" :community="community" :themes="[{'THM_name_VC' : proposal['PRO_theme_VC']}]" back>{{ proposal["PRO_title_VC"] }}</Banner>
    <main class="proposal">

        <section>
            <div class="proposal__description">
                <h2>Description</h2>
                <p v-if="proposal && proposal['PRO_description_TXT']">{{ proposal['PRO_description_TXT'] }}</p>
                <p v-else> Aucune description pour le groupe</p>
            </div>  
            <div v-if="reactions && !reactions['hasReacted']" class="proposal__opinion" :class="{ 'disapear' : saveReaction}">
                <h2>Donner son avis</h2>
                <button v-if="!saveReaction" :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="btn btn--full" @click="react(LIKE)">J'aime</button>
                <button v-if="!saveReaction" :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="btn btn--full" @click="react(DISLIKE)">Je n'aime pas</button>
                <p v-if="saveReaction">Avis enregistré !</p>
            </div>
            <div class="proposal__discussion">
                <h2>Discussion</h2>
                <div class="proposal__discussion__comments-container" v-if="comments && me">
                    <Comment v-for="comment in comments" :comment="comment" :class="{ 'self-end': (comment['COM_sender_NB'] == me['USR_id_NB'])}" :hideName="(comment['COM_sender_NB'] == me['USR_id_NB'])"></Comment>
                    <p v-if="comments.lenght === 0">Soyez le premier à commenter</p>
                </div>
                <div class="proposal__discussion__send-message">
                    <Input type="text" name="comment" placeholder="Écrire un message..." no-label :displayError="false"
                        :rules="[
                            (v) => Boolean(v) ,
                        ]"
                    ></Input>
                    <button @click="sendMessage()" :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="btn btn--full" :disabled="!commentValid">Envoyer</button>
                </div>
            </div>
        </section>

        <section>
            <div v-if="initiator" class="proposal__initiator">
                <p :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="profile-initials">{{ getInitials(initiator['USR_firstname_VC'], initiator['USR_lastname_VC']) }}</p>
                <div>
                    <p>{{ initiator['USR_firstname_VC'] }} <b>{{ initiator['USR_lastname_VC'] }}</b></p>
                    <p class="legende">le {{ formatDate(new Date(proposal['PRO_creation_DATE'])) }}</p>
                </div>
            </div>

            <div v-if="proposal && community" class="proposal__informations"
                :style="{
                    background: community['CMY_color_VC']
                }"
            >
                <p v-if="proposal['PRO_theme_VC']"><img src="/images/icons/theme.svg" alt="icons-theme">{{ proposal['PRO_theme_VC'] }}</p>
                <p v-if="proposal['PRO_budget_NB']"><img src="/images/icons/budget.svg" alt="icons-theme">{{ proposal['PRO_budget_NB'] }}<span>€/an</span></p>
                <p v-if="proposal['PRO_location_VC']"><img src="/images/icons/location.svg" alt="icons-theme">{{ proposal['PRO_location_VC'] }}</p>
                <p v-if="proposal['PRO_period_YEAR']"><img src="/images/icons/date.svg" alt="icons-theme">{{ proposal['PRO_period_YEAR'] }}</p>
            </div>

            <div v-if="proposal && community && formalRequest" class="proposal__request">
                <h3>Demande Formelle</h3>
                <button @click="makeFormalRequest()" class="btn btn--full btn--block" :style="{background: community['CMY_color_VC']}" :disabled="formalRequest['hasAsked']">
                    <img src="/images/icons/ticks.png" alt="validation-ticks">
                    <span>{{ formalRequest['hasAsked'] ? 'Demandé' : 'Demander'}}</span>    
                </button>
                <div>
                    <p class="legende" v-if="proposal['PRO_discussion_duration_NB']">
                    En discussion jusqu'au {{ formatDate(new Date(new Date(proposal["PRO_creation_DATE"]).setDate(new Date(proposal["PRO_creation_DATE"]).getDate() + proposal['PRO_discussion_duration_NB']))) }}
                    </p>           
                    <p class="legende" v-else>La durée de discussion n'est pas définie</p>
                    <p v-if="formalRequest['hasAsked']" class="legende">Le vote sera lancé quand une majorité de membre en auront fait la demande.</p>
                </div>
            </div>

            <div class="proposal__opinions" v-if="reactions && reactions['hasReacted']">
                <h3>Avis</h3>
                <p v-if="reactions['hasReacted'] === LOVE  || reactions['hasReacted'] === LIKE">Vous et {{ (reactions["nblove"] + reactions["nblike"]) }} personnes 
                    <span :style="{color: (community ? community['CMY_color_VC'] : '#222222')}">aiment</span> cette proposition.</p>
                <p v-if="reactions['hasReacted'] === HATE  || reactions['hasReacted'] === DISLIKE">Vous et {{ (reactions["nbhate"] +  reactions["nbdislike"]) }} personnes 
                    <span :style="{color: (community ? community['CMY_color_VC'] : '#222222')}">n'aiment pas</span> cette proposition.</p>
            </div>
        </section>

    </main>
</template>
<script setup>

const config = useRuntimeConfig();
const route = useRoute();

definePageMeta({
    middleware: ["auth"]
})

const LOVE = 3;
const LIKE = 1;
const DISLIKE = 2;
const HATE = 4;

const community = useState("community");
const proposal = ref();
const initiator = ref();
const comments = ref();
const comment = useState('comment');
const commentValid = useState('commentValid');
const me = ref();

const reactions = ref();
const saveReaction = ref(false);

const formalRequest = ref();

const formatDate = (date) => {
  if (!date) return "";
  return date.toLocaleDateString("fr-FR", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });
};

function getInitials(string1, string2) {
    const initial1 = string1.trim().charAt(0).toUpperCase();
    const initial2 = string2.trim().charAt(0).toUpperCase();
    return initial1 + initial2;
}

const sendMessage = async () => {
    const response = await $fetch(`${config.public.baseUrl}/comments`, {
        method: 'POST',
        credentials: 'include',
        body:{
            message: comment.value,
            proposal: route.params.id,
        }
    });

    if(response){
        comments.value.push(response);
        comment.value = '';
    }
}

const fetchUser = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include',
        });

        me.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

const fetchData = async () => {
    try {

        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}`, {
            credentials: 'include',
        });
        proposal.value = response;

        if (!community.value) {

            if (!proposal.value?.PRO_community_NB) {
                console.warn('Community number not found in proposal data');
                return;
            }

            const com = await $fetch(`${config.public.baseUrl}/communities/${proposal.value['PRO_community_NB']}`, {
                credentials: 'include',
            });
            
            community.value = com;
        }

        if (!proposal.value?.PRO_initiator_NB) {
            console.warn('Community number not found in proposal data');
            return;
        }
        
        const user = await $fetch(`${config.public.baseUrl}/users/${proposal.value['PRO_initiator_NB']}`, {
            credentials: 'include',
        });

        initiator.value = user;
    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }
};

const fetchComments = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/comments`, {
            credentials: 'include',
        });

        comments.value = response;
    } catch (error) {
        console.log("An error occured", error);
    }
}

const fetchReaction = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/reactions`, {
            credentials: 'include',
        })

        reactions.value = response;

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const react = async (reaction) => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/react`, {
            method: 'POST',
            credentials: 'include',
            body: {
                reaction: reaction
            }
        })

        if (response) {

            saveReaction.value = true;

            setTimeout(() => {
                reactions.value['hasReacted'] = reaction;
            }, 4000);

            switch (reaction) {
                case 1:
                    reactions.value['nblike']++;
                    break;
                case 2:
                    reactions.value['nbdislike']++;
                    break;
                case 3:
                    reactions.value['nblove']++;
                    break;
                case 4:
                    reactions.value['nbhate']++;
                    break;
                default:
                    break;
            }
        }

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchFormalRequest = async () => {
    const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/requests`, {
        credentials: 'include'
    })

    formalRequest.value = response;
}

const makeFormalRequest = async () => {

    const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/requests`, {
        method: 'POST',
        credentials: 'include'
    })

    if(response){
        formalRequest.value['hasAsked'] = true;
    }
    

}

onMounted(() => {
    fetchData();
    fetchComments();
    fetchUser();
    fetchReaction();
    fetchFormalRequest();
})

</script>