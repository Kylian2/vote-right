<template>
    <Header></Header>
    <NuxtLink class="back" :to="`/communities/${groupeId}`">Retour au groupe</NuxtLink>
    <main class="moderate-community">
        <div v-if="noReport">
            <h3 class="moderate-community__no-report"> Aucun commentaire n'a été signalé </h3>
        </div>

        <div v-else>
        <h1 class="moderate-community__title">{{ groupeName }}</h1>
            <div class="reports">
                <div class="report" v-for="(report, index) in reports" :key="index">
                    <div v-if="!report.expanded" class="report__summary">
                        <p><strong> {{ report['RPT_label_VC'] }} </strong></p>
                        <p class="report__content"> {{ report['RPT_message_VC'] }} </p>
                        <button class="report__toggle-summary" @click="toggle(index)"> Voir plus </button>
                    </div>

                    <div v-else class="report__details">
                        <div class="report__informations">
                            <p><strong> {{ report['RPT_label_VC'] }} </strong></p>
                            <p><strong> Commenté par {{ report['RPT_firstname_VC'] }} {{ report['RPT_lastname_VC'] }} </strong></p>
                            <p class="report__content"> {{ report['RPT_message_VC'] }} </p>
                        </div>
                        
                        <div class="report__actions">
                            <button class="report__toggle-details" @click="toggle(index)"> Voir moins </button>
                            <button 
                                class="btn btn--small"
                                @click="deleteComment(report['RPT_commentId_NB'])">
                                Supprimer le commentaire
                            </button>

                            <button
                                class="btn btn--small"
                                @click="resolvComment(report['RPT_commentId_NB'])">
                                Clore le signalement
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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

    const groupeName = ref();
    const groupeId = ref();
    const reports = ref();

    const noReport = ref();
    const wantToDelete = ref();

    const toggle = (index) => {
        reports.value[index].expanded = !reports.value[index].expanded;
    };

    const deleteComment = async (reportId) => {
        wantToDelete.value = true;

        const response = await $fetch(`${config.public.baseUrl}/reports/${reportId}/${groupeId.value}`, {
        method: 'PATCH', 
        body: {
                delete: wantToDelete.value,
            },
        credentials: 'include',
        });
    }

    const resolvComment = async (reportId) => {
        wantToDelete.value = false;

        const response = await $fetch(`${config.public.baseUrl}/reports/${reportId}/${groupeId.value}`, {
        method: 'PATCH', 
        body: {
                delete: wantToDelete.value,
            },
        credentials: 'include',
        });
    }

    const fetchData = async () => {
        try {
            const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/reports`, {
                credentials: 'include',
            });

            reports.value = response;
        
        } catch (error) {
            console.error("An error occurred : ", error);
        }
    }
    
</script>