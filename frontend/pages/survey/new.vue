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

            <div class="new-survey__choice-list" v-if="suffrage">
                <p>Indiquez les choix</p>
                <div class="sortable-list" id="sortable-list-1" ref="sortableList">                     
                    <div v-for="item, key in choiceList" class="sortable-list__item" draggable="true"
                        @dragstart="(e) => {
                            draggedItem = key;
                        }">
                        <i class="material-icons">drag_handle</i>
                        <input v-model="choiceList[key]" placeholder="Indiquez un choix"></input>
                        <i class="material-icons close-icon"
                            @click="() => {
                                choiceList.splice(key, 1);
                                addDropzone();
                            }"
                        >close</i></div>     
                    <p v-if="choiceList.length === 0" class="legende">Aucun choix</p>                
                </div>

                <button class="btn btn--small" @click="() => {
                    choiceList.push('');
                    addDropzone();
                }">Ajouter un choix</button>
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

// ------- Gestion des dropzones -------

const sortableList = ref(null);
const draggedItem = ref(null);
const choiceList = useState("surveyChoiceList", () => ["Choix 1", "Choix 2", "Choix 3"]);
const createDropzoneWithListeners = () => {
    const dropzone = document.createElement('div');
    dropzone.className = 'sortable-list__dropzone';
    dropzone.innerHTML = '<span></span>';
    
    dropzone.addEventListener('dragover', (e) => {
        e.target.classList.add('dragover');
        e.preventDefault();
    }, false);
    
    dropzone.addEventListener('dragleave', (e) => {
        e.target.classList.remove('dragover');
    });
    
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        
        let target = e.target;
        if (!target.classList.contains('sortable-list__dropzone')) {
            target = target.closest('.sortable-list__dropzone');
        }
        
        target.classList.remove('dragover');
        const index = parseInt(target.getAttribute('data-index'));
        const element = choiceList.value[draggedItem.value];
        choiceList.value.splice(draggedItem.value, 1);
        choiceList.value.splice(index, 0, element);

        // Nettoyer et recréer toutes les dropzones
        Array.from(sortableList.value.getElementsByClassName('sortable-list__dropzone')).forEach(dropzone => {
            dropzone.remove();
        });
        
        addDropzone();
    });
    
    return dropzone;
};

const addDropzone = () => {

    // Nettoyer les anciennes dropzones
    Array.from(sortableList.value.getElementsByClassName('sortable-list__dropzone')).forEach(dropzone => {
        dropzone.remove();
    });

    const array = Array.from(sortableList.value.getElementsByClassName('sortable-list__item'));

    // Dropzone au début de la liste
    const firstDropzone = createDropzoneWithListeners();
    firstDropzone.setAttribute('data-index', '0');
    if (array.length > 0) {
        sortableList.value.insertBefore(firstDropzone, array[0]);
    } else {
        sortableList.value.appendChild(firstDropzone);
    }
    
    // Dropzones entre les éléments
    for(let i = 0; i < array.length; i++){
        const dropzoneClone = createDropzoneWithListeners();
        dropzoneClone.setAttribute('data-index', `${i + 1}`);
        sortableList.value.insertBefore(dropzoneClone, array[i].nextSibling);
    }
    
    // Dropzone à la fin de la liste
    const lastDropzone = createDropzoneWithListeners();
    lastDropzone.setAttribute('data-index', `${array.length + 1}`);
    sortableList.value.appendChild(lastDropzone);
};

watch(suffrage, () => {
    nextTick(() => {
        if(suffrage.value){
            addDropzone();
        }
    })
})

// ------- Fin de la gestion des dropzones -------

onBeforeUnmount(() => {
    document.removeEventListener('DOMContentLoaded', () => {});
})

</script>
