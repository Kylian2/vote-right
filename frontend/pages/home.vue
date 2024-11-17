<template>
    <Header type="logged" actif="accueil"></Header>
    <h1> &#x1F44B; Bonjour {{ firstname }}</h1>

    <main class="home">
        <div class="communities_contener">

            <div class="communities_contener__header">
                <h2>Les Groupes</h2>
                <NuxtLink to="#">Tout voir</NuxtLink>
            </div>

            <div v-if="communities" class="communities_contener__bloc">
                <NuxtLink  v-for="community in communities" to="#" class="community__wrapper"
                :style="{ 
                    background: `url(/images/communities/${community['CMY_image_VC']})`,
                    backgroundSize: `cover`
                }"
                >
                    <div class="community">
                        <div class="community__header">
                            <h4> <span class="emoji">{{String.fromCodePoint(parseInt(community["CMY_emoji_VC"], 16))}}</span>{{ community["CMY_name_VC"] }}</h4>
                            <div class="community__themes">
                                <span v-for="theme in community['CMY_themes_TAB'].slice(0, 3)" 
                                        :style="{ background: community['CMY_color_VC'] }"
                                        class="tag">
                                        {{ theme }}
                                </span>
                                <span class="tag__infos" v-if="community['CMY_themes_TAB'].length > 3">+ {{ community['CMY_themes_TAB'].length - 3 }}</span>
                            </div>
                        </div>
                        <div class="community__header">
                            <p>
                                <span 
                                    class="nbmembre"
                                    :style="{ color: community['CMY_color_VC'] }"
                                    >
                                    {{ community["CMY_nb_member_NB"] }}
                                </span>
                                membres
                            </p>
                        </div>
                    </div>
                </NuxtLink>
            </div>
        </div>
        <div class="proposals">
            <div class="proposals__block">
                <h3>Proposition en cours</h3>
                <div class="proposals__contener">
                    <p v-for="proposal in ongoingProposals"
                    class="proposal"
                    :style="{background: proposal['PRO_color_VC']}"
                    >
                        {{ proposal["PRO_title_VC"]}}
                        <span class="proposal__theme">{{ proposal["PRO_theme_VC"] }}</span>
                    </p>
                </div>
            </div>

            <div class="proposals__block">
                <h3>Proposition terminées</h3>
                <div class="proposals__contener">
                    <p v-for="proposal in finishedProposals"
                    class="proposal"
                    :style="{background: proposal['PRO_color_VC']}"
                    >
                        {{ proposal["PRO_title_VC"]}}
                        <span class="proposal__theme">{{ proposal["PRO_theme_VC"] }}</span>
                    </p>
                </div>
            </div>
        </div>
    </main>
</template>
<script setup>

definePageMeta({
    middleware: ["auth"]
})

onMounted(() => {
    fetchData();
})

const communities = useState("communities");
const firstname = useState("firstname");
const ongoingProposals = useState("ongoingProposals");
const finishedProposals = useState("finishedProposals");

const fetchData = async () => {
    try{
        const response = await $fetch(`http://localhost:3333/community/index`, {
            credentials: 'include',
        });

        communities.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`http://localhost:3333/user/name`, {
            credentials: 'include',
        });

        firstname.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`http://localhost:3333/proposal/ongoing`, {
            credentials: 'include',
        });

        ongoingProposals.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }

    try{
        const response = await $fetch(`http://localhost:3333/proposal/finished`, {
            credentials: 'include',
        });

        finishedProposals.value = response;
        
    } catch(error) {
        console.log("An error occured", error);
    }
}

</script>   