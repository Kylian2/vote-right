<template>
    <NuxtLink :to="`/proposal/${proposal['PRO_id_NB']}`"
    class="card-proposal"
    :class="{ 'card-proposal__finished': finished }"
    :style="{background: proposal['PRO_color_VC']}"
    >
        {{ proposal["PRO_title_VC"]}}
        <span class="card-proposal__theme">{{ trunc(proposal["PRO_theme_VC"]) }}</span>
    </NuxtLink>
</template>
<script setup>

const props = defineProps({
    proposal : {
        type: Object,
        required: true,
    },
    finished : {
        type: Boolean,
        required: false,
        default: false,
    }
})

const trunc = (line) => {
    return line.length > 62 ? line.substr(0,62)+"..." : line;
}
</script>