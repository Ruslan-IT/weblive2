<!-- resources/js/Pages/Product/Category.vue -->



<!-- resources/js/Pages/Product/Category.vue -->

<template>
    <div class="flex flex-col min-h-screen">
        <!-- HEADER -->
        <header class="py-2 border-b bg-white">
            <div class="w-full px-6 mx-auto max-w-[1000px]">
                <div class="flex items-center justify-between h-12">
                    <a href="/" class="flex items-center gap-x-2">
                        <img width="30" src="/img/logo.png" alt="Webway Live">
                    </a>

                    <div class="text-2xl font-extrabold tracking-tight text-black">
                        Webway Live
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="w-full px-6 mx-auto max-w-[1000px]">
            <div
                v-for="product in products"
                :key="product.id"
                class="product-card mb-10  pb-8"
            >
                <div class="product-info">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center bg-clip-text text-transparent
                    bg-gradient-to-r from-blue-500 to-purple-600">

                        {{ product.title }}
                    </h1>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6 text-center">
                        {{ product.title }}
                    </h1>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 drop-shadow-lg mb-6 text-center">
                        {{ product.title }}
                    </h1>

                    <div class="text-center mb-10">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                            {{ product.title }}
                        </h1>
                        <p class="text-lg md:text-xl text-gray-600 mt-4">
                            Краткое описание или слоган продукта
                        </p>
                    </div>


                    <div class="product-description max-w-full mb-6" v-html="product.description"></div>

                    <!-- Новые блоки контента -->
                    <div v-if="product.content_blocks">
                        <div
                            v-for="(block, index) in product.content_blocks"
                            :key="index"
                            class="mb-6"
                        >
                            <!-- Текстовый блок -->
                            <div v-if="block.type === 'text'" class="text-block" v-html="block.description"></div>

                            <!-- Фото блок -->
                            <img
                                v-if="block.type === 'image' && block.image"
                                :src="`/storage/${block.image}`"
                                class="mx-auto my-4 rounded-lg shadow-sm"
                                style="max-width: 100%; height: auto;"
                            />

                            <!-- Кнопка -->
                            <a
                                v-if="block.type === 'button'"
                                :href="block.button_url"
                                class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                {{ block.button_text }}
                            </a>
                        </div>
                    </div>

                    <!-- Цена -->
                    <div class="product-prices mt-4">
                        <span class="current-price text-xl font-bold text-gray-800">
                            {{ product.price }} ₽
                        </span>
                    </div>
                </div>
            </div>

            <!-- Сообщение при отсутствии товаров -->
            <div
                v-if="products.length === 0"
                class="text-center text-gray-500 py-10"
            >
                В этой категории пока нет товаров
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="border-t mt-auto py-6 bg-white">
            <div class="w-full px-6 mx-auto max-w-[1400px] flex flex-col md:flex-row items-center md:items-start justify-between text-sm text-gray-700">

                <!-- Левая колонка -->
                <div class="text-left mb-4 md:mb-0">
                    <p class="font-semibold uppercase">ИП БРАТИШКА ЮЛИЯ НИКОЛАЕВНА</p>
                    <p>ИНН: 366232923373</p>
                    <p>ОГРНИП: 324366800005942</p>
                </div>

                <!-- Центр -->
                <div class="text-center mb-4 md:mb-0 flex flex-col items-center">
                    <img src="/img/logo.png" alt="Логотип" class="w-10 h-10 mb-2 opacity-90" />
                    <p>© 2022–2025 Все права защищены</p>
                </div>

                <!-- Правая колонка -->
                <div class="text-right">
                    <Link
                        href="/oferta"
                        class="text-blue-600 hover:text-blue-800 underline transition"
                        @mouseenter="preloadOferta"
                    >
                        Публичная оферта
                    </Link>

                </div>
            </div>
        </footer>
    </div>
</template>





<script setup>


import { Link } from '@inertiajs/vue3'

defineProps({
    category: Object,
    products: Object,
})


// Предзагрузка страницы оферты при наведении
const preloadOferta = () => {
    router.preload('/oferta')
}

</script>



<style scoped>

.product-card {
    transition: all 0.3s ease;
}
.product-card:hover {
    transform: translateY(-2px);
}

.product-card {
    transition: transform 0.3s ease;
    will-change: transform;
}


</style>
