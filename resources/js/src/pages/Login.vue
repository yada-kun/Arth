<script setup>
import { Form } from "vee-validate";
import { object, string } from "yup";
import Input from "../components/forms/Input.vue";
import ErrorMessage from "../components/forms/ErrorMessage.vue";
import Button from "../components/forms/Button.vue";

let userSchema = object({
    email: string().email().required(),
    password: string().required(),
});

function onSubmit(values) {
    alert(values.password);
}
</script>

<template>
    <div class="flex justify-center items-center w-screen h-screen">
        <div
            class="w-full md:w-[650px] space-y-5 py-10 bg-slate-100 p-4 rounded-md grid grid-cols-1 place-items-center"
        >
            <h1 class="text-slate-600 text-5xl">Arth</h1>
            <Form
                @submit="onSubmit"
                :validation-schema="userSchema"
                class="space-y-3"
            >
                <div class="flex flex-col space-y-5">
                    <div class="grid grid-cols-4 gap-4 items-center">
                        <label
                            for="email"
                            class="font-medium text-gray-700 text-lg col-span-1"
                        >
                            Email:
                        </label>
                        <div class="col-span-3">
                            <Input
                                name="email"
                                type="email"
                                class="rounded-md h-10 w-full border-2 border-gray-400 p-2"
                            />
                            <ErrorMessage name="email" />
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-4 items-center">
                        <label
                            for="password"
                            class="font-medium text-gray-700 text-lg col-span-1"
                        >
                            Password:
                        </label>
                        <div class="col-span-3">
                            <Input
                                name="password"
                                type="password"
                                class="rounded-md h-10 w-full border-2 border-gray-400 p-2"
                            />
                            <ErrorMessage name="password" />
                        </div>
                    </div>
                    <Button type="submit" intent="primary" size="medium">
                        Submit
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>
