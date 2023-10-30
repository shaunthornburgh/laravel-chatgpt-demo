<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useForm } from "@inertiajs/vue3";
import { ref } from 'vue'
import Message from "@/Pages/Messages/Message.vue";
import TextInput from "@/Components/TextInput.vue";

const messages = ref([]);
const loading = ref(false);
const page = usePage();
const form = useForm({
    userMessage: '',
})
const count = ref(1);

const store = async () => {
    loading.value = true;
    messages.value.push({
        content: form.userMessage,
        sender: page.props.auth.user.name
    });

    const source = new EventSource(`/chat?userMessage=${form.userMessage}`);
    const message = {content: '', sender: 'ChatGPT'}
    messages.value.push(message);
    source.addEventListener("update", function (event) {
        if (event.data === "<END_STREAM>") {
            count.value += 2;
            source.close();
            return;
        }
        console.log(count.value)
        messages.value[count.value].content += event.data
        form.userMessage = '';
    });
    loading.value = false;
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="h-full py-12">
            <div class="max-w-7xl h-full mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col bg-white dark:bg-gray-800 rounded-md h-full">
                    <div class="flex items-center justify-between px-4 py-3 lg:px-6">
                        <div class="relative flex items-center space-x-2">
                            <span class="relative bottom-0 right-0 w-2 h-2 bg-green-500 rounded-full">&nbsp;</span>
                            <h2 class="text-base font-semibold text-gray-800 dark:text-white">ChatGPT is online</h2>
                        </div>
                    </div>
                    <div class="flex grow flex-col px-4 pb-8 space-y-4 border-t border-gray-200 lg:px-6 dark:border-gray-700">
                        <div class="flex min-h-0 grow basis-0 flex-col space-y-4 overflow-y-auto">
                            <Message
                                v-for="message in messages"
                                :message="message"
                            />
                        </div>

                        <div class="flex items-center pt-3">
                            <img
                                src="images/user.jpg"
                                class="w-12 h-12 mr-3 rounded-full"
                                :alt="page.props.auth.user.name"
                            />
                            <div class="flex-1">
                                <form @submit.prevent="store">
                                    <TextInput
                                        v-model="form.userMessage"
                                        type="text"
                                        class="w-full px-4 py-3 text-sm placeholder-gray-400 border border-gray-200 rounded-md"
                                        placeholder="Send a message"
                                        :disabled="loading === true"
                                    />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
