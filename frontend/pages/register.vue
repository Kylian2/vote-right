<template>
    <Header type="notlogged"></Header>
    <main>
        <div class="registration">
            <h1>Inscription</h1>
            <form  class="registration__form">

                <!-- Première partie -->
                <Input v-if="step === 1" :vModele="lastname" type="text" name="lastname" placeholder="Entrez votre nom" required>Nom</Input>
                <Input v-if="step === 1" :vModele="firstname" type="text" name="firstname" placeholder="Entrez votre prénom" required>Prénom</Input>
                <Input v-if="step === 1" :vModele="email" type="email" name="email" placeholder="Entrez votre email" required>Email</Input>
                <Input v-if="step === 1" :vModele="password" type="password" name="password" placeholder="Entrez un mot de passe" required>Mot de passe</Input>
                
                <!-- Deuxième partie -->
                <Input v-if="step === 2" :vModele="address" type="text" name="address" placeholder="Entrez votre adresse" required>Adresse</Input>
                <div v-if="step === 2" class="inline-input">
                    <Input :vModele="city" type="text" name="city" placeholder="ex: Paris" required>Ville</Input>
                    <Input :vModele="zipcode" class="inline-input__second" type="text" name="zipcode" placeholder="ex: 75002" required>Code Postal</Input>
                </div>
                <Input v-if="step === 2" :vModele="birthdate" type="date" name="birthdate" placeholder="Entrez votre date de naissance" required>Date de naissance</Input>

                <!--formmethod="dialog" permet au bouton de se comporter comme si il allait envoyer le formulaire (et donc de faire ses vérifications de format (ex emai ou date) mais de ne pas envoyer le formulaire)-->
                <Button v-if="step === 1" formmethod="dialog" class="btn btn--full" @click="() => { if (complete()) step++ }">Commencer l'inscription</Button>
                <Button v-if="step === 2" formmethod="dialog" class="btn btn--full" @click="handleForm()" >Terminer l'inscription</Button>
                <NuxtLink class="second" to="/login">Ou <span class="underline">se connecter</span></NuxtLink>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-1.png" alt="illustration">
        </div>
    </main>
</template>
<script setup>

const step = useState("step", ()=> 1);

const lastname = useState("lastname", ()=> "");
const firstname = useState("firstname", ()=> "");
const email = useState("email", ()=> "");
const password = useState("password", ()=> "");
const address = useState("address", ()=> "");
const city = useState("city", ()=> "");
const zipcode = useState("zipcode", ()=> "");
const birthdate = useState("birthdate", ()=> "");


/* TODO : mettre en place des validations plus poussées */
const complete = () => {
    return (lastname.value.length > 0) && (firstname.value.length > 0) && (email.value.length > 0) && (password.value.length > 0);
}
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

</script>