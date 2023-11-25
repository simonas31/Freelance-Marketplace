<script setup>
import { CLIENT, FREELANCER, ADMIN } from '../constants';
const { chats } = defineProps(['chats']);

const store = useStore();

const user = computed(() => store.state.user);
const role = computed(() => user.value.role);
const showChatModals = ref([]);
const imageUrl = ref(null);

for(let i = 0; i < chats.length; i++){
    showChatModals.value.push(false);
}

const toggleChatModal = (chatModalId) => {
    if (!showChatModals[chatModalId]) {
        for (let i = 0; i < showChatModals.value.length; i++) {
            showChatModals.value[i] = false;
        }
        showChatModals.value[chatModalId] = true;
    }
};

const closeChatModal = (chatModalId) =>{
    showChatModals.value[chatModalId] = false;
}

</script>
<template>
    <Layout>
        <div class="flex-wrap content-center bg-white my-10">
            <div class="grid">
                <div class="my-10">
                    <h1 class="mb-4 text-2xl text-center font-extrabold leading-none tracking-tight text-gray-900">YOUR CHATS</h1>
                </div>
                <div class="w-full overflow-x-auto mb-10 mx-auto px-10 lg:max-w-5xl">
                    <div class="grid sm:grid-cols-2 md:grid-cols-2 md:gap-2 lg:grid-cols-3 lg:gap-3">
                        <div v-for="(chat, index) in chats" class="group bg-yellow-300 hover:bg-yellow-400 shadow-2xl rounded-lg w-64 p-4 mb-4 mx-auto transition-all">
                            <img :src="'data:image/png;base64,' + (chat.client_receiver ? chat.client_receiver.profile.profile_picture : chat.freelancer_receiver.profile.profile_picture)" alt="Card Image" class="w-full h-32 object-cover rounded-t-lg">
                            <div class="p-2 bg-white overflow-auto">
                                <p class="text-black text-center" :style="{ height: chatHeight(index) }">
                                    Chat with <span class="font-semibold">{{ (chat.client_receiver ? chat.client_receiver.name + " " + chat.client_receiver.surname : chat.freelancer_receiver.name + " " + chat.freelancer_receiver.surname) }}</span>
                                </p>
                            </div>
                            <div class="p-4 rounded-b-lg text-center bg-blue-400">
                                <button @click="toggleChatModal(index)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-full">Chat</button>
                            </div>
                            <ChatModal
                                    :freelancer_id="(chat.client_receiver ? chat.user_id : chat.receiver)"
                                    :show_Chat="showChatModals[index]"
                                    :modal-id="index"
                                    :receiver="chat.client_receiver ? [chat?.client_receiver.name, chat?.client_receiver.surname] : [chat?.freelancer_receiver.name, chat?.freelancer_receiver.surname]"
                                    @close-chat-modal="closeChatModal(index)"
                            ></ChatModal>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '../Layout/Layout.vue';
import ChatModal from '../Components/ChatModal.vue';
import { defineProps } from 'vue';
import { useStore } from 'vuex';
import { ref, computed } from 'vue';

export default {
    components: { Layout, ChatModal },
    methods: {
        chatHeight(index) {
        // You can adjust the multiplier as needed
        return `${Math.max(this.getClientReceiverHeight(index), this.getFreelancerReceiverHeight(index))}px`;
        },

        getClientReceiverHeight(index) {
            return this.chats[index]?.client_receiver ? this.calculateTextHeight(this.chats[index]?.client_receiver?.name + " " + this.chats[index]?.client_receiver?.surname) : 0;
        },

        getFreelancerReceiverHeight(index) {
            return this.calculateTextHeight(this.chats[index]?.freelancer_receiver?.name + " " + this.chats[index]?.freelancer_receiver?.surname);
        },

        calculateTextHeight(text) {
            // You can customize this function based on your design and font
            // For simplicity, I'm using a rough estimate
            const lineHeight = 60; // Adjust based on your design
            const lines = text.split('\n').length;
            return lineHeight * lines;
        },
    },
    title: 'Your Chats',
}
</script>