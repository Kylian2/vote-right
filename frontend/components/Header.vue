<template>
    <header>
        <NuxtLink to="/" class="logo">
            <p>VoteRight</p>
        </NuxtLink>
        <nav v-if="type === 'notlogged'">
            <div class="btn--border--header__wrapper">
                <NuxtLink class="btn btn--border--header" to="/register"><p>S'inscrire</p></NuxtLink>
            </div>
            <div class="btn--full--header__wrapper">
                <NuxtLink class="btn btn--full--header" to="/login"><p>Se connecter</p></NuxtLink>
            </div>
        </nav>
        <nav v-if="type === 'logged'" class="logged">
            <div class="wrapper" :class="{'active' : actif === 'accueil'}" ><NuxtLink class="logged__link" to="#">Accueil</NuxtLink></div>
            <div class="wrapper" :class="{'active' : actif === 'groupes'}"><NuxtLink class="logged__link" to="#">Groupes</NuxtLink></div>
        </nav>
        <div v-if="type === 'logged'" class="logged"">
            <NuxtLink to="#" class="shine">Mon Compte</NuxtLink>
        </div>
    </header>
</template>
<script setup>
const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    actif: {
        type: String,
        required: false,
    }
})
</script>