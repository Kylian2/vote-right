<template>

<div class="container-checkbox">
    <div> 
        <p class="form-label"><slot></slot></p>
        <div v-if="displayError && hasAChange && errorMessages.length" class="error">
            <p v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
        </div>
        <div class="form-check" v-for="choice, key in props.choices" :key="choice.value">
            <input :id="choice.name" type="checkbox" v-model="modele[key].value"/>
            <label :for="choice.name">{{ choice.label }}</label>
        </div>
    </div>
</div>

</template>
<script setup>

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    rules: {
        type: Array,
        required: false,
    },
    displayError: {
        type: Boolean,
        required: false,
        default: true,
    },
    choices: {
        type: Array,
        required: true,
    }
});

const modele = useState(`${props.name}`, () => props.choices);
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