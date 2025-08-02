<template>
    <Header type="notlogged"></Header>
    <main class="login">
        <div class="registration registration__recovery">
            <h1 class="registration__recovery__title">Récupération de votre mot de passe</h1>
            <p v-if="step === 1" class="registration__recovery__p">
                Veuillez entrer votre adresse e-mail. Si celle-ci correspond à un compte existant, nous vous enverrons
                un code pour récupérer votre mot de passe.
            </p>
            <p v-if="step === 2" class="registration__recovery__p">
                Veuillez entrer le code reçu par e-mail. Si vous ne l'avez pas reçu, cliquez sur "Renvoyer un code".
            </p>
            <form class="registration__form">
                <!-- Première partie -->
                <p v-if="step === 1 && emailNotExist" class="error">Email inconnu, veuillez réessayer.</p>
                <Input v-if="step === 1" type="email" name="email" placeholder="Entrez votre email" required
                    >Votre email</Input
                >
                <Button
                    v-if="step === 1"
                    :disabled="!email"
                    formmethod="dialog"
                    class="btn btn--full"
                    @click="sendCode()"
                    >Envoyer</Button
                >

                <!-- Deuxième partie -->
                <p v-if="step === 2 && incorrectCode" class="error">Code incorrect, veuillez réessayer.</p>
                <div v-if="step === 2" class="registration__form__code">
                    <InputNumber
                        placeholder="Entrez le code reçu par email"
                        name="code"
                        :rules="[(v) => Boolean(v) || 'Veuillez entrer le code']"
                        >Entrez le code</InputNumber
                    >
                    <span class="underline font--small" @click="sendCode">Renvoyer un code</span>
                </div>
                <Button
                    v-if="step === 2"
                    :disabled="!code"
                    formmethod="dialog"
                    class="btn btn--full"
                    @click="checkCode()"
                    >Valider</Button
                >

                <!-- Troisième partie -->
                <p v-if="step === 3 && error" class="error">
                    Un problème est survenu, veuillez réessayer en rafraîchissant la page.
                </p>
                <Input
                    v-if="step === 3"
                    type="password"
                    name="password"
                    placeholder="Entrez votre nouveau mot de passe"
                    required
                    :rules="[
                        (v) => Boolean(v) || 'Un mot de passe est requis',
                        (v) => v.length > 8 || 'Le mot de passe doit contenir au moins 8 caractères',
                    ]"
                    >Nouveau mot de passe</Input
                >
                <Input
                    v-if="step === 3"
                    type="password"
                    name="confirmationPassword"
                    placeholder="Confirmez votre nouveau mot de passe"
                    required
                    :rules="[
                        (v) => Boolean(v) || 'Un mot de passe est requis',
                        (v) => v.length > 8 || 'Le mot de passe doit contenir au moins 8 caractères',
                        (v) => samePassword() || 'Le mot de passe est différent du premier',
                    ]"
                    >Confirmation du nouveau mot de passe</Input
                >
                <Button
                    v-if="step === 3"
                    :disabled="!passwordValid"
                    formmethod="dialog"
                    class="btn btn--full"
                    @click="changePassword()"
                    >Modifier</Button
                >
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-2.png" alt="illustration" />
        </div>
    </main>

    <Toast name="unknownError" :type="1" :time="10" :loader="true"
        >Une erreur inconnue est survenue, la demande n'a pas pu être traitée. Si le problème persiste, veuillez
        contacter le support.</Toast
    >
    <Toast name="emailSended" :type="3" :time="5" :loader="true">Le code a été envoyé.</Toast>
</template>

<script setup>
const config = useRuntimeConfig()

definePageMeta({
    middleware: ['guest'],
})

const step = useState('step', () => 1)

const email = useState('email', () => '')
const emailNotExist = useState('emailNotExist', () => false)

const code = useState('code', () => '')
const incorrectCode = useState('incorrectCode', () => false)

const password = useState('password', () => '')
const confirmationPassword = useState('confirmationPassword', () => '')

const error = useState('error', () => false)

const passwordValid = computed(() => {
    return password.value && confirmationPassword.value && samePassword()
})

const unknownError = useState('unknownErrorUp', () => false)
const emailSended = useState('emailSendedUp', () => false)

const sendCode = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/code/recovery`, {
            method: 'POST',
            body: {
                email: email.value,
            },
        })

        if (response !== true) {
            unknownError.value = true
            return
        }
        emailSended.value = true
        step.value = 2
    } catch (error) {
        console.error('Error sending code:', error)
        if (error.status === 400) {
            emailNotExist.value = true
        } else {
            error.value = true
        }
    }
}

const checkCode = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/code/check`, {
            method: 'POST',
            body: {
                email: email.value,
                code: code.value,
                action: 'recuperation-code',
            },
        })

        step.value = 3
    } catch (error) {
        if (error.status === 400) {
            incorrectCode.value = true
        }
    }
}

const changePassword = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/users/me/reset/password`, {
            method: 'PATCH',
            body: {
                email: email.value,
                code: code.value,
                password: password.value,
            },
        })

        navigateTo('/login')
    } catch (error) {
        if (error.status === 400) {
            error.value = true
        }
    }
}

const samePassword = () => {
    if (password.value == confirmationPassword.value) {
        return true
    }
    return false
}
</script>
