<script setup>
import { RouterView } from 'vue-router'
import Navbar from './Navbar.vue'
import Footer from './Footer.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <Navbar />
    <main class="flex-grow">
      <RouterView />
    </main>
    <Footer />
  </div>
</template>
