<template>
    <Header type="notlogged"></Header>
    <main class="login">
        <div class="registration">
            <h1>Récupération de votre mot de passe</h1>
            <form  class="registration__form">

                <!-- Première partie -->
                <p v-if="step === 1 && emailNotExist" class="error">Email inconnu, veuillez réessayer</p>
                <Input v-if="step === 1" type="email" name="email" placeholder="Entrez votre email" required>Votre email</Input>
                <Button v-if="step === 1" :disabled="!email" formmethod="dialog" class="btn btn--full" @click="sendCode()">Envoyer</Button>
                
                <!-- Deuxième partie -->
                <p v-if="step === 2 && incorrectCode" class="error">Code incorrect, veuillez réessayer</p>
                <div v-if="step === 2" class="registration__form__code">
                    <InputNumber
                    placeholder="Entrez le code reçu par email"
                    name="code"
                    :rules="[
                        (v) => Boolean(v) || 'Veuillez entrer le code',  
                    ]"
                    >Entrez le code</InputNumber>
                    <span class="underline font--small" @click="sendCode">Renvoyer un code</span>
                </div>
                <Button v-if="step === 2" :disabled="!code" formmethod="dialog" class="btn btn--full" @click="checkCode()">Valider</Button>

                <!-- Troisième partie -->
                <Input 
                    v-if="step === 3" type="password" name="password" placeholder="Entrez votre nouveau mot de passe" required
                    :rules="[
                        (v) => Boolean(v) || 'Un mot de passe est requis', 
                        (v) => v.length > 8 || 'Le mot de passe doit contenir au moins 8 caractères', 
                    ]"
                >Nouveau mot de passe</Input>
                <Input 
                    v-if="step === 3" type="password" name="confirmationPassword" placeholder="Confirmez votre nouveau mot de passe" required
                    :rules="[
                        (v) => Boolean(v) || 'Un mot de passe est requis', 
                        (v) => v.length > 8 || 'Le mot de passe doit contenir au moins 8 caractères', 
                        (v) => samePassword() || 'Le mot de passe est différent du premier'
                    ]"
                >Confirmation du nouveau mot de passe</Input>
                <Button v-if="step === 3" :disabled="!password || !confirmationPassword" formmethod="dialog" class="btn btn--full" @click="changePassword()">Modifier</Button>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-2.png" alt="illustration">
        </div>
    </main>
</template>

<script setup>

const config = useRuntimeConfig();

const step = useState("step", ()=> 1);

const email = useState("email", ()=> "");
const emailNotExist = useState("emailNotExist", () => false);

const code = useState("code", ()=> "");
const incorrectCode = useState("incorrectCodeUp", () => false);

const password = useState("password", ()=> "");
const confirmationPassword = useState("confirmationPassword", ()=> "");

definePageMeta({
  middleware: ["guest"]
})

const sendCode = async() => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/code/recuperation`, {
            method: 'POST',
            body: {
                email: email.value
            }
        });

        step.value = 2;
    } catch (error) {
        if(error.status === 409){
            emailNotExist.value = true;
        }
    }
}

const checkCode = async() => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/code/check`, {
            method: 'POST',
            body: {
                email: email.value,
                code: code.value,
                action: "recuperation-code"
            }
        });

        step.value = 3;
    } catch (error) {
        if(error.status === 400){
            incorrectCode.value = true;
        }
    }
}

const changePassword = async() => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me/password`, {
            method: 'PATCH',
            credentials: 'include',
            body: {
                password: password.value
            }
        });

        navigateTo('/login');
    } catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const samePassword = () => {
    if(password.value == confirmationPassword.value){
        return true;
    }
    return false;
}

</script>