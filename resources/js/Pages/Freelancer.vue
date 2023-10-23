<script setup>
const { freelancer, client_id } = defineProps(['freelancer', 'client_id']);

const userRating = ref(0);
const overallRating = ref(null);
const hoveredStars = ref(0);

const rate = async (rating) => {
    axios.post(`api/ratings`, {
        client_id: client_id,
        freelancer_id: freelancer.id,
        rating: rating
    })
    .then((response) => {
        fetchOverallRating();
        hoveredStars.value = rating;
        userRating.value = rating;
    });
}

const hoverStars = (n) => {
    hoveredStars.value = n;
};

const unhoverStars = () => {
    hoveredStars.value = userRating.value;
};

const isStarHovered = (n) => {
    return n <= hoveredStars.value;
};

const fetchOverallRating = async () => {
    axios.get(`api/ratings/${freelancer.id}`)
    .then((response) => {
        overallRating.value = response.data;
    })
    .catch((e) => {

    });
}

const fetchUserRating = async () => {
    axios.get(`api/ratings/${freelancer.id}/${client_id}`)
    .then((response) => {
        userRating.value = response.data;
        hoverStars(userRating.value);
    })
    .catch((e) => {

    });
}

onMounted(() => {
    fetchUserRating();
    fetchOverallRating();
});
</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="container mx-auto">
                    <div class="my-10">
                        <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">Freelancer</h1>
                        <p class="mb-6 text-lg text-center font-normal text-gray-500">
                            {{ freelancer?.name + " " + freelancer?.surname }}
                            {{ overallRating != null ? "(Rating " + overallRating + ")" : "(unrated)" }}
                        </p>
                        <div class="mb-2 text-center">
                            <div>
                                Your rating
                            </div>
                            <div>
                                <div v-for="n in 10" class="flex-wrap inline-flex" id="userRating">
                                    <div
                                        @mouseenter="hoverStars(n)"
                                        @mouseleave="unhoverStars(n)" 
                                        @click="rate(n)" 
                                        :class="{'opacity-25': !isStarHovered(n), 'hover:opacity-100': isStarHovered(n)}"
                                        class=" cursor-pointer"
                                    >
                                        &#11088;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mx-auto">
                    <p class="mb-6 text-lg text-center font-extrabold text-black">
                        Freelancer's information
                    </p>

                    <div class="grid grid-cols-3 mb-10">
                        <div class="mx-auto">
                            <div class="text-lg text-center font-extrabold text-black">Country</div>
                            <p>{{ freelancer?.country }}</p>
                        </div>
                        
                        <div class="mx-auto">
                            <div class="text-lg text-center font-extrabold text-black">Work fields</div>
                            <p>{{ JSON.parse(freelancer?.work_fields).toString() }}</p>
                        </div>

                        <div class="mx-auto">
                            <div class="text-lg text-center font-extrabold text-black">Work experience</div>
                            <p>{{ freelancer?.work_experience == 10 ? "+" + freelancer?.work_experience : freelancer?.work_experience }} year(s)</p>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="text-lg font-extrabold text-black">Resume</div>
                        <p>{{ freelancer?.resume }}</p>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import axios from 'axios';
import Layout from '../Layout/Layout.vue';
import { defineProps, ref, onMounted } from 'vue';
export default {
    components: { Layout },
    title: 'Home',
}
</script>