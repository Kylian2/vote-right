<template>
    <Header2></Header2>
    <main class="register">
        <div class="registration">
            <h1>Inscription</h1>
            <form  class="registration__form">

                <!-- Première partie -->
                <Input 
                    v-if="step === 1" :vModele="lastname" type="text" name="lastname" placeholder="Entrez votre nom" required
                    :rules="[
                        (v) => Boolean(v) || 'Un nom est requis', 
                        (v) => v.length < 50 || 'Le nom est trop long', 
                    ]"
                >Nom</Input>
                <Input 
                    v-if="step === 1" type="text" name="firstname" placeholder="Entrez votre prénom" required
                    :rules="[
                        (v) => Boolean(v) || 'Un prénom est requis', 
                        (v) => v.length < 50 || 'Le prénom est trop long', 
                    ]"
                >Prénom</Input>
                <Input 
                    v-if="step === 1" type="email" name="email" placeholder="Entrez votre email" required
                    :rules="[
                        (v) => Boolean(v) || 'Un email est requis', 
                        (v) => v.length < 200 || `L'email est trop long`, 
                        (v) => validateEmail(v) || `L'email doit être de la forme example@email.com`, 
                    ]"
                >Email</Input>
                <Input 
                    v-if="step === 1" type="password" name="password" placeholder="Entrez un mot de passe" required
                    :rules="[
                        (v) => Boolean(v) || 'Un mot de passe est requis', 
                        (v) => v.length > 8 || 'Le mot de passe doit contenir au moins 8 caractères', 
                    ]"
                >Mot de passe</Input>
                
                <!-- Deuxième partie -->
                <Input 
                    v-if="step === 2" type="text" name="address" placeholder="Entrez votre adresse" required
                    :rules="[
                        (v) => Boolean(v) || 'Une adresse est requise', 
                        (v) => v.length < 200 || `L'adresse doit faire moins de 200 caractères`, 
                    ]"
                >Adresse</Input>
                <div v-if="step === 2" class="inline-input">
                    <Input 
                    class="inline-input__first" type="text" name="city" placeholder="ex: Paris" required
                        :rules="[
                            (v) => Boolean(v) || 'Une ville est requise', 
                        ]"  
                    >Ville</Input>
                    <Input 
                        class="inline-input__second" type="text" name="zipcode" placeholder="ex: 75002" required
                        :rules="[
                            (v) => Boolean(v) || 'Un code postal est requis', 
                            (v) => v.length === 5 || 'Le code postal est invalide', 
                        ]" 
                    >Code Postal</Input>
                </div>
                <Input 
                    v-if="step === 2" type="date" name="birthdate" placeholder="Entrez votre date de naissance" required
                    :rules="[
                        (v) => Boolean(v) || 'Une date de naissance est requise',  
                    ]"
                >Date de naissance</Input>

                <Button v-if="step === 2 && !verificationCodeIsSend" type="button" class="btn btn--cancel" @click="sendCode()">Recevoir le code de verification</Button>
                <div class="registration__form__code" v-if="step === 2 && verificationCodeIsSend">
                    <InputNumber
                    placeholder="Entrez le code reçu par email"
                    name="code"
                    :rules="[
                        (v) => Boolean(v) || 'Veuillez entrer le code',  
                    ]"
                    >Entrez le code</InputNumber>
                    <span class="underline font--small" @click="sendCode">Renvoyer un code</span>
                </div>

                <!--formmethod="dialog" permet au bouton de se comporter comme si il allait envoyer le formulaire (et donc de faire ses vérifications de format (ex emai ou date) mais de ne pas envoyer le formulaire)-->
                <Button v-if="step === 1" :disabled="!stepOneIsValid" formmethod="dialog" class="btn btn--full" @click="() => { step++ }">Commencer l'inscription</Button>
                <div v-if="stepOneIsValid" class="registration__button-conteneur">
                    <Button v-if="step === 2" :disabled="!stepOneIsValid || !stepTwoIsValid" formmethod="dialog" class="btn btn--full" @click="handleForm()" >Terminer l'inscription</Button>
                    <Button v-if="step === 2" type="button" class="btn btn--cancel" @click="() => {
                        step--;
                        verificationCodeIsSend = false;
                    }" >Retour</Button>
                </div>
                <NuxtLink class="second" to="/login">Ou <span class="underline">se connecter</span></NuxtLink>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-1.png" alt="illustration">
        </div>
    </main>

<Toast 
name="sameEmail" 
:type="1" 
:time="5" 
:loader="true"
class="toast"
>
Email déjà utilisé
</Toast>

<Toast 
name="incorrectCode" 
:type="1" 
:time="5" 
:loader="true"
class="toast"
>
Code incorrect
</Toast>
</template>
<script setup>

const config = useRuntimeConfig();

definePageMeta({
  middleware: ["guest"]
})

const html = document.getElementsByTagName('html')[0];
html.classList.add('landing-page-background');

const step = useState("step", ()=> 1);
const verificationCodeIsSend = useState("verificationCodeIsSend", () => false);

const lastname = useState("lastname", ()=> "");
const firstname = useState("firstname", ()=> "");
const email = useState("email", ()=> "");
const password = useState("password", ()=> "");
const address = useState("address", ()=> "");
const city = useState("city", ()=> "");
const zipcode = useState("zipcode", ()=> "");
const birthdate = useState("birthdate", ()=> "");
const code = useState("code", ()=> "");

const firstnameValid = useState("firstnameValid");
const lastnameValid = useState("lastnameValid");
const emailValid = useState("emailValid");
const passwordValid = useState("passwordValid");
const addressValid = useState("addressValid");
const cityValid = useState("cityValid");
const zipcodeValid = useState("zipcodeValid");
const birthdateValid = useState("birthdateValid");
const codeValid = useState("codeValid", () => false);

const sameEmail = useState("sameEmailUp", () => false);
const incorrectCode = useState("incorrectCodeUp", () => false);

const stepOneIsValid  = computed(() => {
    return firstnameValid.value && lastnameValid.value && emailValid.value && passwordValid.value;
})

const stepTwoIsValid  = computed(() => {
    return addressValid.value && cityValid.value && zipcodeValid.value && birthdateValid.value && codeValid.value;
})

const handleForm = async () => {

    try {
        const response = await $fetch(`${config.public.baseUrl}/auth/register`, {
            method: 'POST',
            body: {
                lastname: lastname.value,
                firstname: firstname.value,
                email: email.value,
                password: password.value,
                address: address.value,
                zipcode: zipcode.value,
                birthdate: birthdate.value,
                code: code.value,
            }
        });

        if(response){
            navigateTo('/home');
        }

    } catch (error) {
        if(error.status === 400){
            incorrectCode.value = true;
        }
    }

}

const sendCode = async () => {
    try {
        await $fetch(`${config.public.baseUrl}/code/verification`, {
            method: 'POST',
            body: {
                email: email.value,
            }
        });
        verificationCodeIsSend.value = true
    } catch (error) {
        if(error.status === 409){
            sameEmail.value = true;
        }
    }
}

const validateEmail = (email) => {
    // Expression régulière pour vérifier une adresse e-mail
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Teste si l'e-mail correspond à l'expression régulière
    return regex.test(email);
}

onBeforeUnmount(() => {
    html.classList.remove('landing-page-background');
});

</script>