<script setup>
import { ref, computed } from 'vue';
import { onClickOutside } from '@vueuse/core';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { CLIENT, FREELANCER, ADMIN} from '../constants'
import { useStore } from 'vuex';
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

const { isVisible } = defineProps(['isVisible', 'links']);
const links = computed(() => {
    const links = [];
    if (Object.keys(user.value).length != 0) {
        if (user.value.role == FREELANCER) {
            links.push({ name: "Jobs", href: "/jobs" }, { name: "Portfolio", href: "/portfolio" }, { name: "Profile", href: "/profile" }, { name: "Completed Transactions", href: "/transactions" }, { name: "Chats", href: "/chats" });
        } else if (user.value.role == CLIENT) {
            links.push({ name: "Applied Freelancers", href: "/applied-freelancers" },
                    { name: 'Hired Freelancers', href: '/hired-freelancers' },
                    { name: "Completed Transactions", href: "/transactions" },
                    { name: "Create Job", href: "/create-job" },
                    { name: "Your Jobs", href: "/your-jobs" },
                    { name: "Chats", href: "/chats" });
        } else if (user.value.role == ADMIN) {
            links.push({ name: "Jobs", href: "/confirm-jobs" }, { name: "Users", href: "/users" });
        }
    }
    return links;
});

const isHidden = ref(false)
const isHiddenRef = ref(null)
onClickOutside(isHiddenRef, (event) => { isHidden.value = true; })

const logout = async () => {
    try {
        const response = await axios.get('/api/logout');

        localStorage.clear();
        location.reload();

        await store.dispatch('setAuth', false);
        await store.dispatch('setUser', {});
    } catch (e) {
        await store.dispatch('setAuth', false);
        await store.dispatch('setUser', {});
    }
}
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
                            xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <div v-if="Object.keys(user).length == 0" class="mt-auto">
                    <div class="pt-6" @click="toggleSidebarSignal()">
                        <Link href="/login" class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold bg-blue-400 rounded-xl">Login in</Link>
                    </div>
                </div>
                <div v-else class="mt-auto">
                    <div class="pt-6" @click="toggleSidebarSignal()">
                        <a @click="logout" class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold bg-blue-400 rounded-xl hover:bg-blue-500 cursor-pointer">Logout</a>
                    </div>
                </div>
            </nav>
        </transition>
    </div>
</template>
<script>
export default {
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