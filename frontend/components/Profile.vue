<template>
    <p class="profile-initials">{{ getInitials(user['USR_firstname_VC'], user['USR_lastname_VC']) }}</p>
</template>


<script setup>

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
})

function getInitials(string1, string2) {
    const initial1 = string1.trim().charAt(0).toUpperCase();
    const initial2 = string2.trim().charAt(0).toUpperCase();
    return initial1 + initial2;
}

</script>