<template>
    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>

    <Banner :community="community" :themes="communityThemes" back>{{ community["CMY_name_VC"] }}</Banner>

    <main v-if="members && members.length" class="community-members">
        <div class="community-members__main-header">
            <select v-model="filter" name="role" @change="() => {
                if(filter === ''){
                    selectedMembers = members
                }else{
                    selectedMembers = members.filter((member) => member['MEM_role_NB'] === filter) 
                }
            }">
                <option value="">Filtrer</option>
                <option :value="1">Administrateur</option>
                <option :value="2">Décideur</option>
                <option :value="3">Assesseur</option>
                <option :value="4">Modérateur</option>
                <option :value="5">Membre</option>
            </select>
        </div>  
        <div class="member-card" v-for="member in selectedMembers">
            <p><b>{{ member["USR_firstname_VC"] }}</b> {{ member["USR_lastname_VC"] }}</p>
            <p><span>{{ member["ROL_label_VC"] }}</span></p>
        </div>
    </main>
</template>
<script setup>

const config = useRuntimeConfig();

definePageMeta({
    middleware: ["auth"]
})

const route = useRoute();

const community = useState("community");
const communityThemes = useState("communityThemes");
const members = ref();
const selectedMembers = ref();
const filter = ref('');

const fetchData = async () => {
    try{

        //Grace a useState, si le valeur à déja été chargée (dans le cas où il arrive depuis une autre page, pas besoin de refaire une requête)
        if(community.value){
            return;
        }

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

        community.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }

    try{

        if(communityThemes.value){
            return;
        }

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/themes`, {
            credentials: 'include',
        })

        communityThemes.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchMembers = async () => {
    try{

        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/members`, {
            credentials: 'include',
        })

        members.value = response;
        selectedMembers.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

onMounted(() => {
    fetchData();
    fetchMembers();
})



</script>