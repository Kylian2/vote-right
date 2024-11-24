<template>
    <div class="base">
        <label :for="name"><slot></slot></label>
        <div v-if="hasAChange && errorMessages.length" class="error">
            <p v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
        </div>
        <textarea
            :id="name"
            :placeholder="placeholder"
            :required="required"
            v-model="modele"
            :rows="rows ? rows : 4"
        />
    </div>
  </template>
  
<script setup>
  
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        required: false,
    },
    required: {
        type: Boolean,
        required: false,
    },
    rows: {
        type: Number,
        required: false,
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
  