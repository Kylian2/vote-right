<template>
    <Header type="logged" actif="accueil"></Header>
    <div class="communities__title">
        <h1>Groupes</h1>
        <NuxtLink class="btn--full" to="#">
            Créer mon groupe
        </NuxtLink>
    </div>
    <main class="communities">
        <div class="communities__bloc">
            <h4>Mes groupes :</h4>
            <div class="communities__bloc__contener">
                <div class="add-community" :class="{ 'small': myCommunities.length > 0 }">
                    <span class="add-community__plus"></span>
                    <span class="add-community__plus"></span>
                </div>
            </div>
        </div>

        <div class="communities__bloc">
            <h4>Auxquels je participe :</h4>
            <div class="communities__bloc__contener">
                <CardCommunity v-for="community in communities" :community="community"></CardCommunity>
                <p v-if="communities.length == 0"> Vous ne participez à aucun groupes </p>
            </div>
        </div>
    </main>
</template>
<script setup>

definePageMeta({
    middleware: ["auth"]
})

const myCommunities = useState("myCommunities", () => []);
const communities = useState("communities", () => []);

onMounted(() => {
    fetchData();
})

const fetchData = async () => {
    try{
        const response = await $fetch(`http://localhost:3333/community/index`, {
            credentials: 'include',
        });

        communities.value = response
    } catch (error) {
        console.log("An error occured", error);
    }

}

</script>