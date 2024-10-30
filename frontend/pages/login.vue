<template>
    <Header></Header>
    <main>
        <div class="registration">
            <h1>Connexion</h1>
            <form  class="registration__form">

                <!-- Première partie -->
                <Input :vModele="email" type="email" name="email" placeholder="Entrez votre email" required>Email</Input>
                <Input :vModele="password" type="password" name="password" placeholder="Entrez un mot de passe" required>Mot de passe</Input>

                <!--formmethod="dialog" permet au bouton de se comporter comme si il allait envoyer le formulaire (et donc de faire ses vérifications de format (ex emai ou date) mais de ne pas envoyer le formulaire)-->
                <Button formmethod="dialog" class="btn btn--full" @click="handleForm()">Connexion</Button>
                <NuxtLink class="second" to="/register">Ou <span class="underline">s'inscrire</span></NuxtLink>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-2.png" alt="illustration">
        </div>
    </main>
</template>
<script setup>

const email = useState("email", ()=> "");
const password = useState("password", ()=> "");


/* TODO : mettre en place des validations plus poussées */
const handleForm = async () => {

    try {
        const response = await $fetch(`http://localhost:3333/auth/login`, {
            method: 'POST',
            body: {
                email: email.value,
                password: password.value,
            }
        });

        if(response){
            navigateTo("/home");
        }

    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }

}

</script>