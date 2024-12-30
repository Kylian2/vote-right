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
            <button @click="settingsModal = !settingsModal">Mon compte</button>
        </div>
        <Modal
        name="settings"
        cancel-text="Quitter"
        >
            <template #title>Paramètres de compte</template>

            <template #subtitles>
                <div>
                    <p @click="MyInformationSection = true" :style="{fontWeight: MyInformationSection ? 'bold' : 'normal' }">Mes informations</p>
                    <p @click="MyInformationSection = false" :style="{fontWeight: !MyInformationSection ? 'bold' : 'normal' }">Notifications</p>
                </div>
            </template>

            <template #body>
                
                <div>
                    <Profile :style="{background: 'black'}" :user="user"/>
                    <div>
                        <p>{{ user['USR_firstname_VC'] }}</p>
                        <p>{{ user['USR_lastname_VC'] }}</p>
                    </div>
                </div>


                <div v-if="MyInformationSection">
                    <div>
                        <button @click="editChange">{{ allowedChanges ? 'Annuler' : 'Modifier' }}</button>
                    </div>
                    <div>
                        <p v-if="!allowedChanges">Date de naissance<span>{{ user['USR_birthdate_DATE'] }}</span></p>
                        <input v-else type="date" name="userBirthdate" v-model="birthdate">Date de naissance</input>
                    </div>

                    <div>
                        <p v-if="!allowedChanges">Adresse<span>{{ user['USR_address_VC'] }}</span></p>
                        <input v-else type="text" name="userAddress" v-model="address">Adresse</input>
                    </div>
                </div>


                <div v-else>
                    <div>
                        <p>Sélectionnez les notifications qui vous intéressent</p>
                    </div>

                    <div>
                        <div>
                            <input type="checkbox" :id="newProposal">
                            <label :for="newProposal">Nouvelle proposition :</label>
                        </div>

                        <div>
                            <input type="checkbox" :id="startOfVoting">
                            <label :for="startOfVoting">Début de vote :</label>
                        </div>

                        <div>
                            <input type="checkbox" :id="reactionToTheProposals">
                            <label :for="reactionToTheProposals">Réaction aux propositions :</label>
                        </div>

                        <div> 
                            <input type="checkbox" :id="notificationFrequency">
                            <label :for="notificationFrequency">Fréquence des notifications :</label>                           
                        </div>
                    </div>
                </div>

            </template>
        </Modal>
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
</template>

<script setup>

const config = useRuntimeConfig();

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

onMounted(() => {
    navigationMobile = document.getElementById("navigation-mobile");
    headerMobile = document.getElementById("header-mobile");
    logo = document.getElementById("logo");
    hamburger = document.getElementById("hamburger");
    fetchUser();
})

const toggleHeader = () => {
    headerMobile.classList.toggle("full");
    navigationMobile.classList.toggle("d-none");
    logo.classList.toggle("d-none");
    hamburger.classList.toggle("active");
}

const user = useState("user");
const settingsModal = useState(`settingsModal`, () => false);

const MyInformationSection = ref(true);
const allowedChanges = ref(false);

const birthdate = ref("");
const address = ref("");
const zipcode = ref("");
const email = ref("");
const password = ref("");

const fetchUser = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include',
        });

        user.value = response;

        setInformation();
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

const setInformation = () => {
    birthdate.value = user.value['USR_birthdate_DATE'];
    address.value =  user.value['USR_address_VC'];
    zipcode.value = user.value['USR_zipcode_CH'];
    email.value = user.value['USR_email_VC'];
    password.value = "";
}

const editChange = () => {
    allowedChanges.value = !allowedChanges.value;

    if(allowedChanges.value == false){
        setInformation();
    }
}

</script>
