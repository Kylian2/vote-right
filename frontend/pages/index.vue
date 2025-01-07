<template>
    <Header type="notlogged"></Header>
</template>
<script setup>
definePageMeta({
  middleware: ["guest"]
})

useHead({
  title: 'VoteRight',
  meta: [
    { name: 'description', content: 'VoteRight : donnez à votre communauté le pouvoir de proposer, voter, discuter et gérer des projets collectifs. Idéal pour collectivités et associations engagées !' }
  ],
})
</script>