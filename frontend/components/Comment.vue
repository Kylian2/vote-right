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
                <div class="comment__actions flip__back" v-if="!hideName && reactions && !reactions['hasReacted']">
                    <div>
                        <button @click.stop @click="react(LOVE)">
                        <img src="/images/icons/love.png" alt="love icons">
                        </button>
                        <button @click.stop @click="react(LIKE)">
                            <img src="/images/icons/like.png" alt="like icons">
                        </button>
                        <button @click.stop @click="react(DISLIKE)">
                            <img src="/images/icons/redlike.png" alt="dislike icons">
                        </button>
                        <button @click.stop @click="react(HATE)">
                            <img src="/images/icons/hate.png" alt="hate icons">
                        </button>
                    </div>
                    <button @click.stop class="comment__report-btn">Signaler</button>
                </div>
                <div class="comment__actions flip__back" v-if="!hideName && reactions && reactions['hasReacted']">
                    <div>
                        <p class="comment__actions__reaction">
                        <img src="/images/icons/love.png" alt="love icons">
                        {{ reactions["nblove"] }}
                        </p>
                        <p class="comment__actions__reaction">
                            <img src="/images/icons/like.png" alt="like icons">
                            {{ reactions["nblike"] }}
                        </p>
                        <p class="comment__actions__reaction">
                            <img src="/images/icons/redlike.png" alt="dislike icons">
                            {{ reactions["nbdislike"] }}
                        </p>
                        <p class="comment__actions__reaction">
                            <img src="/images/icons/hate.png" alt="hate icons">
                            {{ reactions["nbhate"] }}
                        </p>
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
    console.log(reaction);
}

onMounted(() => {
    fetchReaction();
})

</script>