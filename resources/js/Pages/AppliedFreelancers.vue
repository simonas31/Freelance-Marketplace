<script setup>
import { CLIENT, FREELANCER, ADMIN} from '../constants'
const workFields = [{ label: 'IT', value: 'IT' }, 
                    { label: 'Graphic Design', value: 'Graphic Design' }, 
                    { label: 'Writing and Content Creation', value: 'Writing and Content Creation' }, 
                    { label: 'Video and Multimedia Production', value: 'Video and Multimedia Production' },
                    { label: 'Digital Marketing', value: 'Digital Marketing' },
                    { label: 'Photography or Videography', value: 'Photography or Videography' },
                    { label: 'Consulting and Coaching', value: 'Consulting and Coaching' },
                    { label: 'Illustration and Art', value: 'Illustration and Art' }];
const experience = [{ label: '1 year', value: 1 }, 
                    { label: '2 years', value: 2 }, 
                    { label: '3 years', value: 3 }, 
                    { label: '5 years', value: 5 }, 
                    { label: '10+ years', value: 10 }];

const freelancers = ref([]);
const isLoading = ref(true);
const message = ref('Loading...');
const showChatModals = ref([]);
const selectedWorkFields = ref(null);
const selectedExperience = ref(null);
const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);

const fetchFreelancers = async() => {
    try {
        await axios.get('/api/freelancers')
        .then((response) => {
            const data = response.data;
            freelancers.value = data;
            isLoading.value = false;
            for(let i = 0; i < data.length; i++){
                showChatModals.value.push(false);
            }
        }); // Replace with your API endpoint
    } catch (err) {
        message.value = 'Error fetching data from the API.';
        isLoading.value = false;
    }
}

const fetchFiltered = async() => {
    const params = {
        selectedWorkFields: selectedWorkFields != null ? selectedWorkFields.value : null,
        selectedExperience: selectedExperience != null ? selectedExperience.value : null,
    }
    isLoading.value = true;
    await axios.get('/api/freelancers', {
        headers: {
            'content-type': 'application/json'
        },
        params: params
    }).then((response) => {
        freelancers.value = response.data;
        isLoading.value = false;
    }).catch(e => {
        isLoading.value = false;
    });
}

const reset = async () => {
    selectedWorkFields.value = null;
    selectedExperience.value = null;
    freelancers.value = {}
    isLoading.value = true;
    const response = await axios.get('/api/freelancers').then((response) => {
        isLoading.value = false
        freelancers.value = response.data;
    })
    .catch((response) => {
        isLoading.value = false
    })
}

const hire = async (freelancerID, jobID, hired_freelancer_id) => {
    await axios.put(`/api/users/${freelancerID}/jobs/${jobID}/hiredfreelancers/${hired_freelancer_id}`)
    .then((response) => {
        router.get('/applied-freelancers');
    })
    .catch((e) => {

    });
}

const toggleChatModal = (chatModalId) => {
    if(!showChatModals[chatModalId]){
        for(let i = 0; i < showChatModals.value.length; i++){
            showChatModals.value[i] = false;
        }
        showChatModals.value[chatModalId] = true;
    }
};

const closeChatModal = (chatModalId) =>{
    showChatModals.value[chatModalId] = false;
}

onMounted(() => {
    fetchFreelancers();
})
</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">Freelancers interested in your jobs</h1>
                    <p class="mb-6 text-lg text-center font-normal text-gray-500">
                        Search freelancers by selecting your preferred criteria.
                    </p>
                </div>

                <div class="flex justify-center mb-10">
                    <div class="grid grid-cols-3 gap-2 items-center"><!--filter-->
                        <div class="w-64">
                            <Multiselect v-model="selectedWorkFields"
                                    placeholder="Preferred Work Field(s)" 
                                    mode="tags" 
                                    :options="workFields"
                                    :close-on-select="false" 
                                    :create-option="true"/>
                        </div>
                        <div class="w-64">
                            <Multiselect v-model="selectedExperience"
                                    placeholder="Preferred starting work experience" 
                                    mode="single" 
                                    :options="experience"
                                    :create-option="true"/>
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
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Job title</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Freelancer</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work fields</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Work Experience</th>
                                <th class="px-6 py-3 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rating</th>
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
                        <tbody v-for="(freelancer, index) in freelancers">
                            <tr @click="find(freelancer.freelancer.id)" class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 hover:cursor-pointer">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ freelancer.job_title }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ freelancer.freelancer.name + ' ' + freelancer.freelancer.surname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ freelancer.freelancer.profile.country }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ JSON.parse(freelancer.freelancer.portfolio.work_fields).toString() }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ freelancer.freelancer.portfolio.work_experience }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ freelancer.freelancer.rating || 0 }}</td>   
                                <!-- add - remove button for each freelancer by admin -->
                                <td v-if="role == CLIENT || role == ADMIN" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <button v-if="role == CLIENT"
                                        @click.stop="hire(freelancer.freelancer.id, freelancer.job_id, freelancer.id)"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mr-4">
                                        Hire
                                    </button>
                                    <button v-if="role == CLIENT"
                                        @click.stop="toggleChatModal(index)"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-4 rounded mr-4"
                                    >
                                        Chat
                                    </button>
                                </td>
                            </tr>
                            <ChatModal 
                                :freelancer_id="freelancer.freelancer.id"
                                :show_Chat="showChatModals[index]"
                                :modal-id="index"
                                :receiver="[freelancer.freelancer.name, freelancer.freelancer.surname]"
                                @close-chat-modal="closeChatModal(index)"
                            ></ChatModal>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue';
import ChatModal from '../Components/ChatModal.vue';
import { FormKit } from '@formkit/vue';
import Multiselect from '@vueform/multiselect';
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { router } from '@inertiajs/vue3'
import { useStore } from 'vuex';

export default {
    components: { Layout, FormKit, Multiselect },
    methods: {
        find(id){
            router.get('freelancers/' + id);
        },
    },
    title: 'Applied Freelancers'
}
</script>