<template>

    <div class="radio-input">
        <p><slot></slot></p>
        <div v-for="option in options" :key="option.value" class="checkbox">
            <input 
                type="radio" 
                :name="name" 
                :value="option.value" 
                :id="`${name}-${option.value}`"
                :checked="option.default"
                v-model="modele"
            >
            <label :for="`${name}-${option.value}`">{{ option.label }}</label>

        </div>
    </div>
    

</template>
<script setup>

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    options:{
        type: Object,
        required: true,
    },
    rules: {
        type: Array,
        required: false,
    }
})


const modele = useState(`${props.name}`, () => '');

for (const option of props.options) {
    if (option.default) {
        modele.value = option.value;
        break;
    }
}

const hasAChange = ref(false);
const valid = useState(`${props.name}Valid`, () => false);
valid.value = computed(() => errorMessages.value.length === 0);

// Validation des règles
const errorMessages = computed(() => {
if (!props.rules || !props.rules.length) return [];
return props.rules
    .map((rule) => (typeof rule === 'function' ? rule(modele.value) : true))
    .filter((result) => result !== true);
});

watch(modele, (newVal) => {
    hasAChange.value = true;
});


</script>