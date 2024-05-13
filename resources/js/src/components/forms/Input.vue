<script setup>
import { Field } from "vee-validate";

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    class: {
        type: String,
        default: "", // Optional default value for custom classes
    },
    size: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <Field :name="name" :type="type" :class="class" />
</template>
