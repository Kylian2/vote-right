<template>
    <Header type="logged" actif="groupes"></Header>
    <div class="communities__title">
        <h1>Groupes</h1>
        <NuxtLink class="btn--full btn--responsive-hidden" to="/community/new">
            Créer mon groupe
        </NuxtLink>
        <NuxtLink class="btn--full btn--plus btn--responsive" to="#">
            <span class="btn--plus__plus"></span>
            <span class="btn--plus__plus"></span>
        </NuxtLink>
    </div>
    <main class="communities">
        <div class="communities__bloc">
            <h4>Mes groupes :</h4>
            <div class="communities__bloc__contener">
                <CardCommunity v-for="community in myCommunities" :community="community"></CardCommunity>
                <NuxtLink to="/community/new" class="add-community" :class="{ 'small': myCommunities.length > 0 }">
                    <span class="add-community__plus"></span>
                    <span class="add-community__plus"></span>
                </NuxtLink>
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

    try{
        const response = await $fetch(`http://localhost:3333/community/administered`, {
            credentials: 'include',
        });

        myCommunities.value = response
    } catch (error) {
        console.log("An error occured", error);
    }

}

</script>