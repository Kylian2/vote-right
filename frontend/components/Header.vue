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
            <button class="logged__link"  @click="settingsModal = !settingsModal">Mon compte</button>
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
    :disable-valid="(((!allowedInformationChanges && !allowedPasswordChange) || !settingsIsValid) && MyInformationSection) || (!notificationChanged && !MyInformationSection)"
    :before-cancel="() => {beforeCancel()}"
    :before-ok="() => {beforeOk()}"
    :before-close="() => {beforeClose()}"
    >
        <template #title>Paramètres de compte</template>

        <template #subtitles>
            <div class="header-settings-modal__section">
                <p @click="MyInformationSection = true, sectionChanged()" :style="{fontWeight: MyInformationSection ? 'bold' : 'normal' }">Mes informations</p>
                <p @click="MyInformationSection = false, sectionChanged()" :style="{fontWeight: !MyInformationSection ? 'bold' : 'normal' }">Notifications</p>
            </div>
        </template>

        <template #body>
            <div class="header-settings-modal__profile">
                <Profile :style="{color: 'black'}" :user="user"/>
                <div class="header-settings-modal__name">
                    <p>{{ user['USR_firstname_VC'] }}</p>
                    <p>{{ user['USR_lastname_VC'] }}</p>
                </div>
            </div>

            <div class="header-settings-modal__info">
                <div v-if="MyInformationSection">
                    <div class="container-checkbox">
                        <div class="form-check">
                            <input @click="editInformationChange" type="checkbox" id="editInformation" v-model="allowedInformationChanges">
                            <label for="editInformation">
                                Modifier vos informations
                            </label>
                        </div>
                    </div>
                    <div class="header-settings-modal__allowedChanges">
                        <div class="container-checkbox">
                            <div class="form-check">
                                <input @click="editPasswordChange" type="checkbox" id="editPassword" v-model="allowedPasswordChange">
                                <label for="editPassword">
                                    Modifier votre mot de passe
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <p v-else>Sélectionnez les notifications qui vous intéressent !</p>
            </div>

            <form v-if="MyInformationSection" class="header-settings-modal__settings-list">
                <div>
                    <p v-if="!allowedInformationChanges">Date de naissance :<span>{{ user['USR_birthdate_DATE'] }}</span></p>
                    <Input v-else type="date" name="birthdate" placeholder="Entrez votre date de naissance" required
                    :rules="[
                        (v) => Boolean(v) || 'Une date de naissance est requise',  
                    ]">Date de naissance</Input>
                </div>

                <div>
                    <p v-if="!allowedInformationChanges">Adresse :<span>{{ user['USR_address_VC'] }}</span></p>
                    <Input v-else type="text" name="address" placeholder="Entrez votre nouvelle adresse" required
                    :rules="[
                        (v) => Boolean(v) || 'Une adresse est requise', 
                        (v) => v.length < 200 || `L'adresse doit faire moins de 200 caractères`, 
                    ]">Adresse</Input>
                </div>

                <div>
                    <p v-if="!allowedInformationChanges">Code postal :<span>{{ user['USR_zipcode_CH'] }}</span></p>
                    <Input v-else type="text" name="zipcode" placeholder="ex: 75002" required
                    :rules="[
                        (v) => Boolean(v) || 'Un code postal est requis', 
                        (v) => v.length === 5 || 'Le code postal est invalide', 
                    ]">Code Postal</Input>
                </div>

                <div>
                    <p v-if="!allowedInformationChanges">Email :<span>{{ user['USR_email_VC'] }}</span></p>
                    <Input v-else type="email" name="email" placeholder="Entrez votre nouvel email" required
                    :rules="[
                        (v) => Boolean(v) || 'Un email est requis', 
                        (v) => v.length < 200 || `L'email est trop long`, 
                        (v) => checkEmail(v) || `L'email doit être de la forme example@email.com`, 
                    ]">Email</Input>
                </div>
    
                <div>
                    <p v-if="!allowedPasswordChange">Mot de passe :<span>********</span></p>
                    <Input v-else type="password" name="password" placeholder="Entrez votre nouveau mot de passe" required
                    :rules="[
                        (v) => Boolean(v) || 'Un mot de passe est requis', 
                        (v) => v.length >= 8 || 'Le mot de passe doit contenir au moins 8 caractères', 
                    ]">Mot de passe</Input>
                </div>
            </form>

            <div v-else class="header-settings-modal__settings-list">
                <div class="container-checkbox">
                    <div class="form-check">
                        <input @change="notificationChanged = true" type="checkbox" id="newProposal" :value="`newProposal`" v-model="checkedNotification">
                        <label for="newProposal">
                            Nouvelle proposition
                        </label>
                    </div>
                </div>

                <div class="container-checkbox">
                    <div class="form-check">
                        <input @change="notificationChanged = true" type="checkbox" id="startOfVoting" :value="`startOfVoting`" v-model="checkedNotification">
                        <label for="startOfVoting">
                            Début de vote
                        </label>
                    </div>
                </div>

                <div class="container-checkbox">
                    <div class="form-check">
                        <input @change="notificationChanged = true" type="checkbox" id="reactionToTheProposals" :value="`reactionToTheProposals`" v-model="checkedNotification">
                        <label for="reactionToTheProposals">
                            Réaction aux propositions
                        </label>
                    </div>
                </div>

                <div class="container-checkbox">
                    <div class="form-check">
                        <input @change="notificationChanged = true" type="checkbox" id="notificationFrequency" :value="`notificationFrequency`" v-model="checkedNotification">
                        <label for="notificationFrequency">
                            Fréquence des notifications (Hebdomadaire / Quotidienne)
                        </label>
                    </div>
                </div>
            </div>

        </template>
    </Modal>
    <Toast 
        name="settingsValid" 
        :type="3" 
        :time="5" 
        :loader="true"
        class="toast"
    >
    Votre/vos modification(s) ont été prises en compte !
    </Toast>
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
    fetchUser(true);
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

