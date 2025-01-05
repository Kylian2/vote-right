<template>

    <div class="base">
        <p><slot name="title"></slot></p>
        <p class="legend"><slot name="legend"></slot></p>
        <div v-if="hasAChange && errorMessages.length" class="error">
            <p v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
        </div>
        <div class="image-picker">
            <div  
                v-for="(image, index) in images"
                class="image-picker__image"
                :class="{ 'selected': (imageSelected === index)}"
                :style="{ 
                    background: `url(/images/communities/${image})`,
                    backgroundSize: `cover`
                }"
                @click="() => {
                    imageSelected = index;
                    modele = image;
                }"
            ></div>
        </div>
    </div>
    
</template>
    
<script setup>
    
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    images: {
        type: Array,
        required: true,
    },
    rules: {
        type: Array,
        required: false,
    }
})
const modele = useState(`${props.name}`, () => '');
const hasAChange = ref(false);
const valid = useState(`${props.name}Valid`, () => false);
valid.value = computed(() => errorMessages.value.length === 0);
const imageSelected = useState("imageSelected", () => -1);

// Validation des règles
const errorMessages = computed(() => {
if (!props.rules || !props.rules.length) return [];
return props.rules
    .map((rule) => (typeof rule === 'function' ? rule(modele.value) : true))
    .filter((result) => result !== true);
});

watch(modele, (newVal) => {
    hasAChange.value = true;
    imageSelected.value = props.images.indexOf(newVal);
});

    
</script>