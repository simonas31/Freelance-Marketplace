<script setup>
import { CLIENT, FREELANCER, ADMIN} from '../constants'

const { hiredFreelancers } = defineProps(['hiredFreelancers']);
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

const pay = (freelancerID, jobID, amount) => {
    axios.post(`/api/users/${user.value.id}/jobs/${jobID}/transactions`, {
        'amount': amount,
        'receiver': freelancerID,
    })
    .then(response => {
        router.get('/hired-freelancers');
    });
}

</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">Your hired freelancers</h1>
                </div>

                <div class="w-full overflow-x-auto mb-10">
                    <table class="table-auto mx-auto">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Job title</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Freelancer</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work fields</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work Experience</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th v-if="role == CLIENT" class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Operations</th>
                            </tr>
                        </thead>
                        <tbody v-for="(hiredFreelancer, index) in hiredFreelancers">
                            <tr @click="find(hiredFreelancer.freelancer.id)" class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 hover:cursor-pointer">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.job_title }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.freelancer.name + ' ' + hiredFreelancer.freelancer.surname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.country }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ JSON.parse(hiredFreelancer.work_fields).toString() }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.work_experience }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.rating || 0 }}</td>   

                                <td v-if="role == CLIENT" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <button v-if="role == CLIENT && hiredFreelancer.transaction_id == -1"
                                        @click.stop="pay(hiredFreelancer.freelancer.id, hiredFreelancer.job_id, hiredFreelancer.pay_amount)"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mr-4">
                                        Pay
                                    </button>
                                    <div v-else>
                                        <div class="bg-green-500 px-2 py-2 text-center text-white">
                                            Payed
                                        </div>
                                    </div>
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
import { computed, defineProps } from 'vue';
import { useStore } from 'vuex';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

export default {
    components: { Layout, FormKit, Multiselect },
    methods: {
        find(id){
            router.get('freelancers/' + id);
        },
    }
}
</script>