<template>
    <Head title="Разделы" />
    <div class="flex flex-col min-h-screen">
        <!-- HEADER -->
        <Header />

        <!-- 🔹 Блок категорий -->
        <section class="w-full bg-white py-8">
            <div class="max-w-[1000px] mx-auto px-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6 justify-items-center overflow-hidden">
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="`/category/${cat.slug}`"
                        :class="[
                            'flex flex-col items-center text-center transition-transform p-2 rounded-lg',
                            currentCategory && currentCategory.id === cat.id
                                ? 'bg-blue-50 border border-blue-200 scale-105'
                                : 'hover:scale-105 hover:bg-gray-50'
                        ]"
                    >
                        <img
                            :src="cat.photo ? `/storage/${cat.photo}` : '/img/no-image.png'"
                            alt="category icon"
                            class="w-[100px] h-[100px] object-cover rounded-lg mb-2 border  shadow-sm"
                        />
                        <span class="text-sm font-medium text-gray-800">{{ cat.title }}</span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- MAIN CONTENT -->
        <main class="w-full px-6 mx-auto max-w-[1000px] py-8">
            <!-- Продукты -->
            <div v-if="products && products.length > 0">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">
                    {{ currentCategory.title }}
                </h2>

                <div class="grid grid-cols-1 gap-8">
                    <Link
                        v-for="product in products"
                        :key="product.id"
                        :href="`/product/${product.slug}`"
                        class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow block"
                    >
                        <div class="flex flex-col md:flex-row">
                            <!-- Картинка - 25% ширины -->
                            <div class="md:w-1/4">
                                <img
                                    :src="product.photo ? `/storage/${product.photo}` : '/img/no-image.png'"
                                    :alt="product.title"
                                    class="w-full h-38 md:h-full object-cover rounded-t-lg md:rounded-l-lg md:rounded-tr-none"
                                />
                            </div>

                            <!-- Контент - 75% ширины -->
                            <div class="md:w-3/4 p-6 flex flex-col justify-center">
                                <h3 class="font-semibold text-xl text-gray-800 mb-3">{{ product.title }}</h3>




                            </div>
                        </div>
                    </Link>
                </div>


            </div>

            <!-- Пустое состояние -->
            <div v-else class="text-center py-8">
                <p class="text-gray-500" v-if="currentCategory">
                    В категории "{{ currentCategory.title }}" пока нет продуктов
                </p>
                <p class="text-gray-500" v-else>
                    Выберите категорию, чтобы увидеть продукты
                </p>
            </div>
        </main>

        <!-- FOOTER -->
        <Footer />
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'

defineProps({
    categories: Array,
    products: {
        type: Array,
        default: () => []
    },
    currentCategory: {
        type: Object,
        default: null
    },
})
</script>
