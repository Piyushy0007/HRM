<template>
  <div class="container mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">User Table</h1>
    <table class="table-auto w-full border-collapse border border-gray-200 shadow-md rounded-lg">
      <thead class="bg-gray-100">
        <tr>
          <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
          <th class="border border-gray-200 px-4 py-2 text-left">Email Address</th>
          <th class="border border-gray-200 px-4 py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
          <td class="border border-gray-200 px-4 py-2">{{ user.name }}</td>
          <td class="border border-gray-200 px-4 py-2">{{ user.email }}</td>
          <td class="border border-gray-200 px-4 py-2 flex space-x-2">
            <button
              class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 focus:outline-none"
              @click="editUser(user)"
            >
              Edit
            </button>
            <button
              class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 focus:outline-none"
              @click="deleteUser(user.id)"
            >
              Delete
            </button>
            <button
              class="bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600 focus:outline-none"
              @click="viewInfo(user)"
            >
              Info
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [
        { id: 1, name: "John Doe", email: "john@example.com" },
        { id: 2, name: "Jane Smith", email: "jane@example.com" },
        { id: 3, name: "Alice Johnson", email: "alice@example.com" },
      ],
    };
  },
  methods: {
    editUser(user) {
      alert(`Editing user: ${user.name}`);
    },
    deleteUser(userId) {
      this.users = this.users.filter((user) => user.id !== userId);
      alert("User deleted!");
    },
    viewInfo(user) {
      alert(`User Info:\nName: ${user.name}\nEmail: ${user.email}`);
    },
  },
};
</script>

<style>
/* Add some custom styling (optional) */
.container {
  max-width: 800px;
}
</style>
