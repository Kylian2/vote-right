<template>
    <Header type="logged" actif="accueil"></Header>
    <h1>Bienvenue</h1>
</template>
<script setup lang="ts">
definePageMeta({
    middleware: ["auth"]
})
const response = await $fetch(`http://localhost:3333/auth/check`, { credentials : 'include'});
</script>