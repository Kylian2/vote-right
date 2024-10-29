<template>
    <Header></Header>
    <main>
        <div class="registration" v-if="step === 1">
            <h1>Inscription</h1>
            <form class="registration__form">
                <Input type="text" name="lastname" placeholder="Entrez votre nom">Nom</Input>
                <Input type="text" name="firstname" placeholder="Entrez votre prénom">Prénom</Input>
                <Input type="email" name="email" placeholder="Entrez votre email">Email</Input>
                <Input type="password" name="password" placeholder="Entrez un mot de passe">Mot de passe</Input>
                <Button class="btn btn--full" @click="step++">Commencer l'inscription</Button>
                <NuxtLink class="second" to="#">Ou <span class="underline">se connecter</span></NuxtLink>
            </form>
        </div>
        <div class="registration" v-if="step === 2">
            <h1>Dites nous en plus</h1>
            <form class="registration__form">
                <Input type="text" name="address" placeholder="Entrez votre adresse">Adresse</Input>
                <div class="inline-input">
                    <Input type="text" name="city" placeholder="ex: Paris">Ville</Input>
                    <Input class="inline-input__second" type="text" name="zipcode" placeholder="ex: 75002">Code Postal</Input>
                </div>
                <Input type="date" name="birthdate" placeholder="Entrez votre date de naissance">Date de naissance</Input>
                <Button class="btn btn--full">Terminer l'inscription</Button>
                <NuxtLink class="second" to="#">Ou <span class="underline">se connecter</span></NuxtLink>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-1.png" alt="illustration">
        </div>
    </main>
</template>
<script setup>

const step = useState("step", ()=> 1);

</script>