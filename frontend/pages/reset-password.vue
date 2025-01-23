<template>
    <Header type="notlogged"></Header>
    <main class="login">
        <div class="registration">
            <h1>Récupération de votre mot de passe</h1>
            <p v-if="error" class="error">Email inconnu</p>
            <form  class="registration__form">
                <Input :vModele="email" type="email" name="email" placeholder="Entrez votre email" required>Votre email</Input>
                <Button formmethod="dialog" class="btn btn--full" @click="resetPassword()">Valider</Button>
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

const error = ref(false);
definePageMeta({
  middleware: ["guest"]
})

const resetPassword = async() => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me/reset-password`, {
            method: 'POST',
            body: {
                email: email.value
            }
        })

        invitations.value = [];
        if (response.message && response.message === 'Some invitations could not be sent') {
            notEveryInvitations.value = true;
            nbNotSended.value = response.expected - response.sent;
            return;
        }
        allInvitations.value = true;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }finally{
        pendingInvitation.value = false;
    }
}

</script>