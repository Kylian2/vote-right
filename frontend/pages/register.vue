<template>
    <Header></Header>
    <main>
        <div class="registration">
            <h1>Inscription</h1>
            <form class="registration__form">
                <Input type="text" name="lastname" placeholder="Entrez votre nom">Nom</Input>
                <Input type="text" name="firstname" placeholder="Entrez votre prénom">Prénom</Input>
                <Input type="email" name="email" placeholder="Entrez votre email">Email</Input>
                <Input type="password" name="password" placeholder="Entrez un mot de passe">Mot de passe</Input>
                <Button class="btn btn--full">Commencer l'inscription</Button>
                <NuxtLink class="second" to="#">Ou <span class="underline">se connecter</span></NuxtLink>
            </form>
        </div>
        <div class="illustration-conteneur">
            <img class="illustration" src="~/public/images/illustration-1.png" alt="illustration">
        </div>
    </main>
</template>