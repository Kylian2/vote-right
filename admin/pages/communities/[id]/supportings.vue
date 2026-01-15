<template>
    <Header></Header>

    <NuxtLink class="back" :to="`/communities/${$route.params.id}/members`">Retour à la gestion des membres</NuxtLink>
    <h1 class="members__title">Gestion des justificatifs</h1>

    <main class="supportings">
        <table>
            <thead>
                <tr>
                    <th>Nom de la pièce</th>
                    <th>Description de la pièce</th>
                    <th class="invisible"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Justificatif de domicile</td>
                    <td>Permet de vérifier l'adresse de résidence du membre.</td>
                    <td class="invisible">
                        <button class="delete-button">
                            <i class="material-icons" id="leaderboard-icon">close</i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>Pièce d'identité</td>
                    <td>Permet de confirmer l'identité du membre.</td>
                    <td class="invisible">
                        <button class="delete-button">
                            <i class="material-icons" id="leaderboard-icon">close</i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>Justificatif de revenu</td>
                    <td>Utilisé pour évaluer la situation financière du membre.</td>
                    <td class="invisible">
                        <button class="delete-button">
                            <i class="material-icons" id="leaderboard-icon">close</i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="supportings__forms">
            <form class="supportings__form">
                <h3>Ajouter un justificatif</h3>
                <Input
                    name="supportingName"
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
                <button formmethod="dialog" :disabled="!supportingForm" class="btn btn-primary">
                    Ajouter le justificatif
                </button>
            </form>
        </div>
    </main>
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
