<template>
    <Head :title="product.title" />
    <div class="flex flex-col min-h-screen">
        <!-- HEADER -->
        <Header />
        <!-- Хлебные крошки -->
        <nav class="bg-gray-100 py-4">
            <div class="max-w-[1000px] mx-auto px-6">
                <div class="flex items-center space-x-2 text-sm">
                    <Link href="/cat" class="text-gray-500 hover:text-gray-700">Категории</Link>
                    <span class="text-gray-400">/</span>
                    <Link :href="`/category/${product.category.slug}`" class="text-gray-500 hover:text-gray-700">
                        {{ product.category.title }}
                    </Link>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-800 font-medium">{{ product.title }}</span>
                </div>
            </div>
        </nav>


        <!-- Детали продукта -->
        <main class="w-full px-6 mx-auto max-w-[1000px] py-8">
            <h1 class="text-4xl py-4 md:text-5xl font-bold mb-6 text-center bg-clip-text text-transparent
                    bg-gradient-to-r from-blue-500 to-purple-600">

                {{ product.title }}
            </h1>


            <div class="product-description max-w-full mb-6" v-html="product.description"></div>

            <!-- Новые блоки контента -->
            <div v-if="product.content_blocks">
                <div
                    v-for="(block, index) in product.content_blocks"
                    :key="index"
                    class="mb-6"
                >
                    <!-- Текстовый блок -->
                    <div v-if="block.type === 'text'" class="text-block" v-html="sanitizeBlock(block.description)"></div>

                    <!-- Фото блок -->
                    <img
                        v-if="block.type === 'image' && block.image"
                        :src="`/storage/${block.image}`"
                        class="mx-auto my-4 rounded-lg shadow-sm"
                        style="max-width: 100%; height: auto;"
                    />

                    <!-- Кнопка с чекбоксами и модальным окном -->
                    <div v-if="block.type === 'button'" class="flex flex-col items-center my-6 space-y-4">

                        <!-- Чекбоксы согласий -->
                        <div class="space-y-2 text-sm text-gray-700">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" v-model="consentPrivacy" />
                                <span>Я даю согласие на обработку данных в соответствии с <Link href="/privacy" class="text-blue-600 underline">Политикой конфиденциальности</Link></span>
                            </label>

                            <label class="flex items-center space-x-2">
                                <input type="checkbox" v-model="consentOferta" />
                                <span>Я принимаю условия <Link href="/oferta" class="text-blue-600 underline">Договора оферты</Link></span>
                            </label>
                        </div>

                        <!-- Кнопка -->
                        <button
                            :disabled="!consentPrivacy || !consentOferta"
                            @click="openModal"
                            class="inline-block w-full sm:w-auto px-10 py-3 text-lg font-semibold text-white text-center rounded-2xl shadow-md transition-all duration-300"
                            :class="{
                                      'bg-gray-400 cursor-not-allowed': !consentPrivacy || !consentOferta,
                                      'bg-blue-600 hover:bg-blue-700 hover:shadow-lg active:scale-95': consentPrivacy && consentOferta
                                    }"
                        >
                            {{ block.button_text }}
                        </button>
                    </div>
                </div>


            </div>

        </main>

        <!-- FOOTER -->
        <Footer />
    </div>
    <!-- Модальное окно -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-lg w-96 relative">
            <button @click="closeModal" class="absolute top-2 right-3 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-xl font-semibold mb-4 text-center">Введите данные для оплаты</h2>

            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Введите имя"
                    />
                    <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                    <input
                        v-model="form.phone"
                        type="tel"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="+7 (___) ___-__-__"
                    />
                    <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</p>
                </div>

                <button
                    type="submit"
                    class="w-full py-2 text-white bg-blue-600 rounded-xl font-semibold hover:bg-blue-700 transition"
                >
                    Оплатить
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
import { onMounted, onBeforeUnmount, ref } from "vue";
import axios from 'axios'

// --- Получаем пропсы от Laravel через Inertia ---
const props = defineProps({
    product: Object
})

// --- Согласия и модалка ---
const consentPrivacy = ref(false)
const consentOferta = ref(false)
const showModal = ref(false)

