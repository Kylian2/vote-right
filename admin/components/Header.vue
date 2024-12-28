<template>

    <header class="application-header">
        <p class="desktop">VoteRightControlPanel</p>
        <p class="mobile">VRCP</p>
        <div>
            <NuxtLink class="btn btn--small" to="/home" >Accueil</NuxtLink>
            <NuxtLink class="btn btn--small" @click="disconnect()">Déconnexion</NuxtLink>
        </div>
    </header>

</template>
<script setup>
const config = useRuntimeConfig();

const disconnect = async () => {
    try{
        await $fetch(`${config.public.baseUrl}/auth/logout`, {
            method: 'POST',
            credentials: 'include',
        });

        navigateTo('/');
    } catch (error) {
        console.log("An error occured", error);
    }
}

</script>