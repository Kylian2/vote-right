<template>
    <div v-if="community" class="community__hero-banner__wrapper"
        :style="{ 
            background: `url(/images/communities/${community['CMY_image_VC']}) 0% 15% / cover`,
            backgroundSize: `cover`
        }"
    >
        <div class="community__hero-banner">
            <div>
                <NuxtLink v-if="back" :to="`/community/${community['CMY_id_NB']}`">Retour au groupe</NuxtLink>
            </div>
            <div class="community__hero-banner__infos">
                <h1><slot></slot></h1>
                <div v-if="themes">
                    <span v-for="theme in themes" class="community__hero-banner__infos__theme" :style="{ 
                        background: community['CMY_color_VC'],
                    }">{{ theme['THM_name_VC'] }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

const props = defineProps({
    community: Object,
    required: true,

    themes: Array,
    required: true,

    back: Boolean,
})

</script>