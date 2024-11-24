<template>

<Header type="logged" actif="groupes"></Header>
<h1>Créer un groupe</h1>
<main class="new-community">
    <form>
        <div>
            <Input :vModele="name" type="text" name="name" placeholder="Donnez un nom à votre groupe" required>Nom</Input>
            <TextArea :vModele="description" name="description" placeholder="Décrivez votre groupe" :rows='10' required>Description</TextArea>
            <ColorPicker name="color" :colors="colors" :vModele="color">Couleur</ColorPicker>
            <EmojiPicker name="emoji" :vModele="emoji" >Emoji</EmojiPicker>
        </div>    
        <div>
            <ImagePicker name="image" :images="images" :vModele="image">
                <template #title>Image</template>
                <template #legend>Selectionnez une image de bannière pour votre groupe</template>
            </ImagePicker>
            <div class="btn-container">
                <NuxtLink class="btn btn--cancel" href="/communities">Annuler</NuxtLink>
                <button formmethod="dialog" @click="handleData()" class="btn btn--full">Valider la création</button>
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
const description = useState("description");
const color = useState("color");
const emoji = useState("emoji");
const image = useState("image");

const colors = useState("colors", () => ["#5AB7EE", "#FDBE55", "#FB961F", "#13329F", "#8700CF", "#F669D9", "#DE3D59"])
const images = useState("images", () => ["100001.png", "100002.png", "100003.png", "100004.png", "100005.png", "100006.png", "100007.png"])

const verifData = () => {

    if(name.value === null){
        return false;
    }
    if(description.value === null){
        return false;
    }
    if(color.value === null){
        return false;
    }
    if(emoji.value === null){
        return false;
    }
    if(image.value === null){
        return false;
    }

    if(name.value.length > 150){
        return false;
    }

    return true;

}

const handleData = async () => {
    if(!verifData()){
        return false;
    }

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