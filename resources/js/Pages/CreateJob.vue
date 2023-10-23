<script setup>
const { user_id } = defineProps(['user_id']);

const workFields = [{ label: 'IT', value: 'IT' }, 
                    { label: 'Graphic Design', value: 'Graphic Design' }, 
                    { label: 'Writing and Content Creation', value: 'Writing and Content Creation' }, 
                    { label: 'Video and Multimedia Production', value: 'Video and Multimedia Production' },
                    { label: 'Digital Marketing', value: 'Digital Marketing' },
                    { label: 'Photography or Videography', value: 'Photography or Videography' },
                    { label: 'Consulting and Coaching', value: 'Consulting and Coaching' },
                    { label: 'Illustration and Art', value: 'Illustration and Art' }];

const selectedWorkFields = ref(null);

const save = async (e) => {
    await axios.post(`api/users/${user_id}/jobs`, {
        description: e.workDescription,
        work_fields: selectedWorkFields.value.toString(),
        pay_amount: e.pay,
        job_title: e.jobTitle
    })
    .then(() => {
        router.visit('/freelancers');
    })
    .catch((e) => {
        
    });
}
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
                        <FormKit type="text"
                            name="jobTitle" 
                            label="Job Title" 
                            placeholder="Job Title."
                            validation="required" 
                            :classes="{
                                wrapper: 'lg:contents'
                            }" />
                    </div>

                    <div>
                        <FormKit type="textarea"
                            name="workDescription" 
                            label="Work Description" 
                            placeholder="Work Description." 
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
                                <Multiselect
                                v-model="selectedWorkFields"
                                placeholder="Work Field(s)" 
                                mode="tags" 
                                :options="workFields"
                                :searchable="false"
                                :close-on-select="false" 
                                :create-option="true"
                                :required="true"
                                id="selectedWorkFields"
                            />
                        </div>
                        <div>
                            <FormKit type="number" 
                                name="pay" 
                                label="Pay amount"
                                placeholder="Pay amount" 
                                validation="required"
                                :min="10"
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
import { router } from '@inertiajs/vue3'
import Layout from '../Layout/Layout.vue';
import { FormKit } from '@formkit/vue';
import Multiselect from '@vueform/multiselect';
import axios from 'axios';
import { ref } from 'vue';
export default {
    components: { Layout, FormKit, Multiselect },
    title: 'Create Job'
}
</script>