<template>
  <div 
    class="toast" 
    :class="{
      'toast--error': type === 1,
      'toast--alert': type === 2, 
      'toast--valid': type === 3, 
      'toast--info': type === 4,
      'disapear': disapear
    }"
    v-if="up"
  >
    <div class="toast__body">
      <div>
        <p><slot></slot></p>
      </div>
      <div class="toast__cross" @click="closeToast">
        <span></span>
        <span></span>
      </div>
    </div>

    <div class="toast__loader" v-if="loader">
        <p 
        class="toast__loader__bar" 
        :class="{ 'loading': loading }" 
        :style="{
            transition: `linear ${time}s`
        }"
        ></p>
    </div>
  </div>
</template>

<script setup>

// Définir les props
const props = defineProps({
  loader: {
    type: Boolean,
    required: false,
    default: false,
  },
  time: {
    type: Number,
    required: false,
    default: 10, // Temps en secondes
  },
  name: {
    type: String,
    required: true,
  },
});


const disapear = ref(false);
const loading = ref(false);

const up = useState(`${props.name}Up`);

const closeToast = () => {
  up.value = false;
  disapear.value = false;
  loading.value = false;
};

// Surveiller les changements de `up` pour démarrer/arrêter le timer
watch(
  up,
  (v) => {
    setTimeout(() => {
        loading.value = up.value;
    }, 100); 
    setTimeout(() => {
        up.value = false;
        disapear.value = false;
        loading.value = false;
    }, props.time * 1000); 
    setTimeout(() => {
        disapear.value = true;
    }, props.time * 1000 - 800); 
  }
);
</script>
