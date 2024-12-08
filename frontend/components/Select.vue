<template>
    <div class="base">
        <label :for="name"><slot></slot></label>
        <select v-model="modele" :name="name">
            <option :disabled="hasAChange" value="">{{ placeholder }}</option>
            <option v-for="option in options" :value="option[1]"> {{ option[0] }}</option>
        </select>
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
    options:{
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