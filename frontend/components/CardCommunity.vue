<template>
    <NuxtLink to="#" class="community__wrapper"
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
</template>
<script setup>
const props = defineProps({

    community: {
        type: Object,
        required: true,
    }

})
</script>
