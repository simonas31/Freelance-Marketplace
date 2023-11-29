<script setup>
import { CLIENT, FREELANCER, ADMIN} from '../constants';
import { router } from '@inertiajs/vue3';

const { users } = defineProps(['users']);

const roles = {'1': 'Client', '2': 'Freelancer', '200': 'Admin'};
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);
const message = ref('');

const usersRef = ref(users);

const remove = (user_id) => {
    axios.delete(`/api/users/${user_id}`)
    .then(response => {
        usersRef.value.remove()
        router.get('/users');
    });
};

const approve = (user_id, event) => {
    axios.patch(`/api/users/${user_id}`)
    .then(response => {
        event.target.hidden = true;
        message.value = response.data.message;
    });
};
</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <h2 class="text-center text-xl mt-4 uppercase text-green-500">{{ message }}</h2>
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">SYSTEM USERS</h1>
                </div>
                <div class="w-full overflow-x-auto mb-10">
                    <table class="table-auto mx-auto">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 bg-gray-200 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Operations</th>
                            </tr>
                        </thead>
                        <tbody v-for="(user, index) in users">
                            <tr class="odd:bg-white even:bg-slate-50 hover:bg-slate-100">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ user.name + " " + user.surname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ roles[user.role] }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ new Date(user.created_at).toLocaleDateString('en-us', { year:"numeric", month:"numeric", day:"numeric"})  }}</td>
                                <!-- add - remove button for each freelancer by admin -->
                                <td v-if="role == ADMIN" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                                    <button v-if="role == ADMIN && user.confirmed_registration == 0"
                                        @click="approve(user.id, $event)"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mr-4">
                                        Approve
                                    </button>
                                    <button v-if="role == CLIENT || role == ADMIN"
                                        @click="remove(user.id)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue';
import { defineProps } from 'vue';
import { useStore } from 'vuex';
import { ref, computed } from 'vue';
import axios from 'axios';

export default {
    components: { Layout },
    methods: {
    },
    title: 'Your jobs',
}
</script>