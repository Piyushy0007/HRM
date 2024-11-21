<template>
  <div class="c-listing-page px-4 pb-4 w-80" style="margin-right: 1vw;">
    <header-component class="sticky top-0 z-50 bg-white shadow-md" />
    <div class="container mx-auto pt-5 ml-4">
      <h1 class="text-2xl font-bold mb-4 ml-4">Listing</h1>
      <table class="table-auto w-full border-collapse border border-gray-200 shadow-md rounded-lg ml-4">
        <thead class="bg-gray-100">
          <tr>
            <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
            <th class="border border-gray-200 px-4 py-2 text-left">Email Address</th>
            <th class="border border-gray-200 px-4 py-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50">
            <td class="border border-gray-200 px-4 py-2">John Doe</td>
            <td class="border border-gray-200 px-4 py-2">john.doe@example.com</td>
            <td class="border border-gray-200 px-4 py-2 flex space-x-2">
              <button
                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 focus:outline-none"
                @click="alert('Edit John Doe')"
              >
                Edit
              </button>
              <button
                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 focus:outline-none"
                @click="alert('Delete John Doe')"
              >
                Delete
              </button>
              <button
                class="bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600 focus:outline-none"
                @click="alert('Info about John Doe')"
              >
                Info
              </button>
            </td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="border border-gray-200 px-4 py-2">Jane Smith</td>
            <td class="border border-gray-200 px-4 py-2">jane.smith@example.com</td>
            <td class="border border-gray-200 px-4 py-2 flex space-x-2">
              <button
                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 focus:outline-none"
                @click="alert('Edit Jane Smith')"
              >
                Edit
              </button>
              <button
                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 focus:outline-none"
                @click="alert('Delete Jane Smith')"
              >
                Delete
              </button>
              <button
                class="bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600 focus:outline-none"
                @click="alert('Info about Jane Smith')"
              >
                Info
              </button>
            </td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="border border-gray-200 px-4 py-2">Alice Johnson</td>
            <td class="border border-gray-200 px-4 py-2">alice.johnson@example.com</td>
            <td class="border border-gray-200 px-4 py-2 flex space-x-2">
              <button
                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 focus:outline-none"
                @click="openJobForm('Edit')"
              >
                Edit
              </button>
              <button
                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 focus:outline-none"
                @click="alert('Delete Alice Johnson')"
              >
                Delete
              </button>
              <button
                class="bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600 focus:outline-none"
                @click="alert('Info about Alice Johnson')"
              >
                Info
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
    methods: {
  alert(message) {
    window.alert(message);
  },
  openJobForm(mode) {
    console.log('Navigating to create page with mode:', mode);
    this.$router.push('/create');
  },
},

};

</script>

<style>
/* Optional: Ensure header-component styles are well-defined */
header-component {
  width: 100%;
  display: block;
}
</style>
