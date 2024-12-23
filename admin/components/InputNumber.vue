<template>
    <div class="base">
        <label :for="name"><slot></slot></label>
        <div v-if="hasAChange && errorMessages.length" class="error">
            <p v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
        </div>

        <input
            :id="name"
            type="number"
            :placeholder="placeholder"
            :required="required"
            :min="min"
            :max="max"
            v-model="modele"
        />
    </div>
</template>
  
<script setup>
import { ref, watch, computed } from 'vue';

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
rules: {
    type: Array,
    required: false,
},
min: {
    type: Number,
    required: false,
},
min: {
    type: Number,
    required: false,
},
step: {
    type: Number,
    required: false,
    default: 1,
},
});

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
  