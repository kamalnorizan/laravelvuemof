<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PostForm from './PostForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: '',
    content: '',
    id: ''
})

const submit = () => {
    form.post(route('posts.store'), {
        onSuccess: () => { form.reset(); create = true; loadData(); }
    })
}

let create = ref(true);

</script>

<template>
<AuthenticatedLayout>
    <template #header>
        Post Details
    </template>

    <PostForm :submit="submit" :form="form" :create="create" />

</AuthenticatedLayout>
</template>