// --- Форма ---
const form = ref({
    name: '',
    phone: '',
    product: '',
    amount: 0
})

const errors = ref({
    name: '',
    phone: ''
})

// --- Открытие модалки ---
const openModal = () => {
    showModal.value = true
    form.value.product = props.product.title
    form.value.amount = props.product.price ?? 1000
}

// --- Закрытие модалки ---
const closeModal = () => {
    showModal.value = false
    form.value = { name: '', phone: '', product: '', amount: 0 }
    errors.value = { name: '', phone: '' }
}

// --- Отправка формы ---
const submitForm = async () => {
    errors.value = {}

    if (!form.value.name.trim()) {
        errors.value.name = 'Введите имя'
    }

    const phonePattern = /^\+?\d[\d\s\-\(\)]{9,}$/
    if (!phonePattern.test(form.value.phone)) {
        errors.value.phone = 'Введите корректный номер телефона'
    }

    // Если всё ок → отправляем POST на Laravel
    if (Object.keys(errors.value).length === 0) {
        try {
            const { data } = await axios.post('/pay', form.value)
            // Laravel должен вернуть { url: 'https://auth.robokassa.ru/...' }
            window.location.href = data.url
        } catch (e) {
            errors.value = e.response?.data?.errors || {}
        }
    }
}

// --- sanitizeBlock для описаний ---
const sanitizeBlock = (html) => {
    if (!html) return ''
    // фиксим пути /storage
    return html.replace(/src="\/\/storage\//g, 'src="/storage/')
}

/******************** Эффект "паутины" ********************/
let web = null
let scrollHandler = null
let mouseMoveHandler = null

const handleScroll = () => {
    if (!web) return
    const scrollOffset = window.scrollY * 0.2
    web.style.transform = `translateY(${scrollOffset}px)`
}

const handleMouseMove = (e) => {
    if (!web) return
    const { innerWidth, innerHeight } = window
    const x = (e.clientX / innerWidth - 0.5) * 20
    const y = (e.clientY / innerHeight - 0.5) * 20
    web.style.transform = `translate(${x}px, ${y + window.scrollY * 0.2}px)`
}

onMounted(() => {
    const existingWebs = document.querySelectorAll('.web-bg')
    existingWebs.forEach(el => el.parentNode?.removeChild(el))

    web = document.createElement('div')
    web.classList.add('web-bg')
    document.body.appendChild(web)

    scrollHandler = handleScroll
    mouseMoveHandler = handleMouseMove

    window.addEventListener('scroll', scrollHandler)
    window.addEventListener('mousemove', mouseMoveHandler)
})

onBeforeUnmount(() => {
    if (web && web.parentNode) {
        web.parentNode.removeChild(web)
        web = null
    }
    if (scrollHandler) window.removeEventListener('scroll', scrollHandler)
    if (mouseMoveHandler) window.removeEventListener('mousemove', mouseMoveHandler)
})
</script>




<style >

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



/* Фоновая паутина */
/*body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: url("/img/logo2.png") center top / cover no-repeat fixed;
    opacity: 0.05; /
    z-index: -1;
    pointer-events: none;
}*/



/* Фоновая паутина */
.web-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: url("/img/logo2.png") center center / cover no-repeat;
    opacity: 0.03; /* еле заметно */
    z-index: -1; /* под всем контентом */
    pointer-events: none;
    transition: transform 0.2s ease-out;
    will-change: transform;
}
/* Фоновая паутина */



/* Минималистичные скруглённые чекбоксы */
input[type="checkbox"] {
    width: 20px;
    height: 20px;
    border-radius: 6px; /* 👈 делает углы мягкими */
    /*border: 2px solid #9ca3af; *//* серый контур */
    appearance: none; /* убираем стандартный стиль браузера */
    -webkit-appearance: none;
    outline: none;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

input[type="checkbox"]:checked {
    /* background-color: #2563eb;*/ /* синий при выборе */
    /*border-color: #2563eb;*/
}

input[type="checkbox"]:checked::after {
    content: "✓";
    color: white;
    font-size: 14px;
    position: absolute;
    top: 0;
    left: 3px;
}

/* Минималистичные скруглённые чекбоксы */

</style>
