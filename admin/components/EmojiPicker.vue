<template>

<div class="emoji-picker flex align-start">
    <div class="base-2">
		<p><slot></slot></p>
			<div class="emoji-picker__container" ref="pickerContainer">
			<button type="button" class="emoji-picker__btn" @click="togglePicker" :class="{'emoji': selectedEmoji}">{{ selectedEmoji || placeholder || 'Choisir' }}</button>
		</div>
    </div>
    <div v-if="showPicker" class="emoji-picker__picker" >
        <div
            v-for="emoji in emojisList"
            :key="emoji"
            class="emoji-picker__emoji"
            @click="selectEmoji(emoji)"
        >
            {{ emoji.emoji }}
        </div>
    </div>
	<div v-if="hasAChange && errorMessages.length" class="error">
        <p v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
    </div>
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
	rules: {
		type: Array,
		required: false,
	}
})

const modele = useState(`${props.name}`, () => '');
const hasAChange = ref(false);
const valid = useState(`${props.name}Valid`, () => false);
valid.value = computed(() => errorMessages.value.length === 0);

const showPicker = useState("showPicker", () => false);
const selectedEmoji = useState("selectedEmoji", () => '');

// Validation des règles
const errorMessages = computed(() => {
if (!props.rules || !props.rules.length) return [];
return props.rules
    .map((rule) => (typeof rule === 'function' ? rule(modele.value) : true))
    .filter((result) => result !== true);
});

watch(modele, (newVal) => {
    hasAChange.value = true;
    selectEmoji(emojisList.find(item => item.code === newVal))
});

//Il faudra peut etre intérroger une API à terme
const emojisList = [
  { emoji: '😀', code: '1F600', name: 'Smiley Face' },
  { emoji: '😂', code: '1F602', name: 'Face with Tears of Joy' },
  { emoji: '😍', code: '1F60D', name: 'Heart Eyes' },
  { emoji: '👍', code: '1F44D', name: 'Thumbs Up' },
  { emoji: '🎉', code: '1F389', name: 'Party Popper' },
  { emoji: '❤️', code: '2764', name: 'Red Heart' },
  { emoji: '😢', code: '1F622', name: 'Crying Face' },
  { emoji: '🔥', code: '1F525', name: 'Fire' },
  { emoji: '🐶', code: '1F436', name: 'Dog' },
  { emoji: '🐱', code: '1F431', name: 'Cat' },
  { emoji: '🐭', code: '1F42D', name: 'Mouse' },
  { emoji: '🐹', code: '1F439', name: 'Hamster' },
  { emoji: '🐰', code: '1F430', name: 'Rabbit' },
  { emoji: '🐻', code: '1F43B', name: 'Bear' },
  { emoji: '🐼', code: '1F43C', name: 'Panda' },
  { emoji: '🐨', code: '1F428', name: 'Koala' },
  { emoji: '🍎', code: '1F34E', name: 'Apple' },
  { emoji: '🍕', code: '1F355', name: 'Pizza' },
  { emoji: '🍩', code: '1F369', name: 'Doughnut' },
  { emoji: '🍺', code: '1F37A', name: 'Beer' },
  { emoji: '🍔', code: '1F354', name: 'Burger' },
  { emoji: '🍇', code: '1F347', name: 'Grapes' },
  { emoji: '🍓', code: '1F353', name: 'Strawberry' },
  { emoji: '🥑', code: '1F951', name: 'Avocado' },
  { emoji: '⚽', code: '26BD', name: 'Soccer Ball' },
  { emoji: '🏀', code: '1F3C0', name: 'Basketball' },
  { emoji: '🎮', code: '1F3AE', name: 'Video Game Controller' },
  { emoji: '🎸', code: '1F3B8', name: 'Guitar' },
  { emoji: '🎯', code: '1F3AF', name: 'Bullseye' },
  { emoji: '🚗', code: '1F697', name: 'Car' },
  { emoji: '🚀', code: '1F680', name: 'Rocket' },
  { emoji: '✈️', code: '2708', name: 'Airplane' },
  { emoji: '🚤', code: '1F6A4', name: 'Speedboat' },
  { emoji: '🗼', code: '1F5FC', name: 'Tokyo Tower' },
  { emoji: '🗽', code: '1F5FD', name: 'Statue of Liberty' },
  { emoji: '🏝️', code: '1F3DD', name: 'Desert Island' },
  { emoji: '🏜️', code: '1F3DC', name: 'Desert' }
];


// Référence au container
const pickerContainer = ref(null);

// Fonction pour afficher/masquer le picker
function togglePicker() {
  showPicker.value = !showPicker.value;
}

// Fonction pour sélectionner un emoji
function selectEmoji(emoji) {
  selectedEmoji.value = emoji.emoji;
  showPicker.value = false;
  modele.value = emoji.code;
}

// Fonction pour masquer le picker quand on clique ailleurs
function handleClickOutside(event) {
  if (pickerContainer.value && !pickerContainer.value.contains(event.target)) {
    showPicker.value = false;
  }
}

// Ajouter un écouteur d'événement lors du montage du composant
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

// Nettoyer l'écouteur lors du démontage du composant
onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>