<template>
    <Header></Header>
    <div class="proposals">
        <NuxtLink class="proposals__return" :to="`/communities/${$route.params.id}`">Retour au groupe</NuxtLink>
    </div>
</template>
<script setup>

definePageMeta({
    middleware: ["auth", "decider"]
})

const config = useRuntimeConfig();
const route = useRoute();

const community = ref({});

const fetchData = async () => {
    try{
        const cmy = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        });

        community.value = cmy;
        
    } catch(error) {
        console.log("An error occured", error);
    }

}

onMounted(() => {
    fetchData();
});
</script>