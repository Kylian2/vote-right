<template>
    <header class="header--desktop" :class="{[`color-${color}`]: color}">
        <NuxtLink to="/" class="logo">
            <p>VoteRight</p>
        </NuxtLink>
        <nav v-if="type === 'notlogged'">
            <div class="btn--border--header__wrapper">
                <NuxtLink class="btn btn--border--header" to="/register"><p>S'inscrire</p></NuxtLink>
            </div>
            <div class="btn--full--header__wrapper">
                <NuxtLink class="btn btn--full--header" to="/login"><p>Se connecter</p></NuxtLink>
            </div>
        </nav>
        <nav v-if="type === 'logged'" class="logged">
            <div class="wrapper" :class="{'active' : actif === 'accueil'}" ><NuxtLink class="logged__link" to="/home">Accueil</NuxtLink></div>
            <div class="wrapper" :class="{'active' : actif === 'groupes'}"><NuxtLink class="logged__link" to="/communities">Groupes</NuxtLink></div>
        </nav>
        <div v-if="type === 'logged'" class="logged">
            <button @click="showModal = !showModal">Mon Compte</button>
        </div>
    </header>

    <header class="header--mobile" id="header-mobile" :class="{[`color-${color}`]: color}">
        <div>
            <NuxtLink id="logo" to="/" class="logo--mobile">VoteRight</NuxtLink>
            <div class="header__hamburger" id="hamburger" @click="toggleHeader">
                <span class="header__hamburger__bar"></span>
                <span class="header__hamburger__bar header__hamburger__bar--middle"></span>
                <span class="header__hamburger__bar"></span>
            </div>
        </div>
        <nav class="d-none" id="navigation-mobile">
            <NuxtLink to="/" class="logo--mobile"><b>Vote</b>Right</NuxtLink>
            <NuxtLink to="/login">Se connecter</NuxtLink>
            <NuxtLink to="/register">S'inscrire</NuxtLink>
        </nav>
    </header>
    <Modal
    :name="account"
    >
            <template #title>Paramètres de compte</template>
            <template #body>
                <div>
                    <div>
                        <button>Mes informations</button>
                    </div>
                    <div>
                        <button>Notifications</button>
                    </div>
                </div>
            </template>
        </Modal>
</template>

<script setup>

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    actif: {
        type: String,
        required: false,
    },
    color: {
        type: String,
        required: false,
    }
})

let navigationMobile;
let headerMobile;
let logo;
let hamburger;
const me = ref();
const showModal = useState("accountModal", () => false);

const toggleHeader = () => {
    headerMobile.classList.toggle("full");
    navigationMobile.classList.toggle("d-none");
    logo.classList.toggle("d-none");
    hamburger.classList.toggle("active");
}

const fetchUser = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include',
        });

        me.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

onMounted(() => {
    navigationMobile = document.getElementById("navigation-mobile");
    headerMobile = document.getElementById("header-mobile");
    logo = document.getElementById("logo");
    hamburger = document.getElementById("hamburger");
    fetchUser();
})

</script>
