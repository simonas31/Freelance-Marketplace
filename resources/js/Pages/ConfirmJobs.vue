<script setup>
import { useStore } from 'vuex';
import { computed } from 'vue';
import { CLIENT, FREELANCER, ADMIN} from '../constants'

const { jobs } = defineProps(['jobs']);

const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

const approve = (job_id, event) => {
    axios.patch(`/api/jobs/${job_id}`)
    .then(response => {
        router.get('/confirm-jobs');
    })
}

const remove = (user_id, job_id) => {
    axios.delete(`/api/users/${user_id}/jobs/${job_id}`)
    .then(response => {
        router.get('/confirm-jobs');
    })
};

</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">JOBS THAT NEED CONFIRMATION</h1>
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">OR YOU WISH TO DELETE THEM</h1>

                </div>

                <div class="w-full overflow-x-auto mb-10">
                    <table class="table-auto mx-auto">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work fields</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Pay</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Post Time</th>
                                <th class="px-6 py-3 bg-gray-200 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Operations</th>
                            </tr>
                        </thead>
                        <tbody v-for="job in jobs">
                            <tr @click="find(job.id)" class="odd:bg-white even:bg-slate-50 hover:bg-slate-200 hover:cursor-pointer">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.user.name + ' ' +job.user.surname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.work_fields }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.pay_amount }}€</td>
                                <td class="px-6 py-4 break-words max-w-md whitespace-no-wrap border-b border-gray-200">{{ job.description }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.posted_time }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                                    <button 
                                        v-if="role == ADMIN && job.creation_confirmed == 0"
                                        @click.stop="approve(job.id)"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mr-4">
                                        Approve
                                    </button>
                                    <button
                                        v-if="role == ADMIN"
                                        @click.stop="remove(job.user.id, job.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mr-4">
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
import { FormKit } from '@formkit/vue';
import Multiselect from '@vueform/multiselect';
import axios from 'axios';
import { ref, onMounted, defineProps } from 'vue';
import { router } from '@inertiajs/vue3'
export default {
    components: { Layout, FormKit, Multiselect },
    methods: {
        find(id){
            router.get('jobs/' + id);
        }
    }
}
</script>