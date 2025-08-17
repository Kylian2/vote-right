<template>
    <Header type="logged" actif="account"></Header>
    <h1>Mes paramètres</h1>

    <main class="account-settings">
        <ul class="account-settings__navigation">
            <li :class="{ active: page === 1 }" @click="page = 1">
                <i class="material-icons">person</i>
                Profil
            </li>
            <li :class="{ active: page === 2 }" @click="page = 2">
                <i class="material-icons">notifications</i>
                Notifications
            </li>
            <li :class="{ active: page === 3 }" @click="page = 3">
                <i class="material-icons">security</i>
                Sécurité
            </li>
        </ul>

        <div class="account-settings__page" v-if="page === 1 && user">
            <div class="account-settings__page__header">
                <h3>Informations du profil</h3>
                <button v-if="!updatingUserInfos" class="btn btn--full" @click="updatingUserInfos = true">
                    <i class="material-icons">edit</i>
                    <span>Modifier</span>
                </button>
            </div>
            <div>
                <div class="account-settings__input-container">
                    <label for="lastname">Nom</label>
                    <input
                        type="text"
                        name="lastname"
                        id="lastname"
                        v-model="user['USR_lastname_VC']"
                        disabled
                        :title="updatingUserInfos ? 'Impossible de modifier votre nom de famille' : ''"
                    />
                </div>
                <div class="account-settings__input-container">
                    <label for="firstname">Prénom</label>
                    <input
                        type="text"
                        name="firstname"
                        id="firstname"
                        v-model="user['USR_firstname_VC']"
                        disabled
                        :title="updatingUserInfos ? 'Impossible de modifier votre prénom' : ''"
                    />
                </div>
                <div class="account-settings__input-container">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        v-model="user['USR_email_VC']"
                        :disabled="!updatingUserInfos"
                    />
                </div>
            </div>
            <div class="account-settings__page__buttons" v-if="updatingUserInfos">
                <button class="btn btn--full" :disabled="!userInfosValid" @click="updatePersonnalInformations">
                    Enregistrer
                </button>
                <button class="btn btn--cancel" @click="updatingUserInfos = false">Annuler</button>
            </div>
        </div>
        <div class="account-settings__page" v-if="page === 2">
            <h3>Notifications</h3>
            <div class="account-settings__page__notifications">
                <div class="container-checkbox">
                    <div class="form-check">
                        <input
                            @change="updateNotificationPreferences"
                            type="checkbox"
                            id="newProposal"
                            v-model="user['USR_notify_proposal_BOOL']"
                            :true-value="1"
                            :false-value="0"
                        />
                        <label for="newProposal"> Nouvelle proposition </label>
                    </div>
                </div>

                <div class="container-checkbox">
                    <div class="form-check">
                        <input
                            @change="updateNotificationPreferences"
                            type="checkbox"
                            id="startOfVoting"
                            v-model="user['USR_notify_vote_BOOL']"
                            :true-value="1"
                            :false-value="0"
                        />
                        <label for="startOfVoting"> Début de vote </label>
                    </div>
                </div>

                <div class="container-checkbox">
                    <div class="form-check">
                        <input
                            @change="updateNotificationPreferences"
                            type="checkbox"
                            id="reactionToTheProposals"
                            v-model="user['USR_notify_reaction_BOOL']"
                            :true-value="1"
                            :false-value="0"
                        />
                        <label for="reactionToTheProposals"> Réactions aux propositions </label>
                    </div>
                </div>

                <div class="container-checkbox">
                    <div class="form-check">
                        <input
                            @change="
                                (event) => {
                                    user['USR_notification_frequency_CH'] = event.target.checked ? 'Q' : 'H'
                                    updateNotificationPreferences()
                                }
                            "
                            type="checkbox"
                            id="notificationFrequency"
                            v-model="notificationCheckbox"
                        />
                        <label for="notificationFrequency">
                            Fréquence des notifications (Hebdomadaire / Quotidienne)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="account-settings__page" v-if="page === 3">
            <h3>Sécurité du compte</h3>
            <div class="account-settings__security">
                <div>
                    <div v-if="!updatingSecurity" class="account-settings__security__password-container">
                        <div class="account-settings__input-container">
                            <label for="password">Mot de passe</label>
                            <div>
                                <input type="password" name="password" value="XXXXXXXXXXX" disabled />
                                <p v-if="!updatingSecurity" @click="updatingSecurity = true">Modifier</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="updatingSecurity" class="account-settings__input-container">
                        <label for="new-password">Entrez votre nouveau mot de passe</label>
                        <p v-if="updatingSecurity && passwordError !== ''" class="error font--small">
                            {{ passwordError }}
                        </p>
                        <input type="password" name="new-password" id="new-password" v-model="firstPassword" />
                    </div>
                    <div v-if="updatingSecurity" class="account-settings__input-container">
                        <label for="new-password-again">Entrez à nouveau votre nouveau mot de passe</label>
                        <input
                            type="password"
                            name="new-password-again"
                            id="new-password-again"
                            v-model="secondPassword"
                        />
                    </div>
                    <div v-if="updatingSecurity" class="account-settings__page__buttons">
                        <button
                            class="btn btn--full"
                            :disabled="secondPassword === '' || firstPassword !== secondPassword"
                            @click="updatePassword"
                        >
                            Modifier le mot de passe
                        </button>
                        <button class="btn btn--cancel" @click="updatingSecurity = false">Annuler</button>
                    </div>
                </div>
                <p class="error" @click="disconnect">Se déconnecter</p>
                <p class="error" @click="deleteUser = true">Supprimer le compte</p>
            </div>
        </div>
    </main>
    <Modal
        name="deleteUser"
        ok-text="Supprimer"
        cancel-text="Annuler"
        :disable-valid="!deleteUserValid"
        :before-ok="
            () => {
                handleDelete()
                deleteUserContent = ''
            }
        "
        :before-cancel="
            () => {
                deleteUserContent = ''
            }
        "
    >
        <template #title>Supprimer le compte</template>
        <template #body>
            <p>Pour confirmer veuillez écrire votre email :</p>
            <Input
                type="text"
                :placeholder="user['USR_email_VC']"
                class="inline-input"
                name="deleteUser"
                :rules="[(v) => v === user['USR_email_VC'] || '']"
                >Réécrivez :
            </Input>
        </template>
    </Modal>

    <Toast name="settingsUpdateErrorToast" :type="1" :time="10" :loader="true">{{ errorToastText }}</Toast>
    <Toast name="settingsUpdateSuccessToast" :type="3" :time="5" :loader="true">{{ successToastText }}</Toast>
    <Toast name="cantDelete" :type="1" :time="5" :loader="true" class="toast">
        Impossible de supprimer votre compte tant que vous êtes admin
    </Toast>
