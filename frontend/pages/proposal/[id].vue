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
            <div class="proposal__opinion">
                <h2>Donner son avis</h2>
                <button :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="btn btn--full">J'aime</button>
                <button :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="btn btn--full">Je n'aime pas</button>
            </div>
            <div class="proposal__discussion">
                <h2>Discussion</h2>
                <div class="proposal__discussion__comments-container" v-if="comments">
                    <Comment v-for="comment in comments" :comment="comment"></Comment>
                    <p v-if="comments.lenght === 0">Soyez le premier à commenter</p>
                </div>
                <div class="proposal__discussion__send-message">
                    <Input type="text" name="comment" placeholder="Écrire un message..." no-label :displayError="false"
                        :rules="[
                            (v) => Boolean(v) ,
                        ]"
                    ></Input>
                    <button :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="btn btn--full" :disabled="!commentValid">Envoyer</button>
                </div>
            </div>
        </section>

        <section>
            <div v-if="initiator" class="proposal__initiator">
                <p :style="{background: (community ? community['CMY_color_VC'] : '#222222')}" class="profile-initials">{{ getInitials(initiator['USR_firstname_VC'], initiator['USR_lastname_VC']) }}</p>
                <div>
                    <p>{{ initiator['USR_firstname_VC'] }} <b>{{ initiator['USR_lastname_VC'] }}</b></p>
                    <p class="legende">{{ formatDate(new Date(proposal['PRO_creation_DATE'])) }}</p>
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

            <div v-if="proposal && community" class="proposal__request">
                <h3>Demande Formelle</h3>
                <button class="btn btn--full btn--block" :style="{background: community['CMY_color_VC']}">Demander</button>
                <p class="legende" v-if="proposal['PRO_discussion_duration_NB']">
                    En discussion jusqu'au {{ formatDate(new Date(new Date(proposal["PRO_creation_DATE"]).setDate(new Date(proposal["PRO_creation_DATE"]).getDate() + proposal['PRO_discussion_duration_NB']))) }}
                </p>           
                <p class="legende" v-else>La durée de discussion n'est pas définie</p>
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

const community = useState("community");
const proposal = ref();
const initiator = ref();
const comments = ref();
const comment = useState('comment');
const commentValid = useState('commentValid');

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

onMounted(() => {
    fetchData();
    fetchComments();
})

</script>