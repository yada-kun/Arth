<script setup>
import { Field } from "vee-validate";
import { cva } from "class-variance-authority";

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    intent: {
        type: String,
        default: "", // Optional default value for custom classes
    },
    size: {
        type: String,
        default: "",
    },
});

const button = cva(["font-semibold", "border", "rounded", "uppercase"], {
    variants: {
        intent: {
            primary: [
                "bg-blue-500",
                "text-white",
                "border-transparent",
                "hover:bg-blue-600",
            ],
            // **or**
            // primary: "bg-blue-500 text-white border-transparent hover:bg-blue-600",
            secondary: [
                "bg-white",
                "text-gray-800",
                "border-gray-400",
                "hover:bg-gray-100",
            ],
            danger: [
                "bg-red-500",
                "text-white",
                "border-transparent",
                "hover:bg-gray-100",
            ],
        },
        size: {
            small: ["text-sm", "py-1", "px-2"],
            medium: ["text-base", "py-2", "px-4"],
        },
    },
    compoundVariants: [
        {
            intent: "primary",
            size: "medium",
            class: "uppercase",
            // **or** if you're a React.js user, `className` may feel more consistent:
            // className: "uppercase"
        },
    ],
    defaultVariants: {
        intent: "primary",
        size: "medium",
    },
});
</script>

<template>
    <button :type="type" :class="button({ intent, size })"><slot /></button>
</template>
