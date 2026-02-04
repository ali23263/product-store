# 🚀 Quick Start Guide - Product Store Frontend

## Prerequisites
- Node.js 16+ installed
- Laravel backend running on `http://localhost:8000`
- npm or yarn package manager

## Installation Steps

### 1. Navigate to the frontend directory
```bash
cd /home/runner/work/product-store/product-store/frontend
```

### 2. Install dependencies
```bash
npm install
```

### 3. Start the development server
```bash
npm run dev
```

The application will be available at: **http://localhost:3000**

## 🎯 Testing the Application

### Test User Accounts
After setting up the Laravel backend, you'll have these test users:

**Admin Account**
- Email: `admin@example.com`
- Password: `password`
- Access: Full admin panel

**Picker Account**
- Email: `picker@example.com`
- Password: `password`
- Access: Order processing dashboard

**Customer Account**
- Email: `customer@example.com`
- Password: `password`
- Access: Shopping and order history

### Testing Flow

1. **Customer Experience**
   - Visit `http://localhost:3000`
   - Browse products on the home page
   - Click on a product to view details
   - Add products to cart
   - View cart at `/cart`
   - Register a new account at `/register` or login
   - Apply promo code (if available)
   - Complete checkout
   - View order history at `/dashboard`

2. **Admin Experience**
   - Login as admin
   - Access admin panel at `/admin`
   - View dashboard statistics
   - Manage products (add, edit, delete)
   - Create categories
   - Generate promo codes (try: 10% discount)
   - View and manage orders
   - Update order statuses
   - View all users

3. **Picker Experience**
   - Login as picker
   - Access picker dashboard at `/picker`
   - View pending orders
   - Update order status:
     - Pending → Processing → Ready → Shipped → Delivered
   - Filter orders by status
   - View order details

## 🛠️ Development Tips

### Hot Module Replacement (HMR)
The dev server includes HMR - changes are reflected instantly without refresh.

### View Components
Components are organized as:
- `src/components/` - Reusable components
- `src/components/admin/` - Admin-specific components
- `src/pages/` - Page components
- `src/layouts/` - Layout wrappers

### State Management (Pinia)
Stores are located in `src/stores/`:
- `auth.js` - Authentication state
- `cart.js` - Shopping cart
- `products.js` - Products and categories
- `orders.js` - Orders and promo codes

### API Calls
All API endpoints are defined in `src/services/api.js`.
Update the `baseURL` if your backend runs on a different port.

## 🎨 Customization

### Change Colors
Edit `tailwind.config.js`:
```javascript
colors: {
  primary: {
    // Change these values
    500: '#8b5cf6',
    600: '#7c3aed',
    // ...
  }
}
```

### Add New Pages
1. Create component in `src/pages/YourPage.vue`
2. Add route in `src/router/index.js`
3. Add navigation link in header

### Modify API URL
Edit `src/services/api.js`:
```javascript
const api = axios.create({
  baseURL: 'http://your-backend-url/api',
  // ...
})
```

## 📦 Build for Production

```bash
npm run build
```

Output will be in the `dist/` directory.

### Preview Production Build
```bash
npm run preview
```

## 🔍 Common Issues

### Issue: API requests fail
**Solution**: 
- Ensure Laravel backend is running on port 8000
- Check CORS configuration in Laravel
- Verify network tab in browser DevTools

### Issue: Login redirects to home instead of dashboard
**Solution**: 
- Check user role in database
- Verify navigation guards in `src/router/index.js`

### Issue: Cart doesn't persist
**Solution**: 
- Check browser localStorage
- Verify `cartStore.loadCart()` is called in App.vue

### Issue: Build errors
**Solution**:
```bash
# Clear cache and reinstall
rm -rf node_modules dist
npm install
npm run build
```

## 🧪 Features to Test

### Shopping Flow
- ✅ Browse products with search
- ✅ Filter by category
- ✅ Add to cart
- ✅ Update quantities
- ✅ Apply promo code
- ✅ Checkout (requires login)
- ✅ View order history

### Admin Features
- ✅ Dashboard statistics
- ✅ Product CRUD operations
- ✅ Category management
- ✅ Promo code generator
- ✅ Order status updates
- ✅ User management

### Responsive Design
- ✅ Mobile (< 640px)
- ✅ Tablet (640px - 1024px)
- ✅ Desktop (> 1024px)

## 📱 Mobile Testing

Open in mobile browser or use Chrome DevTools:
1. Open DevTools (F12)
2. Click device toolbar icon (Ctrl+Shift+M)
3. Select a mobile device
4. Test all features

## 🎓 Learning Resources

**Vue.js 3**
- [Official Vue 3 Documentation](https://vuejs.org/)
- [Composition API Guide](https://vuejs.org/guide/extras/composition-api-faq.html)

**Pinia**
- [Pinia Documentation](https://pinia.vuejs.org/)

**Tailwind CSS**
- [Tailwind CSS Docs](https://tailwindcss.com/docs)

**Vue Router**
- [Vue Router Documentation](https://router.vuejs.org/)

## 🚀 Next Steps

1. **Customize the design** to match your brand
2. **Add more features** like:
   - Product reviews
   - Wishlist
   - Email notifications
   - Advanced filtering
   - Product images upload
3. **Implement testing** with Vitest and Vue Test Utils
4. **Add analytics** tracking
5. **Optimize performance** with lazy loading

## 💡 Pro Tips

- Use Vue DevTools browser extension for debugging
- Leverage Tailwind's utility classes for rapid styling
- Keep components small and focused
- Use Composition API's `computed` for derived state
- Implement proper error boundaries
- Add loading states for better UX
- Test on multiple browsers

## 📞 Support

If you encounter issues:
1. Check the console for errors
2. Review the network tab for API issues
3. Verify backend is running and accessible
4. Check this guide's troubleshooting section

Happy coding! 🎉
