<template>
    <div>
        <h3>Tous les fichiers</h3>
        <div class="file-management__actions">
            <div class="file-management__action" @click="addDocumentModal = true">
                <i class="material-icons">description</i>
                <p>Ajouter un document</p>
            </div>
        </div>
        <div class="file-management__table__container">
            <table class="file-management__table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Date de l'ajout</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file in files">
                        <td>{{ file['FIL_name_VC'] }}</td>
                        <td>{{ file['FIL_type_NB'] }}</td>
                        <td>{{ file['created_at'] }}</td>
                        <td><i class="material-icons">more_vert</i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <Modal
        :name="`addNewDocumentModalSetting`"
        ok-text="Ajouter"
        cancel-text="Annuler"
        :disable-valid="!formIsValid"
        :before-ok="addFile"
    >
        <template #title>Ajouter un document</template>
        <template #body>
            <form class="file-management__add-modal">
                <Input
                    placeholder="Entrez le nom du document"
                    name="fileName"
                    type="text"
                    :rules="[(v) => v.length < 100 || 'Le nom doit comporter 100 caractères maximum']"
                    >Nom du document</Input
                >
                <div>
                    <p class="legende">
                        Sélectionnez un document à ajouter. La taille de celui-ci ne doit pas excéder 1 Mo.
                    </p>

                    <input
                        class="file-management__add-modal__file-input"
                        type="file"
                        @change="handleFileChange"
                        accept="image/png, image/jpeg, .docx, .pdf"
                    />
                </div>
            </form>
        </template>
    </Modal>

    <Toast name="fileManagementErrorToast" :type="1" :time="10" :loader="true">{{ errorToastText }}</Toast>
    <Toast name="fileManagementSuccessToast" :type="3" :time="5" :loader="true">{{ successToastText }}</Toast>
</template>
<script setup>
const config = useRuntimeConfig()

const files = ref()

const addDocumentModal = useState('addNewDocumentModalSettingModal', () => false)
const image = useState('image', () => null)
const name = useState('fileName')

const successToast = useState('fileManagementSuccessToastUp', () => false)
const successToastText = ref()
const errorToast = useState('fileManagementErrorToastUp', () => false)
const errorToastText = ref()

const handleFileChange = (event) => {
    image.value = event.target.files[0]
}

const formIsValid = computed(() => {
    return name.value && image.value !== null
})

const fetchFiles = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/users/me/files`, {
            credentials: 'include',
        })
        files.value = response
    } catch (error) {
        console.log('An error occured', error)
    }
}

const addFile = async () => {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('image', image.value)

    try {
        const response = await $fetch(`${config.public.baseUrl}/users/file`, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        })

        if (response) {
            name.value = ''
            image.value = null
            successToastText.value = 'Votre fichier a été ajouté !'
            successToast.value = true
        }
        files.value.push(response)
    } catch (error) {
        errorToastText.value = "Une erreur est survenue, votre fichier n'a pas été ajouté"
        errorToast.value = true
        console.error('An unexpected error occurred:', error)
    }

    return true
}

onMounted(() => {
    fetchFiles()
})
</script>
