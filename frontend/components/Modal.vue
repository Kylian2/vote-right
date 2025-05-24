<template>

<div @click="open = false" v-if="open" class="modal__wrapper">

    <div @click.stop class="modal" :class="{ 'fullscreen' : fullscreen, 'modal--error' : error }">

        <h3>
            <slot name="title"></slot>
        </h3>

        <h4 v-if="subtitles">
            <slot name="subtitles"></slot>
        </h4>

        <div class="modal__body">
            <slot name="body"></slot>
        </div>
        <div class="modal__actions">
            <button class="btn btn--cancel" v-if="cancelText"
            @click="() => {
                beforeCancel()
                beforeClose()
                hide()
            }"
            > {{ cancelText }}</button>
            <button class="btn btn--full"
            :disabled="disableValid"
            @click="() => {
                beforeOk()
                beforeClose()
                hide()
            }"
            > {{ okText }}</button>
        </div>

    </div>

</div>
    
</template>

<script setup>

const props = defineProps({
    okText: {
        type: String,
        required: false,
        default: 'Valider'
    },
    cancelText: {
        type: String,
        required: false
    },
    beforeOk: {
        type: Function,
        required: false,
        default: () => {},
    },
    beforeCancel: {
        type: Function,
        required: false,
        default: () => {},
    },
    beforeClose: {
        type: Function,
        required: false,
        default: () => {},
    },
    fullscreen:{
        type: Boolean,
        required: false,
        default: false
    },
    name: {
        type: String,
        required: true,
    },
    disableValid: {
        type: Boolean, 
        required: false,
        default: false,
    },
    subtitles: {
        type: Boolean,
        required: false,
        default: false,
    }, 
    error: {
        type: Boolean,
        required: false,
        default: false,
    }
})

const open = useState(`${props.name}Modal`, () => false);

const hide = () => {
    open.value = false;
}

</script>