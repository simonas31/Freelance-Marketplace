<script setup>
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { initFlowbite } from "flowbite";
import { useStore } from 'vuex';
import { computed } from 'vue';
import { CLIENT, FREELANCER, ADMIN} from '../constants'
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

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
    <button data-dropdown-toggle="dropdownDivider"
        class="text-black bg-blue-400 hover:bg-blue-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center"
        type="button">{{ user?.name + " " + user?.surname }}<svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true"
            xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div id="dropdownDivider" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
            <li v-show="role == FREELANCER">
                <Link href="/portfolio" class="block px-4 py-2 hover:bg-gray-100 hover:cursor-pointer">Portfolio</Link>
            </li>
            <li>
                <Link href="/profile" class="block px-4 py-2 hover:bg-gray-100 hover:cursor-pointer">Profile</Link>
                <Link href="/chats" class="block px-4 py-2 hover:bg-gray-100 hover:cursor-pointer">Your Chats</Link>
                <Link href="/transactions" class="block px-4 py-2 hover:bg-gray-100 hover:cursor-pointer">Transactions</Link>
            </li>
            <li v-show="role == CLIENT">
                <Link href="/create-job" class="block px-4 py-2 hover:bg-gray-100 hover:cursor-pointer">Create Job</Link>
                <Link href="/your-jobs" class="block px-4 py-2 hover:bg-gray-100 hover:cursor-pointer">Your Jobs</Link>
            </li>
        </ul>
        <div class="py-2">
            <a @click="logout()"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:cursor-pointer">Logout</a>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        initFlowbite();
    }
}
</script>