<template>
    <Layout>
        <div>
            <div class="mx-auto">
                <h1 class="text-center text-3xl my-10">Freelance marketplace</h1>
            </div>
            <div class="container mx-auto">
                <div class="flex items-center justify-center">
                    <p class="w-1/3 p-4">
                        A freelance marketplace is an online platform that connects freelancers—individuals offering
                        services
                        or skills—with clients or businesses seeking to hire for specific tasks or projects. These
                        platforms facilitate a wide range of services,
                        including writing, graphic design, programming, marketing, and more. Freelancers create profiles
                        showcasing their expertise,
                        and clients can browse and hire them based on their skills and project needs. The marketplace
                        often handles payment transactions
                        and provides a platform for communication between freelancers and clients.
                    </p>
                    <img class="w-1/4 object-cover shadow-lg rounded-xl"
                        src="https://www.hiveage.com/wp-content/uploads/2015/06/balancing-freelancing-and-job.jpg"
                        alt="img">
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue';
export default {
    components: { Layout },
    title: 'Home',
}
</script>