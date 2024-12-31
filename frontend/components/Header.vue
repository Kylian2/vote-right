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
            <button @click="showSettingsModal">Mon compte</button>
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
    name="settings"
    cancel-text="Quitter"
    :disable-valid="(!allowedChanges && !allowedPasswordChange) || !settingsIsValid"
    :before-ok="() => {validate()}"
    :before-close="() => {close()}"
    >
        <template #title>Paramètres de compte</template>

        <template #subtitles>
            <div>
                <p @click="sectionChanged" :style="{fontWeight: MyInformationSection ? 'bold' : 'normal' }">Mes informations</p>
                <p @click="sectionChanged" :style="{fontWeight: !MyInformationSection ? 'bold' : 'normal' }">Notifications</p>
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
                    <button @click="editPasswordChange">{{ allowedPasswordChange ? 'Annuler la modification du mot de passe' : 'Modifier le mot de passe' }}</button>
                </div>

                <form>
                    <div>
                        <p v-if="!allowedChanges">Date de naissance<span>{{ user['USR_birthdate_DATE'] }}</span></p>
                        <Input v-else type="date" name="birthdate" placeholder="Entrez votre date de naissance" required
                        :rules="[
                            (v) => Boolean(v) || 'Une date de naissance est requise',  
                        ]">Date de naissance</Input>
                    </div>

                    <div>
                        <p v-if="!allowedChanges">Adresse<span>{{ user['USR_address_VC'] }}</span></p>
                        <Input v-else type="text" name="address" placeholder="Entrez votre nouvelle adresse" required
                        :rules="[
                            (v) => Boolean(v) || 'Une adresse est requise', 
                            (v) => v.length < 200 || `L'adresse doit faire moins de 200 caractères`, 
                        ]">Adresse</Input>
                    </div>

                    <div>
                        <p v-if="!allowedChanges">Code postal<span>{{ user['USR_zipcode_CH'] }}</span></p>
                        <Input v-else type="text" name="zipcode" placeholder="ex: 75002" required
                        :rules="[
                            (v) => Boolean(v) || 'Un code postal est requis', 
                            (v) => v.length === 5 || 'Le code postal est invalide', 
                        ]">Code Postal</Input>
                    </div>

                    <div>
                        <p v-if="!allowedChanges">Email<span>{{ user['USR_email_VC'] }}</span></p>
                        <Input v-else type="email" name="email" placeholder="Entrez votre nouvel email" required
                        :rules="[
                            (v) => Boolean(v) || 'Un email est requis', 
                            (v) => v.length < 200 || `L'email est trop long`, 
                            (v) => validateEmail(v) || `L'email doit être de la forme example@email.com`, 
                        ]">Email</Input>
                    </div>
        
                    <div>
                        <p v-if="!allowedPasswordChange">Mot de passe<span>********</span></p>
                        <Input v-else type="password" name="password" placeholder="Entrez votre nouveau mot de passe" required
                        :rules="[
                            (v) => Boolean(v) || 'Un mot de passe est requis', 
                            (v) => v.length >= 8 || 'Le mot de passe doit contenir au moins 8 caractères', 
                        ]">Mot de passe</Input>
                    </div>
                </form>
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
const allowedPasswordChange = ref(false);
const allowedValidate = useState("allowedValidate");

const birthdate = useState("birthdate", ()=> "");
const address = useState("address", ()=> "");
const zipcode = useState("zipcode", ()=> "");
const email = useState("email", ()=> "");
const password = useState("password", ()=> "");

const birthdateValid = useState("birthdateValid");
const addressValid = useState("addressValid");
const zipcodeValid = useState("zipcodeValid");
const emailValid = useState("emailValid");
const passwordValid = useState("passwordValid");

const settingsIsValid  = computed(() => {
    if(allowedChanges.value && allowedPasswordChange.value){
        return birthdateValid.value && addressValid.value && zipcodeValid.value && emailValid.value && passwordValid.value;
    }
    if(allowedChanges.value){
        return birthdateValid.value && addressValid.value && zipcodeValid.value && emailValid.value;
    }
    return passwordValid.value;
})

const fetchUser = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include'
        });

        user.value = response;

    } catch(error) {
        console.log("An error occured", error);
    }
}

const setInformation = () => {
    birthdate.value = user.value['USR_birthdate_DATE'];
    address.value =  user.value['USR_address_VC'];
    zipcode.value = user.value['USR_zipcode_CH'];
    email.value = user.value['USR_email_VC'];
}

const showSettingsModal = () => {
    settingsModal.value = !settingsModal.value;
    
    if(settingsModal.value){
        fetchUser();
        setInformation();
    }
}

const resetSettings = () => {
    allowedChanges.value = false;
    allowedPasswordChange.value = false;
    setInformation();
    password.value = "";
}

const editChange = () => {
    allowedChanges.value = !allowedChanges.value;

    if(allowedChanges.value == false){
        setInformation();
    }
}

const editPasswordChange = () => {
    allowedPasswordChange.value = !allowedPasswordChange.value;

    if(allowedPasswordChange.value == false){
        password.value = "";
    }
}

const sectionChanged = () => {
    MyInformationSection.value = !MyInformationSection.value;

    if(!MyInformationSection.value){
        resetSettings();
    }
}

const validateEmail = (email) => {
    // Expression régulière pour vérifier une adresse e-mail
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Teste si l'e-mail correspond à l'expression régulière
    return regex.test(email);
}

const validateInformation = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me/information`, {
            method: 'POST',
            credentials: 'include',
            body: {
                birthdate: birthdate.value,
                address: address.value,
                zipcode: zipcode.value,
                email: email.value
            }
        });
    } catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const validatePassword = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me/password`, {
            method: 'POST',
            credentials: 'include',
            body: {
                password: password.value
            }
        });
    } catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const validate = () => {
    if (allowedChanges.value){
        validateInformation();
    }
    if(allowedPasswordChange.value){
        validatePassword();
    }
    fetchUser();
}

const close = () => {
    resetSettings();
    MyInformationSection.value = true;
}

</script>
