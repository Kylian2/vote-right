<template>
    <Header></Header>
    <NuxtLink class="back" :to="`/communities/${$route.params.id}`">Retour au groupe</NuxtLink>

    <main class="moderate-community">
        <div v-if="noReport">
            <h3 class="moderate-community__no-report"> Aucun commentaire n'a été signalé </h3>
        </div>
        <div v-else>
        <h1 class="moderate-community__title">{{ groupeName }}</h1>
            <div class="reports">
                <div class="report" v-for="(report, index) in reports" :key="index">
                    <div class="report__summary" @click="toggle(index)">
                        <div>
                            <p><strong> {{ report['RPT_label_VC'] }} {{ report['RPT_labels_TAB'].length > 1 ? " et " + (report['RPT_labels_TAB'].length-1) + " autres" : ""}} </strong></p>
                            <p v-if="!report.expanded" class="report__content"> {{ report['RPT_message_VC'] }} </p>
                        </div>
                        <button class="report__toggle-summary"> {{ report.expanded ? 'Voir moins' : 'Voir plus'}} </button>
                    </div>

                    <div v-if="report.expanded" class="report__details">
                        <div class="report__informations">
                            <p><strong> Commenté par {{ report['RPT_firstname_VC'] }} {{ report['RPT_lastname_VC'] }} </strong></p>
                            <p class="report__content"> {{ report['RPT_message_VC'] }} </p>
                        </div>
                        
                        <div class="report__actions">
                            <button 
                                class="btn btn--small"
                                @click="handleReport(report, true)">
                                Supprimer le commentaire
                            </button>

                            <button
                                class="btn btn--small"
                                @click="handleReport(report, false)">
                                Clore le signalement
                            </button>
                        </div>
                    </div>
                </div>
                <p class="legende" v-if="reports.length === 0"> Tout va bien, il n'y a aucun signalement !</p>
            </div>
        </div>
    </main>
</template>

<script setup>

    const config = useRuntimeConfig();
    const route = useRoute();
    
    definePageMeta({
        middleware: ["auth", "moderator"]
    })
    
    onMounted(() => {
        fetchData();
    })

    const groupeName = ref();
    const groupeId = ref();
    const reports = ref([]);

    const noReport = ref();

    const toggle = (index) => {
        reports.value[index].expanded = !reports.value[index].expanded;
    };

    const handleReport = async (report, del) => {

        try{
            await $fetch(`${config.public.baseUrl}/reports/${report['RPT_user_NB']}/${report['RPT_comment_NB']}`, {
                method: 'PATCH', 
                body: {
                        delete: del,
                    },
                credentials: 'include',
            });

            fetchData();
        }catch(e){
            console.error("An error occurred : ", error);
        }
    }

    const fetchData = async () => {
        try {
            const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/reports`, {
                credentials: 'include',
            });
            reports.value = [];
            response.forEach(report => {
                const existing = reports.value.find(item => item['RPT_comment_NB'] === report['RPT_comment_NB']);

                if (existing) {
                    if (!existing['RPT_labels_TAB'].includes(report['RPT_label_VC'])) {
                        existing['RPT_labels_TAB'].push(report['RPT_label_VC']);
                        }
                } else {
                    reports.value.push({
                    ...report,
                    RPT_labels_TAB: [report['RPT_label_VC']] 
                    });
                }
            });
        
        } catch (error) {
            console.error("An error occurred : ", error);
        }
    }
    
    onBeforeUnmount(() => {
        useState('from', () => {
            return {
                name: route.name,
                href: route.href,
            }
        })
    })
</script>