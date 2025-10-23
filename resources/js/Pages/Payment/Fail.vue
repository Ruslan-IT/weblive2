<template>
    <div class="flex flex-col min-h-screen">
        <Header />

        <main class="flex flex-col items-center justify-center flex-1 text-center px-6 py-16">
            <h1 class="text-4xl md:text-5xl font-bold text-red-600 mb-6">
                ❌ Оплата не выполнена
            </h1>
            <p class="text-gray-600 text-lg mb-8">
                К сожалению, платеж не был завершён. Попробуйте снова или свяжитесь с нами.
            </p>
            <div class="flex space-x-4">
                <Link
                    href="/"
                    class="bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition"
                >
                    На главную
                </Link>
                <Link
                    href="/contact"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition"
                >
                    Связаться с нами
                </Link>
            </div>
        </main>

        <Footer />
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
import { Link } from '@inertiajs/vue3'
</script>
