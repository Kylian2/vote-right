<template>
    <div class="base">
        <label :for="name"><slot></slot></label>
        <input :id="name" :type="type" :placeholder="placeholder" :required="required"/>
    </div>
</template>
<script setup lang="ts">

const props = defineProps({
    name: {
        type: String, 
        required: true,
    }, 
    type: {
        type: String, 
        required: true,
    },
    placeholder: {
        type: String, 
        required: false,
    },
    required:{
        type: Boolean,
        required: false,
    }
})

</script>