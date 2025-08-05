<template>
    <Header></Header>
    <h1>Modifier le groupe</h1>
    <main class="modify-community">
        <form>
            <div>
                <Input type="text" name="name" required :rules="[(v) => Boolean(v) || 'Un nom est requis']">Nom</Input>
                <TextArea
                    name="description"
                    :rows="10"
                    required
                    :rules="[(v) => Boolean(v) || 'Une description est requise']"
                    >Description</TextArea
                >
                <ColorPicker
                    name="color"
                    :colors="colors"
                    :rules="[(v) => Boolean(v) || 'Une couleur est requise']"
                    v-model="color"
                    >Couleur</ColorPicker
                >
                <EmojiPicker name="emoji" :rules="[(v) => Boolean(v) || 'Une couleur est requise']" v-model="emoji"
                    >Emoji</EmojiPicker
                >
            </div>
            <div>
                <div>
                    <p class="label">Image Actuelle</p>
                    <div
                        class="modify-community__image-view"
                        :style="{
                            background: `url(${config.public.baseUrl}/${image})`,
                            backgroundSize: `cover`,
                        }"
                    ></div>
                    <p class="label">Remplacer l'image</p>
                    <p class="legende">
                        Sélectionnez une nouvelle image pour votre groupe, celle-ci devra être au format png ou jpg.
                    </p>
                    <input type="file" @change="handleFileChange" />
                </div>
                <div class="btn-container">
                    <NuxtLink class="btn btn--cancel" :to="`/communities/${$route.params.id}`">Annuler</NuxtLink>
                    <button class="btn" formmethod="dialog" :disabled="!formIsValid" @click="handleData">
                        Valider les modifications
                    </button>
                </div>
            </div>
        </form>
    </main>
</template>

<script setup>
const config = useRuntimeConfig()
const route = useRoute()

definePageMeta({
    middleware: ['auth', 'managed'],
})

const name = useState('name')
const nameValid = useState('nameValid')
const description = useState('description')
const descriptionValid = useState('descriptionValid')
const color = useState('color')
const colorValid = useState('colorValid')
const emoji = useState('emoji')
const emojiValid = useState('emojiValid')
const image = useState('image')
const newImage = ref(null)

const colors = useState('colors', () => ['#5AB7EE', '#FDBE55', '#FB961F', '#13329F', '#8700CF', '#F669D9', '#DE3D59'])

const handleFileChange = (event) => {
    newImage.value = event.target.files[0]
}

onMounted(() => {
    fetchData()
})

const formIsValid = computed(() => {
    return nameValid.value && descriptionValid.value && colorValid.value && emojiValid.value
})

const handleData = async () => {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('description', description.value)
    formData.append('color', color.value)
    formData.append('emoji', emoji.value)
    formData.append('image', newImage.value)

    try {
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/update`, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        })

        if (response) {
            navigateTo(`/communities/${route.params.id}`)
        }
    } catch (error) {
        console.error('An unexpected error occurred:', error)
    }

    return true
}

const fetchData = async () => {
    try {
        const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
            credentials: 'include',
        })

        name.value = response.CMY_name_VC
        description.value = response.CMY_description_TXT
        color.value = response.CMY_color_VC
        emoji.value = response.CMY_emoji_VC
        image.value = response.CMY_image_VC
    } catch (error) {
        console.error('An error occurred : ', error)
    }
}

onBeforeUnmount(() => {
    const from = useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    })
    from.value = {
        name: route.name,
        href: route.href,
    }
})
</script>
