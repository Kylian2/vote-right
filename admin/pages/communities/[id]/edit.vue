<template>
    <Header></Header>
    <h1>Modifier le groupe</h1>
    <main class="modify-community">
        <form>
            <div>
                <Input 
                    type="text" name="name"  required
                    :rules="[
                        (v) => Boolean(v) || 'Un nom est requis', 
                    ]"
                >Nom</Input>
                <TextArea 
                    name="description" :rows='10' required
                    :rules="[
                        (v) => Boolean(v) || 'Une description est requise', 
                    ]"    
                >Description</TextArea>
                <ColorPicker 
                    name="color" :colors="colors"
                    :rules="[
                            (v) => Boolean(v) || 'Une couleur est requise', 
                    ]"
                    v-model="color"
                >Couleur</ColorPicker>
                <EmojiPicker 
                    name="emoji"
                    :rules="[
                            (v) => Boolean(v) || 'Une couleur est requise', 
                    ]"
                    v-model="emoji"
                >Emoji</EmojiPicker>
            </div>    
            <div>
                <ImagePicker 
                    name="image" :images="images"
                    :rules="[
                            (v) => Boolean(v) || 'Une couleur est requise', 
                    ]"
                    >
                    <template #title>Image</template>
                    <template #legend> Choisissez une nouvelle image si vous le voulez </template>
                </ImagePicker>
                <div class="btn-container">
                    <NuxtLink class="btn btn--cancel" :to="`/communities/${$route.params.id}`">Annuler</NuxtLink>
                    <button class="btn" formmethod="dialog" :disabled="!formIsValid" @click="handleData">Valider les modifications</button>
                </div>
            </div>
        </form>
    </main>
</template>

<script setup>

    const config = useRuntimeConfig();
    const route = useRoute();
    
    definePageMeta({
        middleware: ["auth", "managed"]
    })
    
    const name = useState("name");
    const nameValid = useState("nameValid");
    const description = useState("description");
    const descriptionValid = useState("descriptionValid");
    const color = useState("color");
    const colorValid = useState("colorValid");
    const emoji = useState("emoji");
    const emojiValid = useState("emojiValid");
    const image = useState("image");
    const imageValid = useState("imageValid");
    
    const colors = useState("colors", () => ["#5AB7EE", "#FDBE55", "#FB961F", "#13329F", "#8700CF", "#F669D9", "#DE3D59"])
    const images = useState("images", () => ["100001.png", "100002.png", "100003.png", "100004.png", "100005.png", "100006.png", "100007.png"])
    
    onMounted(() => {
        fetchData();
    })

    const formIsValid  = computed(() => {
        return nameValid.value && descriptionValid.value && colorValid.value && emojiValid.value && imageValid.value;
    })
    
    const handleData = async () => {
        try{                                       
            const response = await $fetch(`${config.public.baseUrl}/communities/${route.params.id}/update`, {
                method: 'PATCH',                 
                body: {
                    name: name.value,
                    description: description.value,
                    color: color.value,
                    emoji: emoji.value,
                    image: image.value,
                },
                credentials: 'include',
            });
    
            if(response){
                navigateTo(`/communities/${route.params.id}`);
            }

        } catch (error) {
            console.error('An unexpected error occurred:', error);
        }
    
        return true;
    }

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
    
    onBeforeUnmount(() => {
        const from = useState('from', () => {
            return {
                name: route.name,
                href: route.href,
            }
        }); 
        from.value = {
            name: route.name,
            href: route.href,
        }
    })
</script>