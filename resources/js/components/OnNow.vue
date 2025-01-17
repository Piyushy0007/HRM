<template>
    <div>
        <header-component />
        <nav class="employee mb-1">
            <ul class="flex w-6/12" style="margin-left: 242px;">
                <router-link tag="li" :to="{ name: 'on-now' }" class="w-1/4 mr-1 mt-1" exact >
                    <a href="#" class="block text-center font-semibold text-lg pt-1">On Now</a>
                </router-link>
                <router-link tag="li" :to="{ name: 'on-later' }" class="w-1/4 mr-1 mt-1" >
                    <a href="#" class="block text-center font-semibold text-lg pt-1" >On Later</a>
                </router-link>
            </ul>
        </nav>
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    components: {},
    data() {
        return {};
    },
    created() {},
    methods: {}
};
</script>

<style lang="scss" scoped>
@import "../../sass/onnow";
</style>
