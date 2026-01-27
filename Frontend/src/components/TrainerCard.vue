<template>
  <div class="card">
    <h3>{{ trainer.name }}</h3>
    <p>Sporta veids: {{ trainer.sport }}</p>
  </div>
</template>

<script setup>
defineProps({
  trainer: {
    type: Object,
    required: true
  }
})
</script>