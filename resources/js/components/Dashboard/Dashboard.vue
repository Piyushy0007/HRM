<template>

    <div>
        <header-component />
    <div class="c-Dashboard" style="margin-left: 242px;">

    <div class="flex w-full min-h-screen">
      <div class="w-3/4 rounded-2xl shadow-lg">
        <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h1 class="text-3xl font-bold mb-4">Hello, Kewal</h1>
        <div class="flex items-start space-x-4">
          <div class="w-12 h-12 flex items-center justify-center bg-purple-600 text-white font-bold rounded-full">
            KV
          </div>
          <div class="flex-1">
            <input
              type="text"
              class="w-full p-3 border rounded-lg text-lg"
              placeholder="I kewal ..."
            />
            <p class="text-sm text-gray-500 mt-2">
              Broadcast message to your organization and/or make a LinkedIn post directly from here.
            </p>
          </div>
        </div>
        <div class="mt-4 flex justify-between items-center">
          <a href="#" class="text-blue-500">Organization and LinkedIn</a>
          <button class="bg-custom-button text-white px-4 py-2 rounded-lg shadow-md">Post</button>
        </div>
        </div>


        <div class="bg-white p-6 rounded-2xl shadow-lg mt-4">

                <!-- Celebrations Header -->
                <div class="text-lg font-semibold text-blue-600 flex items-center space-x-2">
                  <span>✔</span>
                  <span>Celebrations</span>
                </div>
            
                <!-- Tabs -->
                <div class="flex justify-start mt-4 space-x-8 border-b">
                  <div class="flex items-center space-x-2 pb-2 border-b-2 border-blue-500 text-blue-600 font-semibold">
                    <span class="text-xl">🎂</span>
                    <span>Birthdays</span>
                  </div>
                  <div class="flex items-center space-x-2 text-gray-500">
                    <span class="text-xl">📷</span>
                    <span>Anniversaries</span>
                  </div>
                </div>
            
                <!-- No Birthdays Today -->
                <div class="flex items-center justify-center space-x-4 p-6">
                  <!-- <img src="/calendar-placeholder.png" alt="No Birthdays" class="w-32"> -->
                  <p class="text-gray-600 text-lg">Looks like there are no birthdays today!</p>
                </div>
            
                <!-- Upcoming Birthdays -->
                <div class="mt-8">
                  <h2 class="text-xl font-semibold text-gray-700 text-center">Upcoming</h2>
                  <div class="flex justify-center mt-4 space-x-6">
                    <div v-for="person in upcomingBirthdays" :key="person.name" class="text-center">
                      <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-lg" 
                           :class="person.color">
                        {{ person.initials }}
                      </div>
                      <p class="text-gray-700 font-semibold mt-2">{{ person.name }}</p>
                      <p class="text-gray-500 text-sm">{{ person.date }}</p>
                    </div>
                  </div>
                </div>
              </div>
        
      </div>
  
      <div class="w-1/4 rounded-2xl shadow-lg ml-6 text-center">
        <div class="bg-white p-2 rounded-2xl shadow-lg text-center">

        <p class="text-gray-600">Today: Fri Jan 31 2025</p>
        <p class="text-4xl font-bold mt-2">11:51:47 AM</p>
        <p class="text-sm text-gray-500">(GMT+0530) IST - Asia/Kolkata</p>
        <p class="text-gray-700 mt-4">Get ready for an amazing workday!</p>
        <button class="bg-custom-button text-white w-full py-2 mt-4 rounded-lg shadow-md">Clock-In</button>
        <p class="text-blue-500 mt-2 cursor-pointer">Policy: Normal Attendance Policy</p>
     
    </div>  
    <div class="bg-white p-2 rounded-2xl shadow-lg text-center mt-4">

        <button class="bg-custom-button text-white w-full py-2 mt-4 rounded-lg shadow-md">Create Virtual Account</button>
        <p class="text-blue-500 mt-2 cursor-pointer">Know More</p>
        </div>


        <div class="bg-white p-2 rounded-2xl shadow-lg text-center mt-4">

            <button class="bg-white border-2 border-custom-button text-custom-button w-full py-2 mt-4 rounded-lg shadow-md">Add People</button>
            <button class="bg-white border-2 border-custom-button text-custom-button w-full py-2 mt-4 rounded-lg shadow-md">Add Accountant</button>
            <button class="bg-white border-2 border-custom-button text-custom-button w-full py-2 mt-4 rounded-lg shadow-md">data</button>
           
        </div>
        
    </div>

    </div>
    </div>

    </div>

  </template>
  
  <script>
  export default {
    name: "Dashboard",
    data() {
    return {
      upcomingBirthdays: [
        { name: "Ashish", date: "2 Mar", initials: "MD", color: "bg-purple-700" },
        { name: "piyush", date: "5 Mar", initials: "PJ", color: "bg-purple-900" },
        { name: "yash", date: "18 Mar", initials: "ML", color: "bg-green-700" },
        { name: "Anjuman", date: "19 Mar", initials: "Ts", color: "bg-purple-800" },
        { name: "Rajani", date: "25 May", initials: "MD", color: "bg-green-800" },
      ],
    };
  },

  };
  </script>
  
  <style scoped>
  </style>
  