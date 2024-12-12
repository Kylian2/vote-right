<template>
    <div class="comment">
        <p class="comment__sender" v-if="!hideName">
            {{ comment["COM_sender_fname_VC"] }} <b>{{ comment["COM_sender_lname_VC"] }}</b>
        </p>
        <div class="flip" :class="{ 'flip-state':toggle}">
            <div :class="{ 'flip__inner': !hideName}" @click="toggle = !toggle">
                <p class="comment__message" :class="{ 'flip__front': !hideName}">
                    {{ comment["COM_message_VC"] }}
                </p>
                <div class="comment__actions flip__back" v-if="!hideName && reactions">
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
                    <button @click.stop class="comment__report-btn">Signaler</button>
                </div>
            </div>
        </div>
        
    </div>
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
    }
})

const toggle = ref(false);
const reactions = ref();

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
        const response = await $fetch(`${config.public.baseUrl}/comments/${props.comment['COM_id_NB']}/react`, {
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

onMounted(() => {
    fetchReaction();
})

</script>