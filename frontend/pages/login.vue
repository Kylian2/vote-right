<template>
    <Header type="notlogged"></Header>
    <main class="login">
        <div class="registration">
            <h1>Connexion</h1>
            <p v-if="error" class="error">Login ou mot de passe incorrect, veuillez réessayer</p>
            <form  class="registration__form">

                <!-- Première partie -->
                <Input :vModele="email" type="email" name="email" placeholder="Entrez votre email" required>Email</Input>
                <Input :vModele="password" type="password" name="password" placeholder="Entrez un mot de passe" required>Mot de passe</Input>

                <!--formmethod="dialog" permet au bouton de se comporter comme si il allait envoyer le formulaire (et donc de faire ses vérifications de format (ex emai ou date) mais de ne pas envoyer le formulaire)-->
                <div>
                    <NuxtLink class="second-password-recovery" to="/password-recovery"><span class="underline">Mot de passe oublié ?</span></NuxtLink>
                    <NuxtLink class="second-register" to="/register">Ou <span class="underline">s'inscrire</span></NuxtLink>
                </div>
                <div>
                    <NuxtLink class="second-recuperation" to="/recovery"><span class="underline">Mot de passe oublié ?</span></NuxtLink>
                </div>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-2.png" alt="illustration">
        </div>
    </main>
</template>
<script setup>

const config = useRuntimeConfig();

const email = useState("email", ()=> "");
const password = useState("password", ()=> "");

const error = ref(false);
definePageMeta({
  middleware: ["guest"]
})

const handleForm = async () => {

    try {
        const response = await $fetch(`${config.public.baseUrl}/auth/login`, {
            method: 'POST',
            body: {
                email: email.value,
                password: password.value,
            },
            credentials: 'include'
        });

        if(response){
            navigateTo("/home");
        }else{
            error.value = true;
        }

    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }

}

</script>