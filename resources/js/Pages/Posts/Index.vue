<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import EasyDataTable from 'vue3-easy-data-table';
import Swal from 'sweetalert2';

defineProps({
    posts: Array
})

const headers = [
    { text: 'ID', value: 'id', name: 'id' },
    { text: 'Title', value: 'title', name: 'title', sortable: true, searchable: true },
    { text: 'Created By', value: 'created_by', name: 'user.name', sortable: true, searchable: true },
    { text: 'Actions', value: 'actions', name: '' },
];

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

const items = ref([]);
const totalRows = ref(0);
const search = ref('');
let create = ref(true);

const serverOptions = ref({
    page: 1,
    rowsPerPage: 10,
    sortBy: 'title',
    sortType: 'asc',
});
const loadData = async () => {
    const columns = headers.map(h => ({
        data: h.value,
        name: h.name,
        searchable: !!h.searchable,
        orderable: !!h.sortable,
        search: { value: '' }
    }));

    const order = serverOptions.value.sortBy == null ? 1 : headers.findIndex(h => h.value === serverOptions.value.sortBy);
    const type = serverOptions.value.sortBy == null ? 'asc' : serverOptions.value.sortType;

    const { data } = await axios.get(route('posts.data'), {
        params: {
            columns,
            start: (serverOptions.value.page - 1) * serverOptions.value.rowsPerPage,
            'search[value]': search.value,
            length: serverOptions.value.rowsPerPage,
            'columns[1][data]': 'title',
            'order[0][column]': order,
            'order[0][dir]': type,
        }
    })
    items.value = data.data
    totalRows.value = data.recordsTotal
}

onMounted(loadData);
watch(search, () => {
    serverOptions.value.page = 1;
    loadData();
});

function editPost(id) {
    const post = items.value.find(p => p.id === id);
    console.log(post);
    form.title = post.title;
    form.content = post.body;
    form.id = id;
    create = false;
}

function deletePost(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(route('posts.destroy', id)).then(() => {
                items.value = items.value.filter(p => p.id !== id);
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Your post has been deleted.',
                    icon: 'success',
                });

            });
        }
    });
}

</script>
<template>
    <Head title="Posts" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Posts
            </h2>
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


                            <input type="hidden" name="id" v-model="form.id">


                            <button type="submit" v-if="create"
                                class="ml-auto bg-blue-600 text-white py-2 px-4 rounded mb-3">Create</button>
                            <button type="submit" v-else
                                class="ml-auto bg-yellow-600 text-white py-2 px-4 rounded mb-3">Update</button>
                        </form>
                        <input v-model="search" type="text" placeholder="Search posts..."
                            class="border rounded p-2 w-full mb-4">
                        <EasyDataTable :headers="headers" :items="items" v-model:server-options="serverOptions"
                            @update:server-options="loadData" :server-items-length="totalRows"
                            :rows-items="[10, 25, 50, 100]" alternatin border-cell>
                            <template #item-actions="{ actions }">
                                <button @click="editPost(actions.id)"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                                &nbsp;<button @click="deletePost(actions.id)"
                                    class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
@import "vue3-easy-data-table/dist/style.css";
</style>
