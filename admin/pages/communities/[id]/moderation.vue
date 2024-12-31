<template>
    <Header></Header>
    <NuxtLink class="back" :to="`/communities/${numberGroup}`">Retour au groupe</NuxtLink>
    <h1> {{ nameGroup }} </h1>
    <main class="moderate-community">
        <div v-if="invitationUnavailable">
            <h3 style="text-align: center; margin-top: 100px; margin-bottom: 100px;"> Cette invitation est inaccessible </h3>
        </div>

        <div v-else>
            <h3 style="text-align: center; margin-top: 100px; margin-bottom: 100px;"> Cette invitation est inaccessible </h3>
        </div>
    </main>
</template>

<script setup>

    const config = useRuntimeConfig();
    const route = useRoute();
    
    definePageMeta({
        middleware: ["auth", "managed"]
    })
    
    onMounted(() => {
        fetchData();
    })

    const nameGroup = ref();
    const numberGroup = ref();

    const fetchData = async () => {
        try {
            const response1 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
                credentials: 'include',
            });

            nameGroup.value = response1.CMY_name_VC;
            numberGroup.value = response1.CMY_id_NB;

            const response2 = 
        
        } catch (error) {
            console.error("An error occurred : ", error);
        }
    }
    
</script>