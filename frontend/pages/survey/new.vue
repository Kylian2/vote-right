<template>

<Header type="logged" actif="sondages"></Header>
<h1>Créer un sondage</h1>

<main class="new-survey">

    <section>
        <div>
            <Input 
                type="text" name="title" placeholder="Donnez un titre à votre sondage" required
                :rules="[
                    (v) => Boolean(v) || 'Un titre est requis', 
                ]"
            >Titre</Input>
            <div>
                <button class="new-survey__description-button" v-if="!wantDescription" @click="() => wantDescription = true">Ajouter une description</button>
                <TextArea 
                v-if="wantDescription" name="description" placeholder="Décrivez votre sondage (facultatif)" :rows='10' required
                >Description</TextArea>
                <button class="new-survey__description-button--remove" v-if="wantDescription" @click="() => wantDescription = false">Retirer la description</button>
            </div>
            <ColorPicker 
                name="color" :colors="colors"
                :rules="[
                        (v) => Boolean(v) || 'Une couleur est requise',
                ]"
            >Couleur</ColorPicker>
        </div>

        <div>

            <div class="new-survey__date-options">
                <div class="new-survey__date-options__radio-container">
                    <div>
                        <Radio name="start-date" 
                        :options="[
                            {value: 'now', label: 'Immédiatement', default: true}, 
                            {value: 'custom', label: 'Choisir une date'}]" 
                        >Date de début</Radio>
                        <div>
                            <Input 
                                v-if="startDate === 'custom'" 
                                type="date" 
                                name="custom-start-date" 
                                :rules="[
                                    (v) => Boolean(v) || 'Une date de début est requise',
                                ]"
                            >Indiquez une date</Input>
                        </div>
                    </div>
                    <div>
                        <Radio name="end-date" 
                        :options="[
                            {value: 'now', label: 'Manuelle', default: true}, 
                            {value: 'custom', label: 'Choisir une date'}]" 
                        >Date de fin</Radio> 
                        <div>
                            <Input 
                                v-if="endDate === 'custom'" 
                                type="date" 
                                name="custom-end-date" 
                                :rules="[
                                    (v) => Boolean(v) || 'Une date de fin est requise',
                                ]"
                            >Indiquez une date</Input>
                        </div> 
                    </div>          
                </div>
            </div>

            <div>
                <Select name="suffrage" placeholder="Selectionnez un mode de scrutin" :options="suffrageMode">Mode de scrutin</Select>
            </div>

        </div>
    </section>

    <div class="btn-container">
        <NuxtLink class="btn btn--cancel" href="/communities">Annuler</NuxtLink>
        <button formmethod="dialog" :disabled="!formIsValid" @click="handleData" class="btn btn--full">Valider la création</button>
    </div>
</main>

</template>
<script setup>

const colors = useState("colors", () => ["#5AB7EE", "#FDBE55", "#FB961F", "#13329F", "#8700CF", "#F669D9", "#DE3D59"])
const suffrageMode = useState("suffrageMode", () => [
    ["Choix unique", "unique"],
    ["Choix multiples", "multiple"],
    ["Choix par classement", "classment"],
    ["Choix par pondération", "ponderation"],
]);
const wantDescription = ref(false);

const startDate = useState("start-date");
const startDateValid = useState("start-dateValid");
const endDate = useState("end-date");
const endDateValid = useState("end-dateValid")

const customStartDate = useState("custom-start-date");
const customStartDateValid = useState("custom-start-dateValid");
const customEndDate = useState("custom-end-date");
const customEndDateValid = useState("custom-end-dateValid");

const suffrage = useState("suffrage");
const suffrageValid = useState("suffrageValid");

const formIsValid = computed(() => {
    return startDateValid.value && endDateValid.value && suffrageValid.value &&
        (startDate.value === 'now' || customStartDateValid.value) &&
        (endDate.value === 'now' || customEndDateValid.value);
});

//https://sortablejs.github.io/Sortable/#shared-lists

</script>
