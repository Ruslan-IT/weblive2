<template>
    <div class="flex flex-col min-h-screen">
        <Header />

        <main class="flex flex-col items-center justify-center flex-1 text-center px-6 py-16">
            <h1 class="text-4xl md:text-5xl font-bold text-green-600 mb-6">
                ✅ Оплата прошла успешно!
            </h1>
            <p class="text-gray-600 text-lg mb-8">
                Спасибо за ваш заказ. Мы свяжемся с вами в ближайшее время.
            </p>
            <Link
                href="/"
                class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition"
            >
                На главную
            </Link>
        </main>

        <Footer />
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
import { Link } from '@inertiajs/vue3'
</script>
