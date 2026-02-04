<template>
  <footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About -->
        <div>
          <h3 class="text-white text-lg font-bold mb-4">Product Store</h3>
          <p class="text-sm">
            Your trusted e-commerce destination for quality products at great prices.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-white text-lg font-bold mb-4">Quick Links</h3>
          <ul class="space-y-2 text-sm">
            <li><router-link to="/" class="hover:text-white transition-colors">Home</router-link></li>
            <li><router-link to="/cart" class="hover:text-white transition-colors">Shopping Cart</router-link></li>
            <li><router-link to="/dashboard" class="hover:text-white transition-colors">My Account</router-link></li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div>
          <h3 class="text-white text-lg font-bold mb-4">Customer Service</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Returns</a></li>
            <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 class="text-white text-lg font-bold mb-4">Newsletter</h3>
          <p class="text-sm mb-4">Subscribe to get special offers and updates.</p>
          <div class="flex">
            <input 
              type="email" 
              placeholder="Your email" 
              class="flex-1 px-3 py-2 text-gray-900 rounded-l-lg focus:outline-none"
            />
            <button class="bg-primary-600 text-white px-4 py-2 rounded-r-lg hover:bg-primary-700 transition-colors">
              Subscribe
            </button>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
        <p>&copy; 2024 Product Store. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
