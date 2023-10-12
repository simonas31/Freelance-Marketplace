<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <Layout>
        <div class=" min-h-fit">
            <div style="max-width: 100%;">
                <div class="mx-5 sm:mx-auto">
                    <div class="max-w-md mx-auto content-center my-5 p-5 bg-white shadow-lg sm:mx-auto lg:max-w-screen-md">
                        <FormKit type="form" method="post" id="registration-example" submit-label="Register" @submit="register" :actions="false" #default="{ value }">
                            <hr class="mb-4" />
                            <FormKit type="hidden" name="role" value="2"/>
                            <FormKit
                                type="text"
                                name="username"
                                label="Your username"
                                placeholder="Username"
                                validation="required"
                                :classes="{
                                    wrapper: 'lg:max-w-full'
                                }"
                                :wrapper-class="{
                                    'formkit-wrapper': false
                                }"/>

                            <ul class="-mt-5" hidden>
                                <li class="formkit-message" id="username-exists1">
                                </li>
                            </ul>

                            <div class="lg:flex lg:gap-4 lg:justify-between">
                                <FormKit
                                type="text"
                                name="name"
                                label="Your name"
                                placeholder="Name"
                                validation="required"
                                :classes="{
                                        outer: 'lg:flex-auto'
                                    }"/>

                                <FormKit
                                    type="text"
                                    name="surname"
                                    label="Your surname"
                                    placeholder="Surname"
                                    validation="required" 
                                    :classes="{
                                        outer: 'lg:flex-auto'
                                    }"/>
                            </div>

                            <div class="lg:flex lg:gap-4 lg:justify-between">
                                <FormKit 
                                    type="password"
                                    name="password"
                                    label="Password"
                                    validation="required|length:6|matches:/[^a-zA-Z]/"
                                    :validation-messages="{
                                        matches: 'Please include at least one symbol',
                                    }"
                                    placeholder="Your password" help="Choose a password"
                                    :classes="{
                                        outer: 'lg:flex-auto'
                                    }"/>

                                <FormKit
                                    type="password"
                                    name="password_confirm"
                                    label="Confirm password"
                                    placeholder="Confirm password"
                                    validation="required|confirm"
                                    help="Confirm your password"
                                    :classes="{
                                        outer: 'lg:flex-auto'
                                    }"/>
                            </div>
                            
                            <div class="lg:flex">
                                <FormKit
                                    id="register-picture"
                                    type="file"
                                    name="picture"
                                    label="Profile picture"
                                    accept=".png, .jpg, .jpeg"
                                    help="Select a photo that would be used as profile picture."
                                    multiple="true"
                                    @change="handleFileChange"
                                    />
                                
                                <div class="sm:flex">
                                    <p class="sm:my-8 mx-4 mt-8 pr-0">Image preview: </p>
                                    <div class="my-auto">
                                        <img v-if="imageUrl" :src="imageUrl" alt="Uploaded Image" style="border: 1px solid #555; border-radius: 50%; width: 50px; height: 50px;"/>
                                    </div>
                                </div>
                            </div>
                            
                            <FormKit 
                                type="submit"
                                label="Register"
                                :classes="{
                                    wrapper: 'lg:flex lg:justify-center'
                                }"
                                :wrapper-class="{
                                    'formkit-wrapper': false
                                }" />
                        </FormKit>
                        <div class="lg:text-center">
                            <p>Already have an account?
                                <Link href="/login" style="color: blue">Login</Link>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue'
import { router } from '@inertiajs/vue3'
import { FormKit } from '@formkit/vue'
import axios from 'axios'

export default {
    data() {
        return {
            imageUrl: null,
        };
    },
    components:{
        FormKit,
        Layout
    },
    methods:{
        async register(e){
            let settings = { headers: { 'content-type': 'multipart/form-data' } };
            let errorLi = document.getElementById('username-exists1');
            errorLi.parentElement.setAttribute('hidden', true);

            await axios.post('/api/register',
            {
                username: e.username,
                name: e.name,
                surname: e.surname,
                email: e.email,
                password: e.password,
                picture: (e.picture[0] == undefined ? null : e.picture[0].file),
                role: e.role
            }, settings)
            .then(function (response){
                router.visit('/login');
            })
            .catch(function (error){
                let errorLi = document.getElementById('username-exists1');
                if(error.response != undefined){
                    errorLi.parentElement.removeAttribute('hidden');
                    errorLi.innerText = error.response.data.message;
                }
            });
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            const imageUrl = URL.createObjectURL(file);
            this.imageUrl = imageUrl;
        },
    },
    mounted() {
    },
    title: 'Register',
}
</script>