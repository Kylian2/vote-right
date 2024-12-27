<template>

<Header></Header>

<NuxtLink class="back">Retour au groupe</NuxtLink>
<h1 class="members__title">Gestion des membres</h1>

<main class="members">
    <div class="members__actions-bar">
        <button class="btn btn-small">Inviter un membre</button>
        <button class="btn btn-small" :disabled="Object.keys(changedRole).length === 0" @click="roleUpdateValidationModal = true">Valider les changements</button>
    </div>
    <div class="members__list">
        <div v-for="member in members" class="member-card">
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
                <button>X</button>
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

const roleUpdateValidationModal = useState('roleUpdateValidationModal', () => false);
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

onMounted(() => {
    fetchMembers();
    fetchRole();
})

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

        }catch (error){
            console.log('An unexptected error occured : ', error);
    }
}

</script>