<template>
    <div class="comment">
        <p>
            {{ comment["COM_sender_fname_VC"] }} <b>{{ comment["COM_sender_lname_VC"] }}</b>
        </p>
        <p>
            {{ comment["COM_message_VC"] }}
        </p>
    </div>
</template>
<script setup>

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    }
})

</script>