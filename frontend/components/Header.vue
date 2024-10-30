<template>
    <header>
        <NuxtLink to="/" class="logo">
            <p>VoteRight</p>
        </NuxtLink>
        <nav>
            <div class="btn--border--header__wrapper">
                <NuxtLink class="btn btn--border--header" to="/register"><p>S'inscrire</p></NuxtLink>
            </div>
            <div class="btn--full--header__wrapper">
                <NuxtLink class="btn btn--full--header" to="/login"><p>Se connecter</p></NuxtLink>
            </div>
        </nav>
    </header>
</template>