<template>
    <Header type="logged"   :color="community && community['CMY_color_VC'] ? community['CMY_color_VC'].slice(-6) : '000000'"></Header>

    <div v-if="community" class="community__hero-banner__wrapper"
        :style="{ 
            background: `url(/images/communities/${community['CMY_image_VC']}) 0% 15% / cover`,
            backgroundSize: `cover`
        }"
    >
        <div class="community__hero-banner">
            <div></div>
            <div>
                <h1>{{ community['CMY_name_VC'] }}</h1>
                <div></div>
            </div>
        </div>
    </div>

    <main class="community" v-if="community">

        <div class="community__description">
            <h2>Description</h2>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam tempus non turpis eget semper. Vestibulum placerat leo quis enim faucibus hendrerit. Phasellus finibus varius sollicitudin. Ut quis posuere justo. Integer facilisis euismod metus, non vestibulum nibh commodo at. Sed semper quis nisi non mattis. In ut gravida justo.
        </div>

        <div class="community__action-block">
            <NuxtLink to="#" class="btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">Nouvelle proposition</NuxtLink>
            <NuxtLink to="#" class="btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">Voir les membres</NuxtLink>
            <NuxtLink to="#" class="btn--full btn--block" :style="{ 
                background: community['CMY_color_VC'],
            }">ADMINPANEL</NuxtLink>
        </div>
    </main>
</template>
<script setup>

definePageMeta({
    middleware: ["auth"]
})

const route = useRoute();

const community = ref();

const fetchData = async () => {
    try{

        const response = await $fetch(`http://localhost:3333/communities/${route.params.id}`, {
            credentials: 'include',
        })

        community.value = response;

    }catch (error){
        console.log('An unexptected error occured : ', error);
    }
}

onMounted(()=>{
    fetchData();
})

</script>