<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps({
    posts: Array
})
</script>
<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Posts
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="border p-4 mb-2 rounded" v-for="post in posts" :key="post.id">
                            <h2>{{ post.title }}</h2>
                            <h2>{{ post.body }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
