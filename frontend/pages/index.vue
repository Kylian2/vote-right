<template>
    <Header type="notlogged"></Header>
</template>
<script setup>
definePageMeta({
  middleware: ["guest"]
})
</script>