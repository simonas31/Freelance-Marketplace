<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Layout>
        <div style="max-width: 100%;">
            <!-- <header class="container mx-auto py-5 text-2xl font-bold text-center">Sign In</header> -->
            <div class="mx-5 sm:mx-auto">
                <div class="max-w-md mx-auto content-center mt-5 p-5 bg-white shadow-lg sm:mx-auto">
                    <FormKit type="form" method="get" submit-label="Login" @submit="login">
                        <div>
                            <FormKit type="text" name="username" label="Your username" placeholder="Username"
                                validation="required" />
                                <FormKit name="password" type="password" label="Password" placeholder="Password"
                                validation="required" />
                            </div>
                            
                            <ul class="-mt-5" hidden>
                                <li class="formkit-message" id="username-exists1">
                                </li>
                            </ul>
                            
                            </FormKit>
                            <div class="text-center">
                            <p>Register as
                                <Link href="/register-client" style="color: blue">client</Link>.
                            </p>
                            <p>Register as
                                <Link href="/register-freelancer" style="color: blue">freelancer</Link>.
                            </p>
                        </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue';
import { router } from '@inertiajs/vue3'
import { FormKit } from '@formkit/vue'
import axios from "axios"

export default {
    components: {
        FormKit,
        Layout
    },
    methods: {
        async login(e) {
            let errorLi = document.getElementById('username-exists1');
            errorLi.parentElement.setAttribute('hidden', true);

            const response = await axios.post('/api/login', {
                username: e.username,
                password: e.password
            })
                .then(function (response) {
                    localStorage.setItem('jwt', response.data.access_token);
                    router.visit('/');
                })
                .catch(function (error) {
                    if (error.response != undefined) {
                        errorLi.parentElement.removeAttribute('hidden');
                        errorLi.innerText = error.response.data.message;
                    }
                });
        }
    },
    title: 'Login',
}
</script>