<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-100 to-gray-200">
        <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Unauthorized Access</h2>
            <p class="text-gray-600 mb-6">
                Sorry, you do not have permission to view this page.
            </p>
            <button 
                @click="goBack" 
                class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition duration-300"
            >
                Go Back to Login
            </button>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        goBack() {
            this.$router.push({ name: "login" });
        }
    }
};
</script>

<style scoped>
</style>
