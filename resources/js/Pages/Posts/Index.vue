<template>

    <h1>Posts</h1>
    <div class="border p-4 mb-2 rounded" v-for="post in posts" :key="post.id">
        <h2>{{ post.title }}</h2>
        <h2>{{ post.body }}</h2>
    </div>
</template>
<script setup>
defineProps({
    posts:Array
})
</script>
