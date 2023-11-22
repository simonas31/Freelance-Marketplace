<script setup>

const { userID } = defineProps(['userID']);

const country = ref(null);
const address = ref(null);
const iban = ref(null);
const image = ref(null);
const imageUrl = ref(null);

const base64toFile = function(base64, filename, mimeType) {
    const binaryString = atob(base64);
    const len = binaryString.length;
    const bytes = new Uint8Array(len);

    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }

    const blob = new Blob([bytes], { type: mimeType });

    const file = new File([blob], filename, { type: mimeType });

    return file;
}

const fetchProfile = () => {
    axios.get(`api/profiles/${userID}`)
        .then((response) => {
            country.value = response.data.country
            address.value = response.data.address
            iban.value = response.data.iban
            if(response.data.profile_picture != null){
                const fileObject = base64toFile(response.data.profile_picture, 'Your image.png', 'image/png');
                image.value = [{ name: 'Your_image.png', file: fileObject }];
                imageUrl.value = URL.createObjectURL(fileObject);
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
    if(imageUrl.value != null && country.value != null && iban.value && address.value){
        document.getElementById('noImage').hidden = true;
    }else if(imageUrl.value == null){
        document.getElementById('noImage').hidden = false;
    }

    await axios.post(`/api/profiles/${userID}`, {
        country: country.value,
        iban: iban.value,
        address: address.value,
        picture: typeof image.value != "undefined" ? image.value[0].file : null
    }, settings)
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
                        @change="handleFileChange" 
                        :classes="{
                            outer: 'lg:grow',
                            wrapper: 'lg:contents'
                        }"/>
                </div>
                
                <p class="text-red-600" id="noImage" hidden>No image selected.</p>
                <div class="lg:flex lg:justify-center">
                    <p class="my-8 lg:mx-4 mt-8 pr-0">Image preview: </p>
                    <div class="my-auto">
                        <img v-if="imageUrl"
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