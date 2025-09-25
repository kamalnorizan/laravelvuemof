<script setup>

const props = defineProps({
    submit: Function,
    form: Object,
    create: Boolean,
});

</script>

<template>
    <form @submit.prevent="submit">
        <input v-model="form.title" type="text" name="title" class="border rounded p2 w-full mb-2" placeholder="Title">
        <div v-if="form.errors.title" class="text-red-600">{{ form.errors.title }}</div>

        <textarea v-model="form.content" name="content" placeholder="Content"
            class="border rounded p2 w-full mb-2"></textarea>
        <div v-if="form.errors.content" class="text-red-600">{{ form.errors.content }}</div>


        <input type="hidden" name="id" v-model="form.id">


        <button type="submit" v-if="create"
            class="ml-auto bg-blue-600 text-white py-2 px-4 rounded mb-3">Create</button>
        <button type="submit" v-else class="ml-auto bg-yellow-600 text-white py-2 px-4 rounded mb-3">Update</button>
    </form>
</template>
