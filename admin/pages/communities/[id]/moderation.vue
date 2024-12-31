<template>
    <Header></Header>
    <NuxtLink class="back" :to="`/communities/${numberGroup}`">Retour au groupe</NuxtLink>
    <main class="moderate-community">
        <div v-if="noReport">
            <h3 style="text-align: center; margin-top: 100px; margin-bottom: 100px;"> Aucun commentaire n'a été signalé </h3>
        </div>

        <div v-else>
            <h1> {{ nameGroup }} </h1>
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
    const reports = ref();

    const noReport = ref();

    const deleteComment = async () => {
        const response = await $fetch(`${config.public.baseUrl}/reports/resolv`, {
        method: 'POST',
        credentials: 'include',
        });
    }

    const resolvComment = async () => {
        const response = await $fetch(`${config.public.baseUrl}/reports/delete`, {
        method: 'POST',
        credentials: 'include',
        });
    }

    const fetchData = async () => {
        try {
            const response1 = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
                credentials: 'include',
            });

            nameGroup.value = response1.CMY_name_VC;
            numberGroup.value = response1.CMY_id_NB;

            const response2 = await $fetch(`${config.public.baseUrl}/reports/${numberGroup.value}`, {
                credentials: 'include',
            });

            if(!response2['RPT_commentId_NB']){
                noReport.value = true;
                return ;
            }
            
            reports.value = response2;
        
        } catch (error) {
            console.error("An error occurred : ", error);
        }
    }
    
</script>