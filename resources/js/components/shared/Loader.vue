<template>
  <div class="c-loader flex flex-col w-full h-full items-center justify-center fixed z-50 top-0" v-if="value">
    <div>
      <div></div>
    </div>
    <p class="block text-xl mt-2 text-white font-hairline">{{ msg }}</p>
  </div>
</template>

<script>

export default {
  props: {
    msg: String,
    value: {
      required: true
    }
  }
}

</script>

<style lang="scss" scoped>

.c-loader {
  background: rgba(0,0,0, .8);

  & > div {
    position: relative;

    div {
      width: 5rem; height: 5rem;
      display: inline-block;
      border: 8px solid rgba(255,255,255, 0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: 1s spin infinite ease-in-out;
    }
  }
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

</style>