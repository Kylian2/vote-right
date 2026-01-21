<template>
    <Header></Header>

    <NuxtLink class="back" :to="`/communities/${$route.params.id}/members`">Retour à la gestion des membres</NuxtLink>
    <h1 class="members__title">Gestion des justificatifs</h1>

    <main class="supportings">
        <table v-if="supportings && supportings.length > 0">
            <thead>
                <tr>
                    <th>Nom de la pièce</th>
                    <th>Description de la pièce</th>
                    <th class="invisible"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="supporting in supportings" :key="supporting['SUP_id_NB']">
                    <td>{{ supporting['SUP_label_VC'] }}</td>
                    <td>{{ supporting['SUP_description_TXT'] }}</td>
                    <td class="invisible">
                        <button
                            class="delete-button"
                            @click="
                                () => {
                                    handledSupporting = supporting
                                    deleteSupportingModalOpen = true
                                }
                            "
                        >
                            <i class="material-icons">close</i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <p v-else>Vous ne demandez aucun justificatif pour le moment.</p>
        <div class="supportings__forms">
            <form class="supportings__form">
                <h3>Ajouter un justificatif</h3>
                <Input
                    name="supportingName"
                    type="text"
                    label="Nom du justificatif"
                    placeholder="Indiquer le nom du justificatif"
                    :rules="[(v) => Boolean(v) || 'Un nom est requis']"
                    >Nom du justificatif</Input
                >
                <TextArea
                    name="supportingDescription"
                    label="Description du justificatif"
                    placeholder="Indiquer aux membres quel type de justificatif fournir, soyez précis."
                    :rules="[(v) => Boolean(v) || 'Une description est requise']"
                    >Description du justificatif</TextArea
                >
                <button
                    formmethod="dialog"
                    :disabled="!supportingForm"
                    class="btn btn-primary"
                    @click="handleSupporting"
                >
                    Ajouter le justificatif
                </button>
            </form>
        </div>
    </main>

    <Modal
        name="deleteSupporting"
        class="modal"
        okText="Supprimer"
        cancelText="Annuler"
        :before-ok="() => deleteSupporting()"
        :before-close="
            () => {
                handledSupporting = null
            }
        "
    >
        <template #title>Confirmer la suppression</template>
        <template #body>
            <p>
                Êtes-vous sûr de vouloir supprimer le justificatif :
                <strong>{{ handledSupporting['SUP_label_VC'] }}</strong> ?
            </p>
            <p>Cette action est irréversible.</p>
        </template>
    </Modal>

    <Toast name="supportingSuccess" :type="3" :time="10" :loader="true" class="toast">
        Le justificatif a été ajouté
    </Toast>

    <Toast name="supportingError" :type="1" :time="10" :loader="true" class="toast"> Une erreur est survenue </Toast>
</template>
<script setup>
const config = useRuntimeConfig()
const route = useRoute()

definePageMeta({
    middleware: ['auth', 'decider'],
})

const supportingName = useState('supportingName')
const supportingDescription = useState('supportingDescription')
const supportingNameValid = useState('supportingNameValid')
const supportingDescriptionValid = useState('supportingDescriptionValid')

const supportingForm = computed(() => {
    return supportingNameValid.value && supportingDescriptionValid.value
})

const successToastUp = useState('supportingSuccessUp')
const errorToastUp = useState('supportingErrorUp')

const supportings = ref([])
const fetchData = async () => {
    try {
        supportings.value = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/supportings`, {
            credentials: 'include',
        })
    } catch (err) {
        console.error('Impossible to fetch supportings date', err)
    }
}

const handleSupporting = async () => {
    try {
        await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/supportings`, {
            method: 'POST',
            credentials: 'include',
            body: {
                community: route.params.id,
                label: supportingName.value,
                description: supportingDescription.value,
            },
        })
        fetchData()
        successToastUp.value = true
        supportingName.value = ''
        supportingDescription.value = ''
    } catch (error) {
        console.log('An error occured', error)
        errorToastUp.value = true
    }
}

const handledSupporting = ref(null)
const deleteSupportingModalOpen = useState('deleteSupportingModal', () => false)

const deleteSupporting = async () => {
    try {
        await $fetch(`${config.public.baseUrl}/supportings/${handledSupporting.value['SUP_id_NB']}`, {
            method: 'DELETE',
            credentials: 'include',
        })
        handleSupporting.value = null
        fetchData()
    } catch (error) {
        console.log('An error occured', error)
        errorToastUp.value = true
    }
}

onMounted(() => {
    fetchData()
})

onBeforeUnmount(() => {
    const from = useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    })
    from.value = {
        name: route.name,
        href: route.href,
    }
})
</script>
