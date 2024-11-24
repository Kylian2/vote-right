<template>

    <div class="base">
        <p><slot name="title"></slot></p>
        <p class="legend"><slot name="legend"></slot></p>
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
    
    const imageSelected = useState("imageSelected", () => -1);
      
    const props = defineProps({
        name: {
            type: String,
            required: true,
        },
        images: {
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