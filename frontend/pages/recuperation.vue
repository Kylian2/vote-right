<template>
    <Header type="notlogged"></Header>
    <main class="login">
        <div class="registration">
            <h1>Récupération de votre mot de passe</h1>
            <p v-if="emailNotExist" class="error">Email inconnu</p>
            <form  class="registration__form">
                <Input v-if="!recuperationCodeIsSend" :vModele="email" type="email" name="email" placeholder="Entrez votre email" required>Votre email</Input>
                <Button v-if="!recuperationCodeIsSend" formmethod="dialog" class="btn btn--full" @click="sendCode()">Envoyer</Button>
                <div class="registration__form__code" v-if="recuperationCodeIsSend">
                    <InputNumber
                    placeholder="Entrez le code reçu par email"
                    name="code"
                    :rules="[
                        (v) => Boolean(v) || 'Veuillez entrer le code',  
                    ]"
                    >Entrez le code</InputNumber>
                    <span class="underline font--small" @click="sendCode">Renvoyer un code</span>
                </div>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-2.png" alt="illustration">
        </div>
    </main>
</template>

<script setup>
import InputNumber from '~/components/InputNumber.vue';

const config = useRuntimeConfig();

const email = ref("email", ()=> "");
const recuperationCodeIsSend = useState("recuperationCodeIsSend", () => false);
const emailNotExist = useState("emailNotExist", () => false);

const code = useState("code", ()=> "");

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
        })
        recuperationCodeIsSend.value = true
    } catch (error) {
        if(error.status === 409){
            emailNotExist.value = true;
        }
    }
}

</script>