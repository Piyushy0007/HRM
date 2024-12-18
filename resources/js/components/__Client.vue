<template>
  <div>
    <header-component />

    <nav class="employee mb-1">
      <ul class="flex justify-center w-5/12 mx-auto">
      	<router-link tag="li" :to="{ name: 'clindex' }" class="w-1/4 mr-1 rounded-t-lg mt-1">
      		<a href="#" class="block text-center font-semibold text-lg pt-1">Community</a>
      	</router-link>
      	<router-link tag="li" :to="{ name: 'client-properties' }" class="w-1/4 mr-1 rounded-t-lg mt-1">
      		<a href="#" class="block text-center font-semibold text-lg pt-1">Properties</a>
      	</router-link>
      </ul>
    </nav>

		<router-view></router-view>

	</div>
</template>

<style lang="scss" scoped>
	@import '../../sass/dashboard';
	nav.employee,
nav.edit-delete-positions {
  border-bottom: 1px solid #3B86FF;
ul{
	margin-bottom: 0;
  li {
    border: 1px solid #3B86FF;
      border-bottom: 0;
      background: #fff;
      color: #3B86FF;
    a{
       color: #3B86FF;
       text-decoration: none;
    }
    &.router-link-exact-active {
       background: #3B86FF;
      color: #fff;
       text-decoration: none;
	  a{
       color: #fff;
        text-decoration: none;
    }
    }
  }
}
}
</style>