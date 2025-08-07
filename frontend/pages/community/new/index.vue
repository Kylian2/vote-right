<template>
    <Header type="logged" actif="groupes"></Header>
    <h1>Créer un groupe</h1>
    <main class="new-community">
        <form>
            <div>
                <Input
                    type="text"
                    name="name"
                    placeholder="Donnez un nom à votre groupe"
                    required
                    :rules="[(v) => Boolean(v) || 'Un nom est requis']"
                    >Nom</Input
                >
                <TextArea
                    name="description"
                    placeholder="Décrivez votre groupe"
                    :rows="10"
                    required
                    :rules="[(v) => Boolean(v) || 'Une description est requise']"
                    >Description</TextArea
                >
                <ColorPicker name="color" :colors="colors" :rules="[(v) => Boolean(v) || 'Une couleur est requise']"
                    >Couleur</ColorPicker
                >
                <EmojiPicker name="emoji" :rules="[(v) => Boolean(v) || 'Une couleur est requise']">Emoji</EmojiPicker>
            </div>
            <div>
                <div>
                    <p class="label">Image</p>
                    <p class="legende">
                        Sélectionnez une image pour votre groupe, celle-ci devra être au format png ou jpg.
                    </p>

                    <input type="file" @change="handleFileChange" />

                    <img v-if="image" class="new-community__image-preview" :src="imageUrl" />
                </div>
                <div class="btn-container">
                    <NuxtLink class="btn btn--cancel" href="/communities">Annuler</NuxtLink>
                    <button formmethod="dialog" :disabled="!formIsValid" @click="handleData" class="btn btn--full">
                        Valider la création
                    </button>
                </div>
            </div>
        </form>
    </main>
</template>
<script setup>
const config = useRuntimeConfig()

definePageMeta({
    middleware: ['auth'],
})

const name = useState('name')
const nameValid = useState('nameValid')
const description = useState('description')
const descriptionValid = useState('descriptionValid')
const color = useState('color')
const colorValid = useState('colorValid')
const emoji = useState('emoji')
const emojiValid = useState('emojiValid')
const image = useState('image', () => null)
const imageValid = useState('imageValid')
const colors = useState('colors', () => ['#5AB7EE', '#FDBE55', '#FB961F', '#13329F', '#8700CF', '#F669D9', '#DE3D59'])

const handleFileChange = (event) => {
    image.value = event.target.files[0]
    //imageContainer.val.src = URL.createObjectURL(image.value)
}

const imageUrl = computed(() => {
    return URL.createObjectURL(image.value)
})

const formIsValid = computed(() => {
    return nameValid.value && descriptionValid.value && colorValid.value && emojiValid.value && image.value !== null
})

const handleData = async () => {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('description', description.value)
    formData.append('color', color.value)
    formData.append('emoji', emoji.value)
    formData.append('image', image.value)

    try {
        const response = await $fetch(`${config.public.baseUrl}/communities`, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        })

        if (response) {
            name.value = null
            description.value = null
            emoji.value = null
            color.value = null
            image.value = null

            const selectedEmoji = useState('selectedEmoji')
            const colorSelected = useState('colorSelected')
            selectedEmoji.value = null
            colorSelected.value = -1

            navigateTo('/communities')
        }
    } catch (error) {
        console.error('An unexpected error occurred:', error)
    }

    return true
}
</script>
