<script setup>
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

const resume = ref(null);
const selectedWorkFields = ref([]);
const selectedExperience = ref(null);
const { userID } = defineProps(['userID']); // Define and access props

const fetchData = async () => {
    await axios.get(`api/portfolios/${userID}`)
            .then((response) => {
                resume.value = response.data.resume;
                if(response.data.work_fields != ""){
                    selectedWorkFields.value = JSON.parse(response.data.work_fields);
                }
                selectedExperience.value = response.data.work_experience;
            })
}

const save = async () => {
    await axios.put(`api/portfolios/${userID}`, {
        resume: resume.value,
        selectedExperience: selectedExperience.value,
        selectedWorkFields: selectedWorkFields.value
    })
    .then(function (response) {
        let p = document.getElementById('responseMessage');
        p.style = 'color: green';
        p.innerHTML = response.data;
    })
    .catch(function (error) {
        let p = document.getElementById('responseMessage');
        p.style = 'color: red';
        p.innerHTML = error.response.data;
    });
}

onMounted(() => {
    fetchData();
})
</script>
<template>
    <Layout>
        <div class="container mx-auto content-center mt-5 p-5 bg-white shadow-lg sm:mx-auto">
            <div class="mx-auto">
                <FormKit type="form" method="put" id="registration-example" submit-label="Save" @submit="save"
                    :actions="false" #default="{ value }">
                    <p class="text-center pb-3" id="responseMessage"></p>
                    <hr class="mb-4" />

                    <ul class="-mt-5 text-center content" hidden>
                        <li class="formkit-message" id="username-exists1">
                        </li>
                    </ul>

                    <div>
                        <FormKit type="textarea"
                            v-model="resume" 
                            name="resume" 
                            label="Resume" 
                            placeholder="Your resume." 
                            rows="15"
                            validation="required" 
                            :classes="{
                                outer: 'lg:grow',
                                wrapper: 'lg:contents'
                            }" />
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="selectedWorkFields">Select your work fields:</label>
                            <Multiselect v-model="selectedWorkFields"
                            placeholder="Work Field(s)" 
                            mode="tags" 
                            :options="workFields"
                            :searchable="false"
                            :close-on-select="false" 
                            :create-option="true"
                            :required="true"
                            id="selectedWorkFields"/>
                        </div>
                        <div>
                            <FormKit type="select" 
                                v-model="selectedExperience"
                                name="workExperience" 
                                label="Work Experience"
                                placeholder="Years of experience" 
                                :options="experience" 
                                validation="required"
                                :classes="{
                                    outer: 'lg:grow',
                                    wrapper: 'lg:contents'
                                }" />
                        </div>
                    </div>
                    
                    <FormKit type="submit" 
                        label="Save"
                        :classes="{
                            wrapper: 'flex justify-center mx-auto my-5'
                            }" 
                        :wrapper-class="{
                            'formkit-wrapper': false
                            }" />
                </FormKit>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue';
import { FormKit } from '@formkit/vue';
import Multiselect from '@vueform/multiselect';
import axios from 'axios';
import { computed } from 'vue';
import { onMounted } from 'vue';
import { ref } from 'vue';
export default {
    components: { Layout, FormKit, Multiselect },
}
</script>
<style>
@import '../../../node_modules/@vueform/multiselect/themes/tailwind.css';
</style>