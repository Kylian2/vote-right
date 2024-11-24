<template>

<div class="base">
    <p><slot></slot></p>
    <div class="color-picker">
        <span 
        v-for="(color, index) in colors"
        class="color-picker__color"
        :class="{ 'selected': (colorSelected === index)}"
        :style="{background: color}"
        @click="() => {
            colorSelected = index;
            modele = color;
        }"
        ></span>
    </div>
</div>

</template>

<script setup>

const colorSelected = useState("colorSelected", () => -1);
  
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    colors: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: String,
        required: false,
    },
})

// Récuperer la variable d'état créée dans le parent
const modele = useState(`${props.name}`, () => '')

//Définir un évenement pour prévenir le parent en cas de changement de valeur
const emit = defineEmits(['update:modelValue'])

// Émettre `update:modelValue` chaque fois que `modele` est modifié
watch(modele, (newVal) => {
    emit('update:modelValue', newVal)
})
  
</script>