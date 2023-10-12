<script setup>
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { Link } from '@inertiajs/vue3';

const isHidden = ref(false)
const isHiddenRef = ref(null)
onClickOutside(isHiddenRef, (event) => { isHidden.value = true; })
</script>
<template>
    <div id="sidebar-menu" class="relative z-50">
        <transition name="sidebarBackground">
            <div v-if="!isVisible" class="fixed inset-0 bg-gray-800 opacity-25" id="sidebarBackgroundId" @click="closeSidebar($event)"></div>
        </transition>

        <transition name="sidebar">
            <nav v-if="!isVisible" ref="isHiddenRef"
                class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-6 px-6 bg-white border-r overflow-y-auto">
                <div class="flex items-center mb-8">
                    <div class="mx-auto text-3xl font-bold leading-none">
                        FLM
                    </div>
                    <button v-on:click="toggleSidebarSignal()" class="navbar-close">
                        <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div>
                    <ul v-for="link in links" @click="toggleSidebarSignal()">
                        <li class="mb-1">
                            <Link :href="link['href']"
                                class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-200 hover:text-blue-600 rounded">{{ link['name'] }}</Link>
                        </li>
                    </ul>
                </div>

                <div class="mt-auto">
                    <div class="pt-6" @click="toggleSidebarSignal()">
                        <Link href="/login" class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold bg-blue-400 rounded-xl">Login in</Link>
                    </div>
                </div>
            </nav>
        </transition>
    </div>
</template>
<script>
export default {
    props: ['isVisible'],
    data() {
        return {
            links: [],
        }
    },
    mounted() {
        this.links = [
            { name: "Home", href: "/" },
        ]
    },
    methods: {
        toggleSidebarSignal() {
            this.$emit('toggleSidebar');
            this.enableScroll();
        },
        closeSidebar(e) {
            if (e.target.id === 'sidebarBackgroundId'){
                this.$emit('toggleSidebar');
                this.enableScroll();
            }
        },
        enableScroll(){
            document.getElementById('app').style = '';
        }
    },
}
</script>
<style>
.sidebar-enter-active {
    animation: slide-sidebar .5s ease;
}

.sidebar-leave-active {
    animation: slide-sidebar .5s ease reverse;
}

.sidebarBackground-enter-active,
.sidebarBackground-leave-active {
    transition: opacity .5s;
}

.sidebarBackground-enter-from,
.sidebarBackground-leave-to {
    opacity: 0;
}

@keyframes sidebarBackground-opacity {
    from {
        opacity: 0;
    }

    to {
        opacity: 0;
    }
}

@keyframes slide-sidebar {
    from {
        transform: translateX(-100%);
    }

    to {
        transform: translateX(0);
    }
}</style>