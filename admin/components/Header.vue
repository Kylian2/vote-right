<template>

    <header class="application-header">
        <p class="desktop">VoteRightControlPanel</p>
        <p class="mobile">VRCP</p>
        <div>
            <NuxtLink class="btn btn--small" to="/home" >Accueil</NuxtLink>
            <NuxtLink class="btn btn--small" to="/" @click="disconnect()">Déconnexion</NuxtLink>
        </div>
    </header>

</template>
<script setup>

const disconnect = () => {
    document.cookie = 'PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
}

</script>