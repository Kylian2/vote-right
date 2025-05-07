<template>

    <main class="index">

        <div>
            <h1>Connexion</h1>
            <form>
                <p v-if="error"class="error">Email ou mot de passe incorrect</p>
                <Input type="text" name="login" placeholder="Entrez votre email">Email</Input>
                <Input type="password" name="password" placeholder="Entrez votre mot de passe">Mot de passe</Input>
                <button formmethod="dialog" class="btn btn--full" @click="handleForm()">Connexion</button>

            </form>
        </div>

    </main>

</template>
<script setup>

definePageMeta({
  middleware: ["guest"]
})

const config = useRuntimeConfig();
const route = useRoute();

const login = useState("login", ()=> "");
const password = useState("password", ()=> "");

const error = ref(false);

const handleForm = async () => {

    try {
        const response = await $fetch(`${config.public.baseUrl}/auth/login`, {
            method: 'POST',
            body: {
                email: login.value,
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

//TODO : verifier si la fonction est utilisée sinon la supprimer
const validateVote = async (vote, answer) => {
    try{
        await $fetch(`${config.public.baseUrl}/proposals/${proposal}/${vote}/vote`, {
            method: 'POST',
            credentials: 'include',
            body: {
                valid: answer
            }
        })

    }catch (error){
        console.log('An unexpected error occured : ', error);
        if(error.status === 403){
            forbidden.value = true;
        }
    }
}

onBeforeUnmount(() => {
    const from = useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    }); 
    from.value = {
        name: route.name,
        href: route.href,
    }
})
</script>