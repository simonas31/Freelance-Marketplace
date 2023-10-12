<script setup>

const { userID } = defineProps(['userID']);

const country = ref(null);
const address = ref(null);
const iban = ref(null);
const image = ref([]);
const imageUrl = ref(null);

const fetchProfile = () => {
    axios.get(`api/profiles/${userID}`)
        .then((response) => {
            country.value = response.data.country
            address.value = response.data.address
            iban.value = response.data.iban
            if(response.data.profile_picture != ""){
                imageUrl.value = `data:image/png;base64,${response.data.profile_picture}`
            }
        });
}

const handleFileChange = (e) => {
    const file = e.target.files[0];
    imageUrl.value = URL.createObjectURL(file);
}

const save = async (e) => {
    let settings = { headers: { 'Content-Type': 'multipart/form-data' } };
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('country', country.value);
    formData.append('iban', iban.value);
    formData.append('address', address.value);
    formData.append('picture', imageUrl.value);

    await axios.post(`/api/profiles/${userID}`, {
        country: country.value,
        iban: iban.value,
        address: address.value,
        picture: e.picture[0].file
    }, settings)
        .then(function (response) {
            let p = document.getElementById('responseMessage');
            p.style = 'color: green';
            p.innerHTML = response.data;
        })
        .catch(function (error) {
            let p = document.getElementById('responseMessage');
            p.style = 'color: red';
            console.log(error);
            p.innerHTML = error.response.data;
    });
}

onMounted(() => {
    fetchProfile();
})

</script>
<template>
    <Layout>
        <!-- <header class="container mx-auto py-5 text-2xl font-bold text-center">Sign In</header> -->
        <div class="container max-w-2xl mx-auto content-center mt-5 p-5 bg-white shadow-lg sm:mx-auto">
            <div class="flex justify-center mx-auto">
            <FormKit type="form" method="POST" id="registration-example" submit-label="Save" @submit="save" :actions="false" #default="{ value }" enctype="multipart/form-data">

                <p class="text-center pb-3" id="responseMessage"></p>
                <hr class="mb-4" />

                <ul class="-mt-5 text-center content" hidden>
                    <li class="formkit-message" id="username-exists1">
                    </li>
                </ul>

                <div class="lg:flex justify-center mx-auto">
                    <div class="lg:mr-7">
                        <FormKit type="text" v-model="country" name="country" label="Country" placeholder="Country" validation="required"/>
                    </div>
                    <div>
                        <FormKit type="text" v-model="address" name="address" label="Your address" placeholder="Address" validation="required" />
                    </div>
                </div>

                <div class="lg:flex justify-center">
                    <FormKit 
                        v-model="image"
                        id="register-picture" 
                        type="file" 
                        name="picture" 
                        label="Profile picture"
                        accept=".png, .jpg, .jpeg" 
                        help="Select a photo that would be used as profile picture."
                        multiple="true"
                        validation="required"
                        @change="handleFileChange" 
                        :classes="{
                            outer: 'lg:grow',
                            wrapper: 'lg:contents'
                        }"/>
                </div>

                <div class="lg:flex lg:justify-center">
                    <p class="my-8 lg:mx-4 mt-8 pr-0">Image preview: </p>
                    <div class="my-auto">
                        <img v-if="imageUrl && image.length != 0"
                            :src="imageUrl" 
                            alt="Uploaded Image"
                            style="border: 1px solid #555; border-radius: 50%; width: 50px; height: 50px;" />
                    </div>
                </div>

                <div class="">
                        <FormKit 
                            v-model="iban"
                            type="text"
                            name="iban"
                            label="IBAN" 
                            placeholder="IBAN" 
                            validation="required"
                            :minlength="5"
                            :maxlength="32"
                            :classes="{
                                outer: 'lg:grow',
                                wrapper: 'lg:contents'
                            }"/>
                </div>

                <FormKit 
                    type="submit" 
                    label="Save" 
                    :classes="{
                    wrapper: 'flex justify-center mx-auto my-5'
                    }" :wrapper-class="{
                        'formkit-wrapper': false
                    }" />
            </FormKit>
        </div>
        </div>
    </Layout>
</template>
<script>
import axios from 'axios';
import Layout from '../Layout/Layout.vue';
import { FormKit } from '@formkit/vue'
import { ref, onMounted } from 'vue';

export default {
    components: { Layout, FormKit },
    title: 'Profile',
    methods: {
    },
}
</script>