<script setup>
const props = defineProps(['freelancer_id', 'show_Chat', 'modalId', 'receiver']);

const store = useStore();
const user = computed(() => store.state.user);

const messages = ref([]);
const text = ref('');
const chatContainer = ref(null);
const chat_id = ref(null);

const fetchMessages = async () => {
    await axios.post(`/api/users/${user.value.id}/${props.freelancer_id}/chats`)
    .then((response) => {
        axios.get(`/api/users/${user.value.id}/chats/${response.data.id}/messages`)
        .then((response) => {
            messages.value = response.data?.messages || [];
            chat_id.value = response.data?.chat_id;
            const scroll = document.getElementById('scrollToBottom');
            scroll.scrollTo({left: 0, top: scroll.scrollHeight, behavior: 'smooth'});

            Pusher.logToConsole = true;//turn this of in production

            const pusher = new Pusher('d1b16a9460bea8a34794', {
                cluster: 'eu'
            });
            const channel = pusher.subscribe(`chats.${chat_id.value || messages.value[0]?.chat_id}`);
            channel.bind('message', data => {
                messages.value.push(data);
                scrollToBottom();
            });

            scrollToBottom();
        });
    });
};

const sendMessage = async () => {
    if(text.value != ''){
        axios.post(`/api/users/${user.value.id}/chats/${chat_id.value || messages.value[0].chat_id}/messages`, {
            text: text.value
        })
        .then(response => {
            scrollToBottom();
        });
        document.getElementById('emptyText').hidden = true;
        text.value = '';
    }else{
        document.getElementById('emptyText').hidden = false;
    }
}

function scrollToBottom() {
    if (chatContainer.value) {
        nextTick(() => {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        });
    }
}

watch(() => props.show_Chat, (newVal, oldVal) => {
    if (newVal) {
        fetchMessages()
    }
});
</script>
<template>
    <div class="fixed bottom-4 right-4" v-if="props.show_Chat">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <!-- Modal Content -->
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Chat with {{ props.receiver[0] }}</h2>
                <button class="text-gray-500 hover:text-red-500" @click="closeModalSignal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div>
                <div class="bg-white w-96 p-4 rounded-lg">

                    <!-- Chat messages -->
                    <div class="h-48 border border-gray-300 rounded-lg p-2 overflow-y-auto" ref="chatContainer" id="scrollToBottom">
                        <div v-for="(message, index) in messages">
                            <!-- You: -->
                            <div v-if="message.sender == user.id">
                                <div class="flex justify-end mb-2">
                                    <div>
                                        <span class="font-bold">{{ user.name }}</span> 
                                        <span class="text-sm font-thin">&nbsp; {{ message.send_time }}</span>
                                    </div>
                                    <!-- <img src="john-doe-avatar.jpg" alt="John Doe" class="w-10 h-10 ml-2 rounded-full mr-2" /> -->
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-blue-500 text-white p-2 mb-2 rounded-lg max-w-sm">
                                        {{ message.text }}
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="flex justify-start mb-2">
                                    <div>
                                        <span class="font-bold">{{ props.receiver[0] }}</span> 
                                        <span class="text-sm font-thin">&nbsp;{{ message.send_time }}</span>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="bg-gray-200 p-2 mb-2 rounded-lg max-w-sm">
                                        {{ message.text }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat input -->
                    <div class="mt-4">
                        <textarea v-model="text" class="w-full border border-gray-300 rounded p-2" placeholder="Type your message..." rows="3"></textarea>
                        <p id="emptyText" hidden class="text-red-600">Send not empty message.</p>
                        <button @click="sendMessage" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { ref, watch, defineProps, nextTick } from 'vue';
import axios from 'axios';
import { computed } from 'vue';
import { useStore } from 'vuex';
import Pusher from 'pusher-js';

export default {
    methods: {
        closeModalSignal() {
            this.$emit('closeChatModal', this.$props.modalId);
        },
    }
}
</script>