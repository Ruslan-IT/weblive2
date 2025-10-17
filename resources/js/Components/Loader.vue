<template>
    <div class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="flex space-x-4 animate-bounce">
            <img src="/img/logo.png" class="w-12 h-12" alt="Icon 1" />
            <img src="/img/logo2.png" class="w-12 h-12" alt="Icon 2" />
        </div>
    </div>
</template>

<script setup>
</script>

<style scoped>
.animate-bounce > * {
    animation: bounce 1s infinite alternate;
}

@keyframes bounce {
    0% { transform: translateY(0); }
    100% { transform: translateY(-15px); }
}
</style>
