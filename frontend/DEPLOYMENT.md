# 🚀 Deployment Checklist

## Pre-Deployment

### 1. Environment Configuration
- [ ] Create `.env` file from `.env.example`
- [ ] Update `VITE_API_BASE_URL` to production API URL
- [ ] Update `VITE_APP_URL` to production frontend URL

### 2. Code Review
- [ ] Review all TODO comments
- [ ] Check for console.log statements
- [ ] Verify all API endpoints match backend
- [ ] Test all user flows

### 3. Build Verification
```bash
npm run build
```
- [ ] Build completes without errors
- [ ] Check bundle size (should be ~76KB gzipped)
- [ ] Verify dist folder is created

### 4. Testing
- [ ] Test all pages in development mode
- [ ] Test authentication flow
- [ ] Test cart functionality
- [ ] Test admin panel
- [ ] Test picker dashboard
- [ ] Test responsive design on mobile
- [ ] Test all form validations
- [ ] Test error handling

## Deployment Steps

### Option 1: Static Hosting (Netlify, Vercel, etc.)

1. **Build the application**
   ```bash
   npm run build
   ```

2. **Deploy the `dist` folder**
   - Netlify: Drag and drop `dist` folder
   - Vercel: `vercel --prod`
   - GitHub Pages: Push to gh-pages branch

3. **Configure redirects** (for SPA routing)
   Create `dist/_redirects` (Netlify):
   ```
   /*    /index.html   200
   ```

### Option 2: Traditional Web Server (Nginx)

1. **Build the application**
   ```bash
   npm run build
   ```

2. **Copy dist files to web root**
   ```bash
   cp -r dist/* /var/www/html/
   ```

3. **Configure Nginx**
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /var/www/html;
       index index.html;

       location / {
           try_files $uri $uri/ /index.html;
       }

       # API proxy (optional)
       location /api {
           proxy_pass http://backend:8000;
       }
   }
   ```

### Option 3: Docker

1. **Build Docker image**
   ```bash
   docker build -t product-store-frontend .
   ```

2. **Run container**
   ```bash
   docker run -p 3000:80 product-store-frontend
   ```

## Post-Deployment

### 1. Verification
- [ ] Visit production URL
- [ ] Test login/register
- [ ] Test product browsing
- [ ] Test cart and checkout
- [ ] Test admin panel (if applicable)
- [ ] Check browser console for errors
- [ ] Test on mobile devices
- [ ] Test on different browsers

### 2. Performance
- [ ] Run Lighthouse audit
- [ ] Check page load times
- [ ] Verify API response times
- [ ] Monitor bundle size

### 3. SEO (Optional)
- [ ] Add meta tags
- [ ] Configure sitemap
- [ ] Set up analytics
- [ ] Add favicon

### 4. Monitoring
- [ ] Set up error tracking (Sentry, etc.)
- [ ] Configure analytics (Google Analytics, etc.)
- [ ] Set up uptime monitoring

## Environment Variables Reference

```env
# API Configuration
VITE_API_BASE_URL=http://localhost:8000/api

# App Configuration
VITE_APP_NAME=Product Store
VITE_APP_URL=http://localhost:3000
```

**Production Example:**
```env
VITE_API_BASE_URL=https://api.yourdomain.com/api
VITE_APP_NAME=Product Store
VITE_APP_URL=https://yourdomain.com
```

## Common Issues

### Issue: API calls fail after deployment
**Solution**: 
- Verify `VITE_API_BASE_URL` is correct
- Check CORS settings on backend
- Ensure backend is accessible from frontend domain

### Issue: Routes return 404
**Solution**:
- Configure server for SPA routing
- Add redirect rules (see deployment steps)

### Issue: Environment variables not working
**Solution**:
- Rebuild application after changing .env
- Ensure variables start with `VITE_`
- Check variables are imported correctly

## Security Checklist

- [ ] HTTPS enabled
- [ ] Environment variables secured
- [ ] API keys not exposed in code
- [ ] CORS properly configured on backend
- [ ] CSP headers configured
- [ ] Authentication tokens secured

## Performance Optimization

- [ ] Enable gzip compression
- [ ] Set up CDN for static assets
- [ ] Configure browser caching
- [ ] Lazy load images
- [ ] Code splitting implemented
- [ ] Bundle size optimized

## Rollback Plan

1. **Keep previous build**
   ```bash
   mv dist dist.backup
   ```

2. **If issues occur, restore**
   ```bash
   mv dist.backup dist
   ```

3. **Or redeploy from specific commit**
   ```bash
   git checkout <commit-hash>
   npm run build
   ```

## Success Criteria

- ✅ Application loads without errors
- ✅ All pages accessible
- ✅ Authentication working
- ✅ API calls successful
- ✅ Responsive on all devices
- ✅ Performance metrics acceptable
- ✅ No console errors

## Quick Deploy Commands

### Development
```bash
npm run dev
```

### Production Build
```bash
npm run build
```

### Production Preview
```bash
npm run preview
```

### Deploy to Netlify
```bash
npm run build
netlify deploy --prod --dir=dist
```

### Deploy to Vercel
```bash
vercel --prod
```

## Notes

- Always test build locally before deploying
- Keep environment variables secure
- Monitor application after deployment
- Have rollback plan ready
- Document any custom configurations

---

**Last Updated**: 2024
**Version**: 1.0.0
