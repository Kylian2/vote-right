<template>
    <div
        @click="
            () => {
                if (actionContainer) {
                    actionContainer.classList.add('d-none')
                }
                actionContainer.value = null
            }
        "
    >
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
                        <td>
                            <i
                                class="material-icons"
                                @click="
                                    (event) => {
                                        if (actionContainer) {
                                            actionContainer.classList.add('d-none')
                                        }
                                        event.target.nextElementSibling.classList.toggle('d-none')
                                        actionContainer = event.target.nextElementSibling
                                        event.stopPropagation()
                                    }
                                "
                                >more_vert</i
                            >
                            <ul @click="$event.stopPropagation()" class="file-management__table__actions d-none">
                                <li
                                    @click="
                                        () => {
                                            fileHandled = file['FIL_id_NB']
                                            renameDocumentModal = true
                                        }
                                    "
                                >
                                    Renommer
                                </li>
                                <li
                                    @click="
                                        () => {
                                            fileHandled = file['FIL_id_NB']
                                            deleteFile()
                                        }
                                    "
                                    class="careful"
                                >
                                    Supprimer
                                </li>
                            </ul>
                        </td>
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

    <Modal
        :name="`renameDocumentModalSetting`"
        ok-text="Renommer"
        cancel-text="Annuler"
        :disable-valid="!renameFileName"
        :before-ok="renameFile"
    >
        <template #title>Renommer le document</template>
        <template #body>
            <Input
                placeholder="Entrez le nom du document"
                name="renameFileName"
                type="text"
                :rules="[(v) => v.length < 100 || 'Le nom doit comporter 100 caractères maximum']"
                >Nom du document</Input
            >
        </template>
    </Modal>

    <Toast name="fileManagementErrorToast" :type="1" :time="10" :loader="true">{{ errorToastText }}</Toast>
    <Toast name="fileManagementSuccessToast" :type="3" :time="5" :loader="true">{{ successToastText }}</Toast>
</template>
<script setup>
const config = useRuntimeConfig()

const files = ref()

const addDocumentModal = useState('addNewDocumentModalSettingModal', () => false)
const renameDocumentModal = useState('renameDocumentModalSettingModal', () => false)
const image = useState('image', () => null)
const name = useState('fileName')

const successToast = useState('fileManagementSuccessToastUp', () => false)
const successToastText = ref()
const errorToast = useState('fileManagementErrorToastUp', () => false)
const errorToastText = ref()

const actionContainer = ref(null)
const fileHandled = ref(null)
const renameFileName = useState('renameFileName')

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
            fetchFiles()
        }
    } catch (error) {
        errorToastText.value = "Une erreur est survenue, votre fichier n'a pas été ajouté"
        errorToast.value = true
        console.error('An unexpected error occurred:', error)
    }

    return true
}

const renameFile = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/file/${fileHandled.value}`, {
            method: 'PATCH',
            body: {
                name: renameFileName.value,
            },
            credentials: 'include',
        })
        if (response) {
            renameFileName.value = ''
            successToastText.value = 'Le fichier a été renommé !'
            successToast.value = true
            fetchFiles()
        }
    } catch (error) {
        console.error('An error occured', error)
        errorToastText.value = "Une erreur est survenue, le fichier n'a pas été renommé"
        errorToast.value = true
        return false
    }

    fileHandled.value = null
    return true
}

const deleteFile = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/file/${fileHandled.value}`, {
            method: 'DELETE',
            credentials: 'include',
        })
        if (response) {
            renameFileName.value = ''
            successToastText.value = 'Le fichier a été supprimé'
            successToast.value = true
            fetchFiles()
        }
    } catch (error) {
        console.error('An error occured', error)
        errorToastText.value = "Une erreur est survenue, le fichier n'a pas été supprimé"
        errorToast.value = true
        return false
    }
    fileHandled.value = null
    return true
}

onMounted(() => {
    fetchFiles()
})
</script>