const allowedInformationChanges = ref(false);
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

const checkedNotification = ref([]);
const notificationChanged = ref(false);

const newProposal = ref(false);
const startOfVoting = ref(false);
const reactionToTheProposals = ref(false);
const notificationFrequencyCH = ref('');

const settingsValid = useState('settingsValidUp', ()=>false);

const settingsIsValid  = computed(() => {
    if(allowedInformationChanges.value && allowedPasswordChange.value){
        return birthdateValid.value && addressValid.value && zipcodeValid.value && emailValid.value && passwordValid.value;
    }
    if(allowedInformationChanges.value){
        return birthdateValid.value && addressValid.value && zipcodeValid.value && emailValid.value;
    }
    return passwordValid.value;
})

const fetchUser = async (updateSettings = false) => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me`, {
            credentials: 'include'
        });

        user.value = response;

        if(updateSettings){
            setInformation();
            setCheckedNotification();
        }
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

const setCheckedNotification = () => {
    checkedNotification.value = [];

    if(user.value['USR_notify_proposal_BOOL']){
        checkedNotification.value.push("newProposal");
    }
    if(user.value['USR_notify_vote_BOOL']){
        checkedNotification.value.push("startOfVoting");
    }
    if(user.value['USR_notify_reaction_BOOL']){
        checkedNotification.value.push("reactionToTheProposals");
    }
    if(user.value['USR_notification_frequency_CH'] == 'Q'){
        checkedNotification.value.push("notificationFrequency");
    }
}

const resetInformation = () => {
    allowedInformationChanges.value = false;
    allowedPasswordChange.value = false;
    
    setInformation();
    password.value = "";
}

const resetNotification = () => {
    notificationChanged.value = false;

    setCheckedNotification();
}

const editInformationChange = () => {
    if(allowedInformationChanges.value == false){
        setInformation();
    }
}

const editPasswordChange = () => {
    if(allowedPasswordChange.value == false){
        password.value = "";
    }
}

const sectionChanged = () => {
    if(!MyInformationSection.value){
        resetInformation();
    } else{
        resetNotification();
    }
}

const checkEmail = (email) => {
    // Expression régulière pour vérifier une adresse e-mail
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Teste si l'e-mail correspond à l'expression régulière
    return regex.test(email);
}

const beforeValidateNotification = () => {
    if(checkedNotification.value.includes("newProposal")){
        newProposal.value = true;
    } else {
        newProposal.value = false;
    }

    if(checkedNotification.value.includes("startOfVoting")){
        startOfVoting.value = true;
    } else {
        startOfVoting.value = false;
    }

    if(checkedNotification.value.includes("reactionToTheProposals")){
        reactionToTheProposals.value = true;
    } else {
        reactionToTheProposals.value = false;
    }

    if(checkedNotification.value.includes("notificationFrequency")){
        notificationFrequencyCH.value = 'Q';
    } else {
        notificationFrequencyCH.value = 'H';
    }
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

const validateNotification = async () => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/users/me/notification`, {
            method: 'POST',
            credentials: 'include',
            body: {
                newProposal: newProposal.value,
                startOfVoting: startOfVoting.value,
                reactionToTheProposals: reactionToTheProposals.value,
                notificationFrequency: notificationFrequencyCH.value
            }
        });
    } catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const beforeCancel = () => {
    resetInformation();
    resetNotification();
}

const beforeOk = () => {
    if (allowedInformationChanges.value){
        validateInformation();  
    }
    if(allowedPasswordChange.value){
        validatePassword();
        password.value = "";
    }
    if(notificationChanged.value){
        beforeValidateNotification();
        validateNotification();
    }
    fetchUser();
    settingsValid.value = true;
}

const beforeClose = () => {
    allowedInformationChanges.value = false;
    allowedPasswordChange.value = false;
    notificationChanged.value = false;
    // Afficher la section des informations de l'utilisateur en premier lorsqu'on ouvre la modale
    MyInformationSection.value = true;
}

</script>
