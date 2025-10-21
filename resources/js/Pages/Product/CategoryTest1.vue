<!-- resources/js/Pages/Product/Category.vue -->



<!-- resources/js/Pages/Product/Category.vue -->

<template>
    <Head title="Разделы" />
    <div class="flex flex-col min-h-screen">
        <!-- HEADER -->
        <Header />

        <!-- MAIN -->
        <main class="w-full px-6 mx-auto max-w-[1000px]">
            <div
                v-for="product in products"
                :key="product.id"
                class="product-card mb-10  pb-8"
            >
                <div class="product-info">
                    <h1 class="text-4xl py-4 md:text-5xl font-bold mb-6 text-center bg-clip-text text-transparent
                    bg-gradient-to-r from-blue-500 to-purple-600">

                        {{ product.title }}
                    </h1>
<!--                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6 text-center">
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
                    </div>-->


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

                </div>
            </div>

            <!-- Сообщение при отсутствии товаров -->
            <div
                v-if="products.length === 0"
                class="text-center text-gray-500 py-10"
            >
                В этой категории пока нет страниц
            </div>
        </main>

        <!-- FOOTER -->
        <Footer />


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
    </div>
</template>





<script setup>

import { ref } from 'vue'
import { onMounted, onBeforeUnmount } from 'vue'
import {Head, Link} from '@inertiajs/vue3'

import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'




const consentPrivacy = ref(false)
const consentOferta = ref(false)
const showModal = ref(false)




/***************** Валидация формы **********/

const form = ref({
    name: '',
    phone: ''
})

const errors = ref({
    name: '',
    phone: ''
})

const openModal = () => {
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    form.value = { name: '', phone: '' }
    errors.value = { name: '', phone: '' }
}

const submitForm = () => {
    errors.value = { name: '', phone: '' }

    if (!form.value.name.trim()) {
        errors.value.name = 'Введите имя'
    }

    const phonePattern = /^\+?\d[\d\s\-\(\)]{9,}$/
    if (!phonePattern.test(form.value.phone)) {
        errors.value.phone = 'Введите корректный номер телефона'
    }

    if (!errors.value.name && !errors.value.phone) {
        /*alert(`✅ Имя: ${form.value.name}\n📞 Телефон: ${form.value.phone}\n(тут будет логика оплаты)`)*/
        closeModal()
    }
}

/***************** Валидация формы **********/

/********************Паутина движение **************/



onMounted(() => {
    // элемент с паутиной
    const web = document.createElement('div')
    web.classList.add('web-bg')
    document.body.appendChild(web)

    // Эффект движения при скролле
    const handleScroll = () => {
        const scrollOffset = window.scrollY * 0.2 // медленное движение при прокрутке
        web.style.transform = `translateY(${scrollOffset}px)`
    }

    // Эффект параллакса от мыши
    const handleMouseMove = (e) => {
        const { innerWidth, innerHeight } = window
        const x = (e.clientX / innerWidth - 0.5) * 20 // -10 ... +10 px
        const y = (e.clientY / innerHeight - 0.5) * 20
        web.style.transform = `translate(${x}px, ${y + window.scrollY * 0.2}px)`
    }

    window.addEventListener('scroll', handleScroll)
    window.addEventListener('mousemove', handleMouseMove)

    onBeforeUnmount(() => {
        window.removeEventListener('scroll', handleScroll)
        window.removeEventListener('mousemove', handleMouseMove)
    })
})

/*******************Паутина движение**************** */



defineProps({
    category: Object,
    products: Object,
})


// Предзагрузка страницы оферты при наведении
const preloadOferta = () => {
    router.preload('/oferta')
}

const sanitizeBlock = (html) => {
    if (!html) return ''
    // Заменяем все //storage/... на /storage/...
    return html.replace(/src="\/\/storage\//g, 'src="/storage/')
}


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
