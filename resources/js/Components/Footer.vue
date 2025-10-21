<template>
    <footer class="border-t mt-auto py-6 bg-white">
        <div
            class="w-full px-6 mx-auto max-w-[1400px] flex flex-col md:flex-row items-center md:items-start justify-between text-sm text-gray-700"
        >
            <!-- Левая колонка -->
            <div class="text-left mb-4 md:mb-0">
                <p class="font-semibold uppercase">ИП БРАТИШКА ЮЛИЯ НИКОЛАЕВНА</p>
                <p>ИНН: 366232923373</p>
                <p>ОГРНИП: 324366800005942</p>
            </div>

            <!-- Центр -->
            <div class="text-center mb-4 md:mb-0 flex flex-col items-center">
                <img
                    src="/img/logo.png"
                    alt="Логотип"
                    class="w-10 h-10 mb-2 opacity-90"
                />
                <p>© 2022–2025 Все права защищены</p>
            </div>

            <!-- Правая колонка -->
            <div class="text-right">
                <Link
                    href="/oferta"
                    class="text-black hover:text-blue-800 underline transition"

                >
                    Публичная оферта
                </Link>
                <br />
                <Link
                    href="/privacy"
                    class="text-black hover:text-blue-800 underline transition"

                >
                    Политика конфиденциальности
                </Link>
            </div>
        </div>
    </footer>
</template>

<script setup >
import {Link} from "@inertiajs/vue3";



</script>
