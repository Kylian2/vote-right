<template>
    <div class="base">
      <label :for="name"><slot></slot></label>
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
        modelValue: {
            type: String,
            required: false,
        },
        rows: {
            type: Number,
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
  