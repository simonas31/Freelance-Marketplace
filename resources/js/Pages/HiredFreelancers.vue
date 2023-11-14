<script setup>
import { CLIENT, FREELANCER, ADMIN} from '../constants'

const { hiredFreelancers } = defineProps('hiredFreelancers');
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

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
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Freelancer</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work fields</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work Experience</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th v-if="role == CLIENT" class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Operations</th>
                            </tr>
                        </thead>
                        <tbody v-for="(hiredFreelancer, index) in hiredFreelancers">
                            <tr @click="find(hiredFreelancer.id)" class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 hover:cursor-pointer">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.name + ' ' +hiredFreelancer.surname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.country }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ JSON.parse(hiredFreelancer.work_fields).toString() }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.work_experience }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ hiredFreelancer.rating || 0 }}</td>   
                                <!-- add - remove button for each freelancer by admin -->
                                <td v-if="role == CLIENT" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <button v-if="role == CLIENT"
                                        @click.stop="pay(hiredFreelancer.id)"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mr-4">
                                        Pay
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
import { computed } from 'vue';
import { useStore } from 'vuex';

export default {
    components: { Layout, FormKit, Multiselect },
    methods: {
    }
}
</script>