<template>
    <div class="comment">
        <p class="message__sender" v-if="!hideName">
            {{ comment["COM_sender_fname_VC"] }} <b>{{ comment["COM_sender_lname_VC"] }}</b>
        </p>
        <p class="comment__message">
            {{ comment["COM_message_VC"] }}
        </p>
    </div>
</template>
<script setup>

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

</script>