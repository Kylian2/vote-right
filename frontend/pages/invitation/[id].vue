<template>
    <Header type="notlogged"></Header>
    <main class="invitation">
        <div class="invitation__image" v-if="community" 
            :style= "{ background: `url('/images/communities/${community['CMY_image_VC']}') 0% 15% / cover` }">
        </div>
        <div class="invitation__content" v-if="invitation && community">
            <h2> {{ invitation['INV_firstname_VC'] }} {{ invitation['INV_lastname_VC'] }} vous invite à rejoindre le groupe "{{ community["CMY_name_VC"] }}"</h2>
            <p> En rejoignant le groupe, vous pourrez faire des propositions et participer aux votes. </p>
        </div>
        <div class="invitation__community">
            <div class="invitation__community-themes">
                <p class="invitation__community-themes-header"> Le groupe traite des thèmes : </p>
                <ul class="invitation__community-themes-list" v-if="communityThemes && communityThemes.length">
                    <li v-for="theme in communityThemes"> {{ theme['THM_name_VC'] }} </li>
                </ul>
            </div>
            <div class="invitation__community-verification">
                <Input type="number" name="code" placeholder="Entrez un code à 6 chiffres" min="000000" max="999999" required>
                    Code de sécurité
                </Input>
            </div>
        </div>
        <div class="invitation__user-actions">
            <Button class="btn btn--full" type="submit" >Accepter</Button>
            <Button class="btn btn--cancel">Refuser</Button>
        </div>
    </main>
</template>
  
<script setup>

const config = useRuntimeConfig();
const route = useRoute();

onMounted(() => {
    fetchData();
})

const invitation = ref();
const community = ref();
const communityThemes = ref([]);

const fetchData = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/invitation/${route.params.id}`, {
            credentials: 'include',
        });

        invitation.value = response;

        const response2 = await $fetch(`${config.public.baseUrl}/communities/${invitation.value.INV_community_NB}`, {
            credentials: 'include',
        });

        community.value = response2;

        const response3 = await $fetch(`${config.public.baseUrl}/communities/${invitation.value.INV_community_NB}/themes`, {
            credentials: 'include',
        });

        communityThemes.value = response3;

    } catch (error) {
        console.error("An error occurred", error);
    }
}

</script>