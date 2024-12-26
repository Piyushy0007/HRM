<template>
    <div>
        <h2>Unauthorized</h2>
        <p>You do not have access to this page.</p>
        <button @click="goBack">Go Back</button>
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