</template>
<script setup>
const config = useRuntimeConfig()

definePageMeta({
    middleware: ['auth'],
})

const page = ref(1)
const user = ref()

const errorToastText = ref()
const errorToast = useState('settingsUpdateErrorToastUp', () => false)
const successToastText = ref()
const successToast = useState('settingsUpdateSuccessToastUp', () => false)

const updatingUserInfos = ref(false)
const userInfosValid = computed(() => {
    return (
        user.value['USR_firstname_VC'] != '' &&
        user.value['USR_lastname_VC'] != '' &&
        checkEmail(user.value['USR_email_VC'])
    )
})

const updatingSecurity = ref(false)
const deleteUser = useState('deleteUserModal', () => false)
const deleteUserValid = useState('deleteUserValid')
const deleteUserContent = useState('deleteUser')

const notificationCheckbox = computed(() => {
    return user.value['USR_notification_frequency_CH'] === 'Q'
})

const firstPassword = ref('')
const secondPassword = ref('')
const passwordError = computed(() => {
    return firstPassword.value.length < 8 ? 'Le mot de passe doit comporter 8 caractères' : ''
})

const fetchUser = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include',
        })

        user.value = response
        updatingUserInfos.value = false
        updatingSecurity.value = false
    } catch (error) {
        console.log('An error occured', error)
    }
}

const checkEmail = (email) => {
    // Expression régulière pour vérifier une adresse e-mail
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

    // Teste si l'e-mail correspond à l'expression régulière
    return regex.test(email)
}

const updatePersonnalInformations = async () => {
    if (!userInfosValid) return

    try {
        await $fetch(`${config.public.baseUrl}/users/me/information`, {
            method: 'PATCH',
            credentials: 'include',
            body: {
                email: email.value,
            },
        })
        successToastText.value = 'Vos informations ont été mises à jour'
        successToast.value = true

        // Termine la modification
        updatingUserInfos.value = false
        updatingSecurity.value = false
    } catch (error) {
        errorToastText.value = "Une erreur est survenue, vos informations n'ont pas été modifiées"
        errorToast.value = true
        fetchUser()
        console.log('An unexpected error occured : ', error)
    }
}

const updateNotificationPreferences = async () => {
    try {
        await $fetch(`${config.public.baseUrl}/users/me/notification`, {
            method: 'PATCH',
            credentials: 'include',
            body: {
                newProposal: user.value['USR_notify_proposal_BOOL'],
                startOfVoting: user.value['USR_notify_vote_BOOL'],
                reactionToTheProposals: user.value['USR_notify_reaction_BOOL'],
                notificationFrequency: user.value['USR_notification_frequency_CH'],
            },
        })
        fetchUser()
    } catch (error) {
        errorToastText.value = 'Une erreur est survenue lors de la modification de vos préférences'
        errorToast.value = true
        console.log('An unexpected error occured : ', error)
        fetchUser()
    }
}

const updatePassword = async () => {
    if (firstPassword.value !== secondPassword.value) {
        errorToastText.value = 'Les deux mots de passes ne correspondent pas'
        errorToast.value = true
        return
    }
    try {
        await $fetch(`${config.public.baseUrl}/users/me/password`, {
            method: 'PATCH',
            credentials: 'include',
            body: {
                password: secondPassword.value,
            },
        })
        successToastText.value = 'Votre mot de passe a été modifié'
        successToast.value = true
        fetchUser()

        updatingSecurity.value = false
        updatingUserInfos.value = false
    } catch (error) {
        errorToastText.value = "Une erreur est survenue, votre mot de passe n'a pas été modifié"
        errorToast.value = true
        console.log('An unexptected error occured : ', error)
        fetchUser()
    }
}

onMounted(() => {
    fetchUser()
})

const disconnect = async () => {
    try {
        await $fetch(`${config.public.baseUrl}/auth/logout`, {
            method: 'POST',
            credentials: 'include',
        })
        navigateTo('/')
    } catch (error) {
        console.log('An error occured', error)
    }
}

const cantDelete = useState('cantDeleteUp', () => false)

const handleDelete = async () => {
    try {
        await $fetch(`${config.public.baseUrl}/users/me`, {
            method: 'DELETE',
            credentials: 'include',
        })
        navigateTo('/')
        deleteUser.value = false
        settingsModal.value = false
    } catch (error) {
        if (error.status == 400) {
            cantDelete.value = true
            return
        }
        console.log('An error occured', error)
    }
}
</script>
