<template>
    <p
    class="proposal"
    :style="{background: proposal['PRO_color_VC']}"
    >
        {{ proposal["PRO_title_VC"]}}
        <span class="proposal__theme">{{ proposal["PRO_theme_VC"] }}</span>
    </p>
</template>
<script setup>

const props = defineProps({
    proposal : {
        type: Object,
        required: true,
    }
})
</script>