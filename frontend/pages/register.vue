<template>
    <Header type="notlogged"></Header>
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
                    v-if="step === 1" :vModele="firstname" type="text" name="firstname" placeholder="Entrez votre prénom" required
                    :rules="[
                        (v) => Boolean(v) || 'Un prénom est requis', 
                        (v) => v.length < 50 || 'Le prénom est trop long', 
                    ]"
                >Prénom</Input>
                <Input 
                    v-if="step === 1" :vModele="email" type="email" name="email" placeholder="Entrez votre email" required
                    :rules="[
                        (v) => Boolean(v) || 'Un email est requis', 
                        (v) => v.length < 200 || `L'email est trop long`, 
                        (v) => validateEmail(v) || `L'email doit être de la forme example@email.com`, 
                    ]"
                >Email</Input>
                <Input 
                    v-if="step === 1" :vModele="password" type="password" name="password" placeholder="Entrez un mot de passe" required
                    :rules="[
                        (v) => Boolean(v) || 'Un mot de passe est requis', 
                        (v) => v.length > 8 || 'Le mot de passe doit contenir au moins 8 caractères', 
                    ]"
                >Mot de passe</Input>
                
                <!-- Deuxième partie -->
                <Input 
                    v-if="step === 2" :vModele="address" type="text" name="address" placeholder="Entrez votre adresse" required
                    :rules="[
                        (v) => Boolean(v) || 'Une adresse est requise', 
                        (v) => v.length < 200 || `L'adresse doit faire moins de 200 caractères`, 
                    ]"
                >Adresse</Input>
                <div v-if="step === 2" class="inline-input">
                    <Input 
                        :vModele="city" type="text" name="city" placeholder="ex: Paris" required
                        :rules="[
                            (v) => Boolean(v) || 'Une ville est requise', 
                        ]"  
                    >Ville</Input>
                    <Input 
                        :vModele="zipcode" class="inline-input__second" type="text" name="zipcode" placeholder="ex: 75002" required
                        :rules="[
                            (v) => Boolean(v) || 'Un code postal est requis', 
                            (v) => v.length === 5 || 'Le code postal est invalide', 
                        ]" 
                    >Code Postal</Input>
                </div>
                <Input 
                    v-if="step === 2" :vModele="birthdate" type="date" name="birthdate" placeholder="Entrez votre date de naissance" required
                    :rules="[
                        (v) => Boolean(v) || 'Une date de naissance est requise',  
                    ]"
                >Date de naissance</Input>
                <!--formmethod="dialog" permet au bouton de se comporter comme si il allait envoyer le formulaire (et donc de faire ses vérifications de format (ex emai ou date) mais de ne pas envoyer le formulaire)-->
                <Button v-if="step === 1" :disabled="!stepOneIsValid" formmethod="dialog" class="btn btn--full" @click="() => { step++ }">Commencer l'inscription</Button>
                <div class="registration__button-conteneur">
                    <Button v-if="step === 2" :disabled="!stepOneIsValid || !stepTwoIsValid" formmethod="dialog" class="btn btn--full" @click="handleForm()" >Terminer l'inscription</Button>
                    <Button v-if="step === 2" type="button" class="btn btn--cancel" @click="step--" >Retour</Button>
                </div>
                <NuxtLink class="second" to="/login">Ou <span class="underline">se connecter</span></NuxtLink>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-1.png" alt="illustration">
        </div>
    </main>
</template>
<script setup>

definePageMeta({
  middleware: ["guest"]
})

const step = useState("step", ()=> 1);

const lastname = useState("lastname", ()=> "");
const firstname = useState("firstname", ()=> "");
const email = useState("email", ()=> "");
const password = useState("password", ()=> "");
const address = useState("address", ()=> "");
const city = useState("city", ()=> "");
const zipcode = useState("zipcode", ()=> "");
const birthdate = useState("birthdate", ()=> "");

const firstnameValid = useState("firstnameValid");
const lastnameValid = useState("lastnameValid");
const emailValid = useState("emailValid");
const passwordValid = useState("passwordValid");
const addressValid = useState("addressValid");
const cityValid = useState("cityValid");
const zipcodeValid = useState("zipcodeValid");
const birthdateValid = useState("birthdateValid");

const stepOneIsValid  = computed(() => {
    return firstnameValid.value && lastnameValid.value && emailValid.value && passwordValid.value;
})

const stepTwoIsValid  = computed(() => {
    return addressValid.value && cityValid.value && zipcodeValid.value && birthdateValid.value;
})

const handleForm = async () => {

    try {
        const response = await $fetch(`http://localhost:3333/auth/register`, {
            method: 'POST',
            body: {
                lastname: lastname.value,
                firstname: firstname.value,
                email: email.value,
                password: password.value,
                address: address.value,
                zipcode: zipcode.value,
                birthdate: birthdate.value,
            }
        });

        if(response){
            navigateTo('/home');
        }

    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }

}

const validateEmail = (email) => {
    // Expression régulière pour vérifier une adresse e-mail
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Teste si l'e-mail correspond à l'expression régulière
    return regex.test(email);
}

</script>