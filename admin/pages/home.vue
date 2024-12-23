<template>
<Header></Header>
<h1>Groupes gérés</h1>

<main class="home">

    <div class="community-list">
        <p v-for="community, key in communities" 
            @mouseover="(event) => event.target.style.color = community['CMY_color_VC']" 
            @mouseout="(event) => event.target.style.color = ''">
        <strong>{{community["CMY_name_VC"]}}</strong> <span>{{community["CMY_nb_member_NB"] >= 2 ? community["CMY_nb_member_NB"] + " membres" : "1 membre"}}</span></p>
    </div>

</main>

</template>
<script setup>

definePageMeta({
  middleware: ["auth"]
})

const config = useRuntimeConfig();

const communities = ref();

const fetchData =  async() =>{

    try{
        const response = await $fetch(`${config.public.baseUrl}/communities/administered`, {
            credentials: 'include',
        });

        communities.value = response
    } catch (error) {
        console.log("An error occured", error);
    }

}

onMounted(() => {
    fetchData();
})

</script>