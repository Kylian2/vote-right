<template>
<Header type="logged" actif="groupes"></Header>
<h1>Créer un groupe</h1>
<main class="new-community">
    <form>
        <div>
            <Input 
                type="text" name="name" placeholder="Donnez un nom à votre groupe" required
                :rules="[
                    (v) => Boolean(v) || 'Un nom est requis', 
                ]"
            >Nom</Input>
            <TextArea 
                name="description" placeholder="Décrivez votre groupe" :rows='10' required
                :rules="[
                    (v) => Boolean(v) || 'Une description est requise', 
                ]"    
            >Description</TextArea>
            <ColorPicker 
                name="color" :colors="colors"
                :rules="[
                        (v) => Boolean(v) || 'Une couleur est requise', 
                ]"
            >Couleur</ColorPicker>
            <EmojiPicker 
                name="emoji"
                :rules="[
                        (v) => Boolean(v) || 'Une couleur est requise', 
                ]"
            >Emoji</EmojiPicker>
        </div>    
        <div>
            <ImagePicker 
                name="image" :images="images" :vModele="image"
                :rules="[
                        (v) => Boolean(v) || 'Une couleur est requise', 
                ]"
                >
                <template #title>Image</template>
                <template #legend>Selectionnez une image de bannière pour votre groupe</template>
            </ImagePicker>
            <div class="btn-container">
                <NuxtLink class="btn btn--cancel" href="/communities">Annuler</NuxtLink>
                <button formmethod="dialog" :disabled="!formIsValid" @click="handleData" class="btn btn--full">Valider la création</button>
            </div>
        </div>
    </form>
</main>

</template>
<script setup>

definePageMeta({
    middleware: ["auth"]
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

const formIsValid  = computed(() => {
    return nameValid.value && descriptionValid.value && colorValid.value && emojiValid.value && imageValid.value;
})

const handleData = async () => {

    try{
        const response = await $fetch(`http://localhost:3333/community/store`, {
            method: 'POST',
            body: {
                name: name.value,
                description: description.value,
                color: color.value,
                emoji: emoji.value,
                image: image.value,
            },
            credentials: 'include'
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

            navigateTo('/communities');
        }
    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }

    return true;
}

</script>