 <template>
    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>

    <Banner v-if="proposal" :community="community" :themes="[{'THM_name_VC' : proposal['PRO_theme_VC']}]" back>{{ proposal["PRO_title_VC"] }}</Banner>
    <main class="proposal">
            <section class="section-1">
                <div class="proposal__description">
                    <h2>Description</h2>
                    <p v-if="proposal && proposal['PRO_description_TXT']">{{ proposal['PRO_description_TXT'] }}</p>
                    <p v-else> Aucune description pour le groupe</p>
                </div>  

                <div v-if="reactions && !reactions['hasReacted']" class="proposal__opinion" :class="{ 'disapear' : saveReaction}">
                    <h2>Donner son avis</h2>
                    <div>
                        <button v-if="!saveReaction" :style="{background: communityColor}" class="btn btn--full" @click="react(LIKE)">J'aime</button>
                        <button v-if="!saveReaction" :style="{background: communityColor}" class="btn btn--full" @click="react(DISLIKE)">Je n'aime pas</button>
                        <p v-if="saveReaction">Avis enregistré !</p>
                    </div>
                </div>
                <div class="proposal__discussion">
                    <h2>Discussion</h2>
                    <div class="proposal__discussion__comments-container" v-if="comments && me">
                        <Comment v-for="comment in comments" 
                        :key="comment['COM_id_NB']" 
                        :comment="comment" 
                        :right="(comment['COM_sender_NB'] == me['USR_id_NB'])" 
                        :hideName="(comment['COM_sender_NB'] == me['USR_id_NB'])" 
                        :reasons="reasons"></Comment>
                        <p v-if="comments.length === 0">Soyez le premier à commenter</p>
                    </div>
                    <div class="proposal__discussion__send-message">
                        <Input type="text" name="comment" placeholder="Écrire un message..." no-label :displayError="false"
                            :rules="[
                                (v) => Boolean(v) ,
                            ]"
                        ></Input>
                        <button @click="sendMessage()" :style="{background: communityColor}" class="btn btn--full" :disabled="!commentValid">Envoyer</button>
                    </div>
                </div>
            </section>

            <div v-if="initiator" class="proposal__initiator">
                <p :style="{background: communityColor}" class="profile-initials">{{ getInitials(initiator['USR_firstname_VC'], initiator['USR_lastname_VC']) }}</p>
                <div>
                    <p>{{ initiator['USR_firstname_VC'] }} <b>{{ initiator['USR_lastname_VC'] }}</b></p>
                    <p class="legende">le {{ formatDate(new Date(proposal['PRO_creation_DATE'])) }}</p>
                </div>
            </div>

            <div v-if="proposal" class="proposal__informations"
                :style="{
                    background: communityColor
                }"
            >
                <p v-if="proposal['PRO_theme_VC']"><img src="/images/icons/theme.svg" alt="icons-theme">{{ proposal['PRO_theme_VC'] }}</p>
                <p v-if="proposal['PRO_budget_NB']"><img src="/images/icons/budget.svg" alt="icons-theme">{{ proposal['PRO_budget_NB'] }}<span>€/an</span></p>
                <p v-if="proposal['PRO_location_VC']"><img src="/images/icons/location.svg" alt="icons-theme">{{ proposal['PRO_location_VC'] }}</p>
                <p v-if="proposal['PRO_period_YEAR']"><img src="/images/icons/date.svg" alt="icons-theme">{{ proposal['PRO_period_YEAR'] }}</p>
            </div>

            <div v-if="proposal && formalRequest" class="proposal__request">
                <h3>Demande Formelle</h3>
                <button @click="makeFormalRequest()" class="btn btn--full btn--block" :style="{background: communityColor}" :disabled="formalRequest['hasAsked']">
                    <img src="/images/icons/ticks.png" alt="validation-ticks">
                    <span>{{ formalRequest['hasAsked'] ? 'Demandé' : 'Demander'}}</span>    
                </button>
                <div>
                    <p class="legende" v-if="proposal['PRO_discussion_duration_NB']">
                    En discussion jusqu'au {{ formatDate(new Date(new Date(proposal["PRO_creation_DATE"]).setDate(new Date(proposal["PRO_creation_DATE"]).getDate() + proposal['PRO_discussion_duration_NB']))) }}
                    </p>           
                    <p class="legende" v-else>La durée de discussion n'est pas définie</p>
                    <p v-if="formalRequest['hasAsked']" class="legende">Le vote sera lancé quand une majorité de membres en aura fait la demande.</p>
                </div>
            </div>

            <div class="proposal__opinions" v-if="reactions && reactions['hasReacted']">
                <h3>Avis</h3>
                <p v-if="reactions['hasReacted'] === LOVE  || reactions['hasReacted'] === LIKE">Vous et {{ (reactions["nblove"] + reactions["nblike"]) }} personnes 
                    <span :style="{color: communityColor}">aiment</span> cette proposition.</p>
                <p v-if="reactions['hasReacted'] === HATE  || reactions['hasReacted'] === DISLIKE">Vous et {{ (reactions["nbhate"] +  reactions["nbdislike"]) }} personnes 
                    <span :style="{color: communityColor}">n'aiment pas</span> cette proposition.</p>
            </div>

            <!-- Système de vote -->

            <section class="vote-section">

                <div class="vote__results"v-if="results.length > 0 && currentVote" v-for="result, key in results">
                    <h3>{{ (currentVote['VOT_type_NB'] === 1 || currentVote['VOT_type_NB'] === 2) ? 'Résultat' : `Résultat - Tour ${key+1}`}}</h3>
                    <div class="vote__results__container">
                        <div v-for="possibility, key in result" class="result">
                            <p>{{ possibility['POS_label_VC'] }}</p>
                            <div class="result__stat">
                                <span class="result__stat__bar" 
                                :style="{
                                        background: communityColor,
                                        width: `${possibility['POS_percentNbVotes_NB']}%`
                                    }"></span>
                                <span class="result__stat__value legende">{{ possibility['POS_percentNbVotes_NB'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="currentVote && (results.length+1 < nbRounds || timeRemaining !== 0)" class="vote">
                    <h3>{{ classicSystem ? 'Vote' : `Vote - Tour ${currentVote['VOT_round_NB']}`}}</h3>
                    <div>
                        <p class="legende">{{ timeRemaining > 0 ? formatTimeRemaning(timeRemaining) : 'Le vote est terminé.'}}</p>
                        <p class="legende">Le vote est un {{currentVote['VOT_type_VC']}}</p>
                    </div>

                    <div 
                    v-if="!hasVoted && classicSystem" 
                    class="vote__options vote__options--classique">
                        <button v-for="possibility in currentVote['VOT_possibilities_TAB']"
                        class="btn btn--full" 
                        :class="{'select' : selectedVoteOption === possibility[1]}"
                        :style="{background: communityColor}"
                        @click="selectedVoteOption = possibility[1]"
                        :disabled="timeRemaining === 0"
                        >{{ possibility[0] }}</button>
                    </div>

                    <div 
                    v-if="!hasVoted && !classicSystem" 
                    class="vote__options vote__options--multiple">
                        <button v-for="possibility in currentVote['VOT_possibilities_TAB']"
                        class="btn btn--full" 
                        :class="{'select' : selectedVoteOption === possibility[1]}"
                        :style="{background: communityColor}"
                        @click="selectedVoteOption = possibility[1]"
                        :disabled="timeRemaining === 0"
                        >{{ possibility[0] }}</button>
                    </div>
                    <div v-if="!hasVoted && timeRemaining !== 0" class="vote__validation">
                        <p v-if="!hasVoted" class="legende">Attention, ce choix est irréversible</p>
                        <div class="vote__validation__wrapper"
                        :style="{background: communityColor}"
                        >
                            <div class="vote__validation__loader" :class="{ 'active': isVoting }">
                                <button 
                                :class="{ 'active': isVoting }"
                                class="btn btn--full"
                                @mousedown="startVoting()"
                                @mouseup="stopVoting()"
                                :disabled="selectedVoteOption === -1 || hasVoted"
                                > 
                                    {{ (selectedVoteOption === -1 )? 'Faites un choix' : (hasVoted ? "A voté" : "VOTER")}} 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
    </main>
    <Toast 
        name="reportValid" 
        :type="3" 
        :time="5" 
        :loader="true"
        class="toast"
    >
        Commentaire signalé !
    </Toast>
    <Toast 
        name="reportError" 
        :type="1" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Vous avez déjà signalé ce commentaire
    </Toast>
</template>
<script setup>

const config = useRuntimeConfig();
const route = useRoute();

definePageMeta({
    middleware: ["auth", "proposal-access"]
})

const LOVE = 3;
const LIKE = 1;
const DISLIKE = 2;
const HATE = 4;

const communityColor = ref("#222222");

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

const reasons = ref([]);

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

        if (!proposal.value?.PRO_community_NB) {
            console.warn('Community number not found in proposal data');
            return;
        }

        const com = await $fetch(`${config.public.baseUrl}/communities/${proposal.value['PRO_community_NB']}`, {
            credentials: 'include',
        });
        
        community.value = com;
        communityColor.value = community.value['CMY_color_VC'];

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
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/reactions`, {
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

const fetchReasons = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/reasons`, {
            credentials: 'include',
        })

        reasons.value = response.map((r) => [r['RES_label_VC'], r['RES_id_NB']]);

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

/* Système de vote */

const selectedVoteOption = ref(-1);
const votes = ref();
const currentVote = ref();
const results = ref([]);
const hasVoted = ref(false);

const isVoting = ref(false);
let votingTimer;
const requiredHoldTime = 3000;
const timeRemaining = ref(0);
const nbRounds = ref();
const classicSystem = ref();

const startVoting = () => {
    isVoting.value = true;
    votingTimer = setTimeout(() => {
        console.log('VOTE');
        hasVoted.value = true;
    }, requiredHoldTime);
}

const stopVoting = () => {
    isVoting.value = false;
    clearTimeout(votingTimer);
    console.log('STOP VOTE');
}

const fetchResult = async (round) => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/${round}/result`, {
            credentials: 'include',
        })

        results.value.push(response);

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchVote = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/proposals/${route.params.id}/vote`, {
            credentials: 'include',
        })

        votes.value = response;
        if(votes.value.length > 0){
            currentVote.value = votes.value[votes.value.length-1];
            timeRemaining.value = calculateTimeRemaining(new Date(), new Date(currentVote.value['VOT_end_DATE']?.replace(" ", "T")));
            nbRounds.value = currentVote['VOT_nb_rounds_NB'];
            classicSystem.value = (currentVote['VOT_type_NB'] !== 1 && currentVote['VOT_type_NB'] !== 2);

            for(let i = 0; i < votes.value.length; i++){
                let rmt = calculateTimeRemaining(new Date(), new Date(votes.value[i]['VOT_end_DATE']?.replace(" ", "T")));
                if(rmt === 0){
                    fetchResult(i+1);
                }
            }
        }

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const calculateTimeRemaining = (date1, date2) => {

    // Calcul de la différence
    const diffMs = date2 - date1;
    if(diffMs < 0){
        return 0;
    }else{
        return diffMs;
    }
}

const formatTimeRemaning = (time)=>{

    const days = Math.floor(time / (1000 * 60 * 60 * 24));
    const hours = Math.floor((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

    let format = 'Le vote dure encore ';
    if (days > 0) {
        format += `${days} jour${days > 1 ? 's' : ''}`;
    }
    if (hours > 0) {
        format += (days > 0 ? ' et ' : '') + `${hours} heure${hours > 1 ? 's.' : '.'}`;
    }
    return format || "moins d'une heure.";
}

//Non utilisée, pour connaitre le ou les gagnants. 
const getWinner = (results) => {
    let winner = [results[0]];
    for (let i = 1; i < results.length; i++){
        if (winner["POS_nbVotes_NB"] < results[i]["POS_nbVotes_NB"]){
            winner = []
            winner.push(results[i]);
        }else if (winner["POS_nbVotes_NB"] === results[i]["POS_nbVotes_NB"]){
            winner.push(results[i]);
        }
    }
}

onMounted(() => {
    fetchData();
    fetchComments();
    fetchUser();
    fetchReaction();
    fetchFormalRequest();
    fetchReasons();
    fetchVote();
})

</script>