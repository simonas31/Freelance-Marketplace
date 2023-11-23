<template>
    <Header></Header>
    <main>
        <div style="min-height: 80vh;">
            <slot></slot>
        </div>
    </main>
    <Footer></Footer>
</template>
<script>
import Header from '../Components/Header.vue';
import Footer from '../Components/Footer.vue';
import axios from 'axios';
import { onMounted } from 'vue';
import { useStore } from 'vuex';

export default {
    components: {
        Header,
        Footer
    },
    setup() {
        const store = useStore();

        onMounted(async () => {
            try {
                const response = await axios.get('/api/auth');

                await store.dispatch('setAuth', true);
                await store.dispatch('setUser', response.data.user);
            } catch (e) {
                await store.dispatch('setAuth', false);
                await store.dispatch('setUser', {});
            }
        });
    },
    name: 'Layout'
}
</script>
<style>
[aria-current="page"] {
    font-weight: bold;
}

[v-cloak]>* {
    display: none;
}
</style>