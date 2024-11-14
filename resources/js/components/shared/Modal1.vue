<template>
  <div class="modal fixed top-0 overflow-y-auto h-full w-full" v-show="value">
    <div class="relative bg-white rounded-lg my-10" :class="!size ? 'mx-4' : `mx-auto ${size}`">
      <a href="#" @click.prevent="close" class="btn-close absolute right-0 top-0">&times;</a>

      <header class="overflow-hidden rounded-tl-lg">
        <h2 class="text-center py-2 text-2xl font-semibold">{{ title }}</h2>
      </header>

      <div class="relative px-6 pb-6">
        <slot />  
      </div>      
    </div>
  </div>
</template>

<script>

export default {
  name: 'Modal1',
  props: {
    value: {
      required: true
    },
    size: String,
    title: String,
  },
  methods: {
    close() {
      this.$emit('input', !this.value)
    }
  }
}

</script>

<style lang="scss" scoped>
  .modal {
    background-color: rgba(0,0,0, .7);
  }

  a.btn-close {
    color: #302369;
    background: #fff;

    height: 30px;
    width: 30px;
    display: block;
    text-align: center;
    line-height: 1;
    font-size: 1.75rem;
    border: 1px solid #302369;
    border-radius: 100%;

    margin-top: -12px;
    margin-right: -12px;
  }

  header {
    background: #302369;
    h2 { color: #fff }
  }

  label {
    color: #2D2D2D;
  }
  

  input, textarea { border: 1px solid #707070 }
</style>
