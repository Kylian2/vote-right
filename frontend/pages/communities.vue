<template>
    <Header type="logged" actif="accueil"></Header>
    <div>
        <h1>Groupes</h1>
    </div>
    <main class="communities">

    </main>
</template>
<script setup>

definePageMeta({
    middleware: ["auth"]
})

</script>