<script setup>
import { CLIENT, FREELANCER, ADMIN} from '../constants'

const { transactions } = defineProps(['transactions']);
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">Your completed transactions</h1>
                </div>

                <div class="w-full overflow-x-auto mb-10">
                    <table class="table-auto mx-auto">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Job title</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Freelancer</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Pay amoutn</th>
                            </tr>
                        </thead>
                        <tbody v-for="(transaction, index) in transactions">
                            <tr class="even:bg-slate-200 hover:bg-slate-100">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ transaction.job.job_title }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ transaction.job.hired_freelancer.freelancer.name + ' ' + transaction.job.hired_freelancer.freelancer.surname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ transaction.job.hired_freelancer.client.name + ' ' + transaction.job.hired_freelancer.client.name }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ Math.round((transaction.amount * ((100-transaction.tax)/100)) * 100) / 100 }}</td>   
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
import { computed, defineProps, ref } from 'vue';
import { useStore } from 'vuex';

export default {
    components: { Layout },
    methods: {
    },
    title: 'Completed Transactions',
}
</script>