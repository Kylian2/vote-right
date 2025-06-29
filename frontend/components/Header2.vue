<template>
    <header class="landing-header" :class="{ 'landing-header--active': hamburgerMenuActive }">
        <a href="/" class="landing-header__logo">VoteRight</a>
        
        <nav class="landing-header__nav">
                <a href="#fonctionnalites" class="landing-header__nav-link">Fonctionnalités</a>
                <a href="#communautes" class="landing-header__nav-link">Communautés</a>
                <a href="#contact" class="landing-header__nav-link">Contact</a>
        </nav>
        
        <div class="landing-header__actions">
            <a href="/login" class="landing-btn landing-btn--secondary">Connexion</a>
            <a href="/register" class="landing-btn landing-btn--primary">Commencer gratuitement</a>
        </div>

        <div class="landing-header__hamburger-menu" @click="hamburgerMenuActive = !hamburgerMenuActive">
            <div class="landing-header__hamburger-menu__line"></div>
            <div class="landing-header__hamburger-menu__line"></div>
            <div class="landing-header__hamburger-menu__line"></div>
        </div>

    </header>
</template>
<script setup>

const hamburgerMenuActive = ref(false);

</script>