<template>

    <div class="base">
        <p><slot></slot></p>
        <div v-if="hasAChange && errorMessages.length" class="error">
            <p v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
        </div>
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
      
    const props = defineProps({
        name: {
            type: String,
            required: true,
        },
        colors: {
            type: Array,
            required: true,
        },
        rules: {
            type: Array,
            require: false,
        }
    })
    
    const modele = useState(`${props.name}`, () => '');
    const hasAChange = ref(false);
    const valid = useState(`${props.name}Valid`, () => false);
    valid.value = computed(() => errorMessages.value.length === 0);
    const colorSelected = useState("colorSelected", () => -1);
    
    // Validation des règles
    const errorMessages = computed(() => {
    if (!props.rules || !props.rules.length) return [];
    return props.rules
        .map((rule) => (typeof rule === 'function' ? rule(modele.value) : true))
        .filter((result) => result !== true);
    });
    
    watch(modele, (newVal) => {
        hasAChange.value = true;
        colorSelected.value = props.colors.indexOf(newVal);
    });
      
    </script>