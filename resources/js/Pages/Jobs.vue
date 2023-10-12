<script setup>
import { useStore } from 'vuex';
import { computed } from 'vue';
import { CLIENT, FREELANCER, ADMIN} from '../constants'

const workFields = [{ label: 'IT', value: 'IT' }, 
                    { label: 'Graphic Design', value: 'Graphic Design' }, 
                    { label: 'Writing and Content Creation', value: 'Writing and Content Creation' }, 
                    { label: 'Video and Multimedia Production', value: 'Video and Multimedia Production' },
                    { label: 'Digital Marketing', value: 'Digital Marketing' },
                    { label: 'Photography or Videography', value: 'Photography or Videography' },
                    { label: 'Consulting and Coaching', value: 'Consulting and Coaching' },
                    { label: 'Illustration and Art', value: 'Illustration and Art' }];

const jobs = ref([]);
const isLoading = ref(true);
const message = ref('Loading...');
const selectedWorkFields = ref(null);
const payFrom = ref(null);
const payTo = ref(null);
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

const fetchJobs = async() => {
    try {
        const response = await axios.get('api/jobs'); // Replace with your API endpoint
        const data = await response.data;
        jobs.value = data;
        isLoading.value = false;
    } catch (err) {
        message.value = 'Error fetching data from the API.';
        isLoading.value = false;
    }
}

const fetchFiltered = async() => {
            const params = {
                selectedWorkFields: selectedWorkFields != null ? selectedWorkFields.value : null,
                payFrom: payFrom != null ? payFrom.value : null,
                payTo: payTo != null ? payTo.value : null
            }
            isLoading.value = true;
            await axios.get('api/jobs', {
                headers: {
                    'content-type': 'application/json'
                },
                params: params
            }).then((response) => {
                jobs.value = response.data;
                isLoading.value = false;
            }).catch(e => {
                isLoading.value = false;
                console.log(e);
            });

        }
const reset = async () => {
            selectedWorkFields.value = null;
            payFrom.value = null;
            payTo.value = null;
            const response = await axios.get('api/jobs');

            jobs.value = await response.data;
        }

onMounted(() => {
    fetchJobs();
})
</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">Current available Jobs</h1>
                    <p class="mb-6 text-lg text-center font-normal text-gray-500">
                        Search jobs by selecting your preferred criteria.
                    </p>
                </div>

                <div class="flex justify-center mb-10">
                    <div class="grid grid-cols-3 gap-2 items-center"><!--filter-->
                        <div class="w-64">
                            <Multiselect v-model="selectedWorkFields"
                                    placeholder="Desired Work Field(s)" 
                                    mode="tags" 
                                    :options="workFields"
                                    :close-on-select="false" 
                                    :create-option="true"/>
                        </div>
                        <div class="w-64">
                            <FormKit
                                placeholder="From pay amount"
                                type="number" 
                                v-model="payFrom" 
                                :min="0" 
                                :max="10000"/>
                            <FormKit
                                placeholder="To pay amount" 
                                type="number" 
                                v-model="payTo" 
                                :min="0" 
                                :max="10000"/>
                        </div>
                        <div class="grid grid-rows-1 content-center">
                            <div>
                                <button @click="fetchFiltered" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-5">Search</button>
                                <button @click="reset" class=" bg-slate-400 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Reset</button>
                            </div>
                        </div>
                    </div>
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
                                <th v-if="role == CLIENT || role == ADMIN" class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Operations</th>
                            </tr>
                        </thead>
                        <tbody v-if="isLoading">
                            <tr>
                                <td colspan="5" class="text-center">
                                    Loading...
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-for="job in jobs">
                            <tr @click="find(job.id)" class="odd:bg-white even:bg-slate-50 hover:bg-slate-200 hover:cursor-pointer">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.user.name + ' ' +job.user.surname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.work_fields }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.pay_amount }}€</td>
                                <td class="px-6 py-4 break-words max-w-md whitespace-no-wrap border-b border-gray-200">{{ job.description }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ job.posted_time }}</td>
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