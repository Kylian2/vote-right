<template>
    <div class="comment" :class="{ 'self-end': right }">
        <p class="comment__sender" v-if="!hideName">
            {{ comment["COM_sender_fname_VC"] }} <b>{{ comment["COM_sender_lname_VC"] }}</b>
        </p>
            <p @click="() => {toggle = (!toggle && !hideName)}" class="comment__message" :class="{ 'd-none':toggle}">
                {{ comment["COM_message_VC"] }}
            </p>
            <div @click="toggle = !toggle" class="comment__actions" v-if="!hideName && reactions" :class="{ 'd-none':!toggle}">
                <div>
                    <button @click.stop @click="react(LOVE)" :disabled="reactions['hasReacted']">
                        <img src="/images/icons/love.png" alt="love icons">
                        <p v-if="reactions['hasReacted']">{{ reactions["nblove"] }}</p>
                    </button>
                    <button @click.stop @click="react(LIKE)" :disabled="reactions['hasReacted']">
                        <img src="/images/icons/like.png" alt="like icons">
                        <p v-if="reactions['hasReacted']">{{ reactions["nblike"] }}</p>
                    </button>
                    <button @click.stop @click="react(DISLIKE)" :disabled="reactions['hasReacted']">
                        <img src="/images/icons/redlike.png" alt="dislike icons">
                        <p v-if="reactions['hasReacted']">{{ reactions["nbdislike"] }}</p>
                    </button>
                    <button @click.stop @click="react(HATE)" :disabled="reactions['hasReacted']">
                        <img src="/images/icons/hate.png" alt="hate icons">
                        <p v-if="reactions['hasReacted']">{{ reactions["nbhate"] }}</p>
                    </button>
                </div>
                <button @click="reportModal = !reportModal" class="comment__report-btn">Signaler</button>
            </div>
    </div>
    <Modal
        name="report"
        ok-text="Signaler"
        cancel-text="Annuler"
        :disable-valid="!reportReasonValid"
        :before-ok="() => {report()}"
        :before-cancel="() => {reportReason = ''}"
        >
            <template #title>Signalez le commentaire</template>
            <template #body>  
                <Select v-if="reasons"
                name="reportReason" 
                :options="reasons" 
                placeholder="Selectionnez un motif"
                :rules="[
                    (v) => Boolean(v) || 'Veuillez selection un motif'
                ]"
                >Motif : </Select>
            </template>        
    </Modal>
</template>
<script setup>

const config = useRuntimeConfig();

const LOVE = 3;
const LIKE = 1;
const DISLIKE = 2;
const HATE = 4;

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
    hideName: {
        type: Boolean,
        required: false,
        default: false,
    }, 
    reasons: {
        type: Array,
        required: true,
    },
    right: {
        type: Boolean,
        required: false,
        default: false,
    }
})

const toggle = ref(false);
const reactions = ref();

const reportModal = useState('reportModal', () => false);
const reportReason = useState('reportReason');
const reportReasonValid = useState('reportReasonValid');

const fetchReaction = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/comments/${props.comment['COM_id_NB']}/reactions`, {
            credentials: 'include',
        })

        reactions.value = response;

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const react = async (reaction) => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/comments/${props.comment['COM_id_NB']}/reactions`, {
            method: 'POST',
            credentials: 'include',
            body: {
                reaction: reaction
            }
        })

        if (response) {
            reactions.value['hasReacted'] = true;

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

const report = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/comments/${props.comment['COM_id_NB']}/report`, {
            method: 'POST',
            credentials: 'include',
            body:{
                reason: reportReason.value
            }
        })

        console.log(response);

        }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

onMounted(() => {
    fetchReaction();
})

</script>