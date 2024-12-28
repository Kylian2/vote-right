<template>

<Header></Header>

<NuxtLink class="back" :to="`/communities/${$route.params.id}`">Retour au groupe</NuxtLink>
<h1 class="members__title">Gestion des membres</h1>
<main class="members">
    <div class="members__actions-bar">
        <button class="btn btn-small" @click="invitationModal = true">Inviter un membre</button>
        <button class="btn btn-small" :disabled="Object.keys(changedRole).length === 0" @click="roleUpdateValidationModal = true">Valider les changements</button>
    </div>
    <div class="members__list">
        <div v-for="member, key in members" class="member-card">
            <p><b>{{member['USR_firstname_VC']}}</b> {{ member['USR_lastname_VC'] }}</p>
            <div>
                <p class="legende">{{member['ROL_label_VC']}}</p>
                <select name="role-selector" @change="changeRole(member, $event.target.value)">
                    <option value="">- - -</option>
                    <option 
                        v-for="role in roles" 
                        :key="role['ROL_id_NB']" 
                        :value="role['ROL_id_NB']" :disabled="role['ROL_id_NB'] === member['MEM_role_NB']">
                        {{ role["ROL_label_VC"] }}
                    </option>                
                </select>
                <button @click="() => {
                    userToExclude = member;
                    userToExcludeIndex = key;
                    exclusionValidationModal = true;
                }">X</button>
            </div>
        </div>
    </div>
</main>
<Modal
name="roleUpdateValidation"
cancel-text="Annuler"
:before-ok="() => {
    postChanges();
}"
>
<template #title>Valider les changements</template>
<template #body>

    <p class="update-role__title">Récapitulatif des changements :</p>

    <ul class="update-role">
        <li v-for="member in changedUser">
            {{ member['USR_firstname_VC'] + " " + member['USR_lastname_VC'] }} 
            devient <b>{{ roles.find(r => r['ROL_id_NB'] == changedRole[member['USR_id_NB']])['ROL_label_VC'] }}</b>
        </li>
    </ul>

</template>
</Modal>

<Toast 
    name="administratorMissing" 
    :type="1" 
    :time="5" 
    :loader="true"
    class="toast"
>
Impossible de se retrograder
</Toast>

<Toast 
    name="roleUpdated" 
    :type="3" 
    :time="5" 
    :loader="true"
    class="toast"
>
Modifications appliquées
</Toast>

<Modal
name="exclusionValidation"
cancel-text="Annuler"
ok-text="Exclure"
:before-cancel="() => {
    userToExclude = {};
}"
:before-ok="() => {
    excludeUser();
    userToExclude = {}
}"
>
<template #title>Valider l'exclusion du membre ?</template>
</Modal>  

<Toast 
    name="impossibleToExcludeUser" 
    :type="1" 
    :time="5" 
    :loader="true"
    class="toast"
>
Impossible de supprimer l'utilisateur
</Toast>

<Toast 
    name="userHasBeenExcluded" 
    :type="3" 
    :time="5" 
    :loader="true"
    class="toast"
>
Utilisateur exclu
</Toast>

<Modal
name="invitation"
ok-text="Envoyer les invitations"
cancel-text="Annuler"
:disable-valid="!invitationTextValid"
:before-ok="() => {
    invitations = emailList(invitationText);
    invitationText = '';
    sendInvitations();
}"
>
<template #title>Inviter des membres</template>
<template #body>
    <TextArea
    name="invitationText"
    placeholder="ex: laurent@gmail.com;valentin@madouas.com"
    :rules="[
        (v) => Boolean(v) || 'Veuillez entrez des emails'
    ]"
    :rows="10">
    Entrez des emails
    </TextArea>
</template>
</Modal>

<Toast 
    name="notEveryInvitations" 
    :type="2" 
    :time="5" 
    :loader="true"
    class="toast"
>
{{ nbNotSended > 1 ? nbNotSended+" invitations n'ont pas été envoyées" : "1 invitation n'a pas été envoyée"}} 
</Toast>

<Toast 
    name="allInvitations" 
    :type="3" 
    :time="5" 
    :loader="true"
    class="toast"
>
Envoyées !
</Toast>

</template>

<script setup>
const config = useRuntimeConfig();
const route = useRoute();

definePageMeta({
    middleware: ["auth"]
})

const members = ref({});
const roles = ref({});
const changedRole = ref({});
const changedUser = ref({});
const userToExclude = ref({});
const userToExcludeIndex = ref();

const administratorMissing = useState('administratorMissingUp');
const impossibleToExcludeUser = useState('impossibleToExcludeUserUp');
const userHasBeenExcluded = useState('userHasBeenExcludedUp');
const roleUpdated = useState('roleUpdatedUp');

const roleUpdateValidationModal = useState('roleUpdateValidationModal', () => false);
const exclusionValidationModal = useState('exclusionValidationModal', () => false);
const invitationModal = useState('invitationModal', () => false);

const invitations = useState('invitations', ()=> []);
const invitationText = useState('invitationText');
const invitationTextValid = useState('invitationTextValid');

const notEveryInvitations = useState('notEveryInvitationsUp');
const allInvitations = useState('allInvitationsUp');

const nbNotSended = ref();

const fetchMembers = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/members`, {
            credentials: 'include',
        })

        members.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchRole = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/roles`, {
            credentials: 'include',
        })

        roles.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

//Peut-être qu'il existe une méthode plus efficace pour enregistrer les changements
const changeRole = (member, role) => {
    if(role != ''){
        changedRole.value[member['USR_id_NB']] = role;
        changedUser.value[member['USR_id_NB']] = member;
    }else{
        delete changedRole.value[member['USR_id_NB']];
        delete changedUser.value[member['USR_id_NB']];
    }
}

const postChanges = async () => {
    try{

        await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/members`, {
            method: 'POST',
            credentials: 'include',
            body: changedRole.value
        })

        members.value = {};
        fetchMembers();
        changedRole.value = {};
        changedUser.value = {};
        roleUpdated.value = true;

    }catch (error){
        console.log('An unexptected error occured : ', error);
        if(error.status === 401){
            administratorMissing.value = true;
            changedRole.value = {};
            changedUser.value = {};
        }
    }
}

const excludeUser = async () => {
    try{
        await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/exclude/${userToExclude.value['USR_id_NB']}`, {
            method: 'POST',
            credentials: 'include',
            body: changedRole.value
        })

        members.value.splice(userToExcludeIndex.value, 1);
        userHasBeenExcluded.value = true;

    }catch (error){
        console.log('An unexptected error occured : ', error);
        if(error.status === 401){
            impossibleToExcludeUser.value = true;
        }
    }
}

const emailList = (text) => {
    const invitations =   text.split(';')               
                            .map(email => email.trim()) // Supprimer les espaces autour de chaque email
                            .filter(email => email) // Retirer les entrées vides (au cas où il y ;;)
                            .filter((i) => validateEmail(i));
    return invitations;
}

const validateEmail = (email) => {
    // Expression régulière pour vérifier une adresse e-mail
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Teste si l'e-mail correspond à l'expression régulière
    return regex.test(email);
}

const pendingInvitation = ref(false); //Si on veut faire un loader
const sendInvitations = async() => {
    try{
        const response = await $fetch(`${config.public.baseUrl}/invitations`, {
            method: 'POST',
            credentials: 'include',
            body: {
                community: route.params.id,
                invitations: invitations.value
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

onMounted(() => {
    fetchMembers();
    fetchRole();
})
</script>