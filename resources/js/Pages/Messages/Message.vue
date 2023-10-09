<script setup>
const props = defineProps({
    message: Object
})

</script>

<template>
    <div
        class="flex items-start max-w-lg pt-3 space-x-3"
        :class="props.message.sender === 'ChatGPT' && 'self-end'"
    >
        <img
            :src="props.message.sender === 'ChatGPT' ? 'images/chatgpt.png' : 'images/user.jpg'"
            class="w-12 h-12 ml-3 rounded-full"
            :class="props.message.sender === 'ChatGPT' && 'order-2'"
            :alt="props.message.sender"
        />
        <div class="flex-1 order-1">
            <div
                class="px-4 py-4 rounded-md lg:px-6 lg:pb-6 dark:text-gray-100"
                :class="props.message.sender === 'ChatGPT'
                    ? 'bg-indigo-400 dark:bg-indigo-600'
                    : 'bg-gray-200 dark:bg-gray-900'"
            >
                <div class="flex items-end justify-between pb-3">
                    <span class="text-sm font-semibold">{{ props.message.sender }}</span>
                    <div class="flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="block text-xs font-semibold tracking-wider uppercase">
                            Just now
                        </span>
                    </div>
                </div>
                <p class="block text-sm">{{ props.message.content }}</p>
            </div>
        </div>
    </div>
</template>
