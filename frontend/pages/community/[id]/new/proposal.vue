<template>

    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>
    <h1>Nouvelle proposition</h1>

    <main class="new-proposal">
        <form class="new-proposal__form">
            <div class="new-proposal__form__input-container">
                <div>
                    <Input type="text" name="proposalTitle" placeholder="Donnez un titre à la proposition" required 
                        :rules="[
                            (v) => Boolean(v) || 'Un titre est requis', 
                        ]"
                    >Titre</Input>
                    <TextArea name="proposalDescription" placeholder="Décrivez la proposition" required
                        :rules="[
                            (v) => Boolean(v) || 'Une description est requise', 
                        ]"
                    >Description</TextArea>
                    <Select v-if="communityThemes" 
                        name="proposalTheme" 
                        placeholder="Choisissez un thème" 
                        :options="communityThemes.map((theme) => [theme['THM_name_VC'], theme['THM_id_NB']])"
                        :rules="[
                            (v) => Boolean(v) || 'Un thème est requis', 
                        ]"
                        >Thème</Select>
                </div>
                <div>
                    <Input type="text" name="proposalLocation" placeholder="Ex: 12 avenue du blé">Indiquez une localisation</Input>
                </div>
            </div>
            <div class="new-proposal__form__btn-container">
                <NuxtLink class="btn btn--cancel" href="/communities">Annuler</NuxtLink>
                <button v-if="community" formmethod="dialog" :disabled="!formIsValid" @click="handleData" class="btn btn--full" :style="{ 
                    background: community['CMY_color_VC']
                }">Valider la création</button>
            </div>
        </form>
    </main>

</template>

<script setup>

const config = useRuntimeConfig();
const route = useRoute();

definePageMeta({
    middleware: ["auth"]
})

const community = useState("community");
const communityThemes = useState("communityThemes");

const title = useState("proposalTitle");
const titleValid = useState("proposalTitleValid");
const description = useState("proposalDescription");
const descriptionValid = useState("proposalDescriptionValid");
const location = useState("proposalLocation");
const locationValid = useState("proposalLocationValid");
const theme = useState("proposalTheme");
const themeValid = useState("proposalThemeValid");

const formIsValid  = computed(() => {
    return titleValid.value && descriptionValid.value && locationValid.value && themeValid.value;
})

const fetchCommunityInformations = async () => {
    try{

        if(community.value) {return;}
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

        community.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const fetchCommunityTheme = async () => {
    try{

        if(communityThemes.value) {return;}
        
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/themes`, {
            credentials: 'include',
        })

        communityThemes.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

const handleData = async () => {

try{
    const response = await $fetch(`${config.public.baseUrl}/proposals`, {
        method: 'POST',
        body: {
            title: title.value,
            description: description.value,
            community: route.params.id,
            location: locationValid ? location.value : null,
            theme: theme.value,
        },
        credentials: 'include'
    });

    if(response){
        title.value = null;
        description.value = null;
        community.value = null;
        location.value = null;
        theme.value = null;

        navigateTo(`/community/${route.params.id}`);
    }
} catch (error) {
    console.error('An unexpected error occurred:', error);
}

return true;
}

onMounted(() => {
    fetchCommunityInformations();
    fetchCommunityTheme();
})

</script>