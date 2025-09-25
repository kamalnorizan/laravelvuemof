<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import EasyDataTable from 'vue3-easy-data-table'

defineProps({
    posts: Array
})

const headers = [
    { text: 'ID', value: 'id' },
    { text: 'Title', value: 'title' },
    { text: 'Created By', value: 'created_by' },
    { text: 'Actions', value: 'actions' },
];

const form = useForm({
    title: '',
    content: ''
})


const submit = () => {
    form.post(route('posts.store'), {
        onSuccess: () => form.reset()
    })
}

const items = ref([]);
const totalRows = ref(0);
const serverOptions = ref({
    page: 1,
    rowsPerPage: 10
});

const loadData = async () => {
    const { data } = await axios.get(route('posts.data'), {
        params: {
            start: (serverOptions.value.page - 1) * serverOptions.value.rowsPerPage,
            length: serverOptions.value.rowsPerPage,
        }
    })
    items.value = data.data
    totalRows.value = data.recordsTotal
}

onMounted(loadData);

</script>
<template>

    <Head title="Posts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Posts {{ $page.props.auth.user.name }}
            </h2>
        </template>
        <template #actions>

        </template>
        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="$page.props.flash.success" class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                            {{ $page.props.flash.success }}
                        </div>
                        <div v-if="$page.props.flash.errors" class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                            {{ $page.props.flash.errors }}
                        </div>
                        <form @submit.prevent="submit">
                            <input v-model="form.title" type="text" name="title" class="border rounded p2 w-full mb-2"
                                placeholder="Title">
                            <div v-if="form.errors.title" class="text-red-600">{{ form.errors.title }}</div>

                            <textarea v-model="form.content" name="content" placeholder="Content"
                                class="border rounded p2 w-full mb-2"></textarea>
                            <div v-if="form.errors.content" class="text-red-600">{{ form.errors.content }}</div>

                            <button type="submit"
                                class="ml-auto bg-blue-600 text-white py-2 px-4 rounded mb-3">Create</button>
                        </form>

                        <EasyDataTable
                        :headers="headers"
                        :items="items"
                        v-model:server-options="serverOptions"
                        @update:server-options="loadData" :server-items-length="totalRows"
                        alternatin
                        border-cell></EasyDataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
@import "vue3-easy-data-table/dist/style.css";
</style>
