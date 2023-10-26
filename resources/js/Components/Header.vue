<script setup>
import { Link } from '@inertiajs/vue3';
import { CLIENT, FREELANCER, ADMIN} from '../constants'
const store = useStore();

const auth = computed(() => store.state.authenticated);
const user = computed(() => store.state.user);

const links = computed(() => {
    const links = [];
    if (auth.value) {
        if (user.value.role == FREELANCER) {
            links.push({ name: "Jobs", href: "/jobs" });
        } else if (user.value.role == CLIENT) {
            links.push({ name: "Available Freelancers", href: "/freelancers" }, { name: 'Hired Freelancers', href: '/hired-freelancers' });
        } else if (user.value.role == ADMIN) {
            links.push({ name: "Jobs", href: "/confirm-jobs" }, { name: "Users", href: "/users" }, { name: "Freelancers", href: "/freelancers"});
        }
    }
    return links;
});

</script>
<template>
    <nav class="relative px-4 py-4 flex justify-between items-center" style="background-color: #6690bd;">
        <Link href="/" class="md:ml-20 lg:ml-64"><a class="text-3xl font-bold leading-none">FLM</a></Link>
        <div>
            <div class="lg:hidden">
                <button @click="toggleSidebar()" class="flex items-center text-blue-600 p-3">
                    <svg class="block h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="https://www.w3.org/2000/svg">
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                    </svg>
                </button>
            </div>

            <ul
                class="hidden absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 lg:mx-auto lg:flex lg:items-center lg:w-auto lg:space-x-6">
                <li v-for="link in links" v-cloak>
                    <Link class="mb-1 text-sm text-white" :href="link['href']">{{ link['name'] }}</Link>
                </li>
            </ul>

            <div class="md:mr-20 lg:mr-48">
                <div class="hidden lg:flex" v-cloak v-if="!auth">
                    <Link href="/login"
                        class="lg:inline-block lg:ml-auto lg:mr-3 py-2 px-6 bg-blue-400 hover:bg-blue-400 text-sm text-gray-900 font-bold rounded-xl transition duration-200">
                    Login</Link>
                </div>
                <div class="hidden lg:flex" v-cloak v-if="auth">
                    <Dropdown></Dropdown>
                </div>
                <Sidebar :isVisible="isHidden" @toggle-sidebar="toggleSidebar()" :links="links" />
            </div>
        </div>
    </nav>
</template>
<script>
import Sidebar from "./Sidebar.vue";
import Dropdown from "./Dropdown.vue";
import { computed } from 'vue';
import { useStore } from 'vuex';

export default {
    data() {
        return {
            isHidden: true,
        }
    },
    methods: {
        toggleSidebar() {
            this.isHidden = !this.isHidden
            document.getElementById('app').style = 'overflow: hidden';
        }
    },
    components: {
        Sidebar,
        Dropdown
    },
}
</script>
<style>
[aria-current="page"] {
    font-weight: bold;
}
[v-cloak] > * {
    display: none;
}
</style>