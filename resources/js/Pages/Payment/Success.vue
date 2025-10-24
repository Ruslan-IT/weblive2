<template>
    <div class="flex flex-col min-h-screen bg-gray-50">
        <Header />

        <main class="flex flex-col items-center justify-center flex-1 px-6 py-16">
            <div
                class="bg-white shadow-xl rounded-2xl p-8 max-w-lg w-full text-center transform transition-all duration-300 hover:shadow-2xl"
            >
                <div class="flex justify-center mb-6">
                    <div
                        class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center animate-bounce"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 text-green-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-green-600 mb-3">
                    Оплата прошла успешно!
                </h1>
                <p class="text-gray-600 mb-8">
                    Спасибо, {{ order.name }} 🎉
                    Ваш заказ №{{ order.id }} на сумму
                    <span class="font-semibold text-gray-800">{{ order.amount }} ₽</span>
                    успешно оплачен.
                </p>

                <div class="bg-gray-100 p-4 rounded-xl text-left text-sm text-gray-700 mb-6">
                    <p><strong>Товар:</strong> {{ order.product }}</p>
                    <p><strong>Телефон:</strong> {{ order.phone }}</p>
                    <p><strong>Дата оплаты:</strong> {{ formatDate(order.paid_at) }}</p>
                </div>

                <Link
                    href="/cat"
                    class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition"
                >
                    На главную
                </Link>
            </div>
        </main>

        <Footer />
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
import { Link, usePage } from '@inertiajs/vue3'

// Получаем данные заказа, которые ты передаёшь из контроллера:
const order = usePage().props.order

// Форматирование даты
const formatDate = (dateStr) => {
    if (!dateStr) return '—'
    const d = new Date(dateStr)
    return d.toLocaleString('ru-RU', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}
</script>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.6s ease forwards;
}
</style>
