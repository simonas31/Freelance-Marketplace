<script setup>

defineProps({job: Object});
</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                {{ job }}
                
                <div class="container mx-auto">
                    <p class="mb-6 text-lg text-center font-extrabold text-black">
                        Jobs's information
                    </p>
                    <p class="mb-6 text-lg text-center font-normal text-gray-500">
                       Posted time: {{ new Date((Date.parse(job?.posted_time))).toLocaleDateString('en-us', { year:"numeric", month:"numeric", day:"numeric" }) }}
                    </p>
                    <div class="grid grid-cols-3 mb-10">
                        <div class="mx-auto">
                            <div class="text-lg text-center font-extrabold text-black">Country</div>
                            <p>maybe add job country?</p>
                        </div>
                        
                        <div class="mx-auto">
                            <div class="text-lg text-center font-extrabold text-black">Work fields</div>
                            <p>{{ JSON.parse(job?.work_fields).toString() }}</p>
                        </div>

                        <div class="mx-auto">
                            <div class="text-lg text-center font-extrabold text-black">Pay amount</div>
                            <p>{{ job.pay_amount }}€</p>
                        </div>
                    </div>

                    <div class="mb-5">
                        <p class="text-lg font-extrabold text-black">Client</p>
                        <p>{{ job.user.name + " " + job.user.surname }}</p>
                    </div>
                    <div class="mb-5">
                        <p class="text-lg font-extrabold text-black">Description</p>
                        <p>{{ job?.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue';
export default {
    components: { Layout },
    title: 'Job',
}
</script>