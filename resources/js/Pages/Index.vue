<!-- resources/js/Pages/Product/Category.vue -->



<!-- resources/js/Pages/Product/Category.vue -->

<template>
    <Head title="Главная" />
    <div class="flex flex-col min-h-screen">
        <!-- HEADER -->
        <Header />

        <!-- MAIN -->
        <main class="w-full px-6 mx-auto max-w-[1000px]">
            1111
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
