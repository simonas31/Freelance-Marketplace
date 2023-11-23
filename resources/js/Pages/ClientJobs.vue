<script setup>
import { CLIENT, FREELANCER, ADMIN} from '../constants';
const { jobs } = defineProps(['jobs']);

const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

const remove = (job_id) => {
    axios.delete(`api/users/${user.value.id}/jobs/${job_id}`)
    .then(response => {
        router.get('/your-jobs');
    });
};

</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">YOUR CREATED JOBS</h1>
                </div>
                <div class="w-full overflow-x-auto mb-10">
                    <table class="table-auto mx-auto">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work Fields</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Pay</th>
                                <th v-if="role == CLIENT || role == ADMIN" class="px-6 py-3 bg-gray-200 text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">Operations</th>
                            </tr>
                        </thead>
                        <tbody v-for="(job, index) in jobs">
                            <tr @click="find(job.id)" class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 hover:cursor-pointer">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.job_title }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.description }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.work_fields }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.pay_amount }} €</td>
                                <!-- add - remove button for each freelancer by admin -->
                                <td v-if="role == CLIENT || role == ADMIN" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                                    <button v-if="role == CLIENT || role == ADMIN"
                                        @click.stop="edit(job.id)"
                                        class="bg-yellow-200 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded">
                                        Edit
                                    </button>
                                    &nbsp;
                                    <button v-if="role == CLIENT || role == ADMIN"
                                        @click.stop="remove(job.id)"
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
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    components: { Layout },
    methods: {
        find(id){
            router.get('jobs/' + id);
        },
        edit(id){
            router.get('edit-jobs/' + id);
        }
    },
    title: 'Your jobs',
}
</script>