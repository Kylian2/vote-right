<template>
    <Header></Header>
    <h1>Modifier le groupe</h1>
    <main class="modify-community">
        <form>
            <div>
                <Input 
                    type="text" name="newName" v-model="name" required
                    :rules="[
                        (v) => Boolean(v) || 'Un nom est requis', 
                    ]"
                >Nom</Input>
                <TextArea 
                    name="newDescription" :rows='10' v-model="description" required
                    :rules="[
                        (v) => Boolean(v) || 'Une description est requise', 
                    ]"    
                >Description</TextArea>
                <ColorPicker 
                    name="newColor" :colors="colors"
                    :rules="[
                            (v) => Boolean(v) || 'Une couleur est requise', 
                    ]"
                >Couleur</ColorPicker>
                <EmojiPicker 
                    name="newEmoji"
                    :rules="[
                            (v) => Boolean(v) || 'Une couleur est requise', 
                    ]"
                >Emoji</EmojiPicker>
            </div>    
            <div>
                <ImagePicker 
                    name="newImage" :images="images" :vModele="image"
                    :rules="[
                            (v) => Boolean(v) || 'Une couleur est requise', 
                    ]"
                    >
                    <template #title>Image</template>
                    <template #legend> Choisissez une nouvelle image si vous le voulez </template>
                </ImagePicker>
                <div class="btn-container">
                    <NuxtLink class="btn btn--cancel" :to="`/communities/${$route.params.id}`">Annuler</NuxtLink>
                    <button class="btn btn--full" formmethod="dialog" :disabled="!formIsValid" @click="handleData">Valider les modifications</button>
                </div>
            </div>
        </form>
    </main>
</template>

<script setup>
import Input from '~/components/Input.vue';
import TextArea from '~/components/TextArea.vue';
import ColorPicker from '~/components/ColorPicker.vue';
import ImagePicker from '~/components/ImagePicker.vue';
import EmojiPicker from '~/components/EmojiPicker.vue';

    const config = useRuntimeConfig();
    const route = useRoute();

    onMounted(() => {
        fetchData();
    })
    
    definePageMeta({
        middleware: ["auth", "managed"]
    })
    
    const newName = useState("newName");
    const newNameValid = useState("newNameValid");
    const newDescription = useState("newDescription");
    const newDescriptionValid = useState("newDescriptionValid");
    const newColor = useState("newColor");
    const newColorValid = useState("newColorValid");
    const newEmoji = useState("newEmoji");
    const newEmojiValid = useState("newEmojiValid");
    const newImage = useState("newImage");
    const newImageValid = useState("newImageValid");
    
    const colors = useState("colors", () => ["#5AB7EE", "#FDBE55", "#FB961F", "#13329F", "#8700CF", "#F669D9", "#DE3D59"])
    const images = useState("images", () => ["100001.png", "100002.png", "100003.png", "100004.png", "100005.png", "100006.png", "100007.png"])
    
    const formIsValid  = computed(() => {
        return newNameValid.value && newDescriptionValid.value && newColorValid.value && newEmojiValid.value && newImageValid.value;
    })
    
    const handleData = async () => {
        try{                                       
            const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/update`, {
                method: 'POST',                 
                body: {
                    name: newName.value,
                    description: newDescription.value,
                    color: newColor.value,
                    emoji: newEmoji.value,
                    image: newImage.value,
                },
                credentials: 'include',
            });
    
            if(response){
                name.value = null;
                description.value = null;
                emoji.value = null;
                color.value = null;
                image.value = null;

                const selectedEmoji = useState("selectedEmoji");
                const colorSelected = useState("colorSelected");
                selectedEmoji.value = null;
                colorSelected.value = -1;

                navigateTo(`/communities/${route.params.id}`);
            }

        } catch (error) {
            console.error('An unexpected error occurred:', error);
        }
    
        return true;
    }

    const name = ref();
    const description = ref();
    const color = ref();
    const emoji = ref();
    const image = ref();

    const fetchData = async () => {
        try {
            const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}`, {
                credentials: 'include',
            });

            name.value = response.CMY_name_VC;
            description.value = response.CMY_description_TXT;
            color.value = response.CMY_color_VC;
            emoji.value = response.CMY_emoji_VC;
            image.value = response.CMY_image_VC;
        
        } catch (error) {
            console.error("An error occurred : ", error);
        }
    }
    
</script>