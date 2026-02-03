# PSOL FRANCA Admin System Setup Guide

## 🚀 New Features Added

### 1. **Admin Profile Management** (`admin_profile.php`)

- Update admin name and email
- Change password with current password verification
- Secure password validation (minimum 6 characters)
- Success/error notifications

### 2. **Forgot Password System** (`forgot_password.php`)

- Email-based password recovery
- Secure token generation
- Multi-step password reset process
- Token expiration (1 hour)

### 3. **Enhanced Login System** (`login.php`)

- Database authentication with password hashing
- "Forgot Password?" link
- Improved security with prepared statements
- Session management

### 4. **Inquiry Submission** (`get-started.php`)

- Form submission to database
- Beautiful success message with animation
- Error handling and validation
- Admin can view all inquiries in dashboard

---

## 📋 Database Setup Instructions

### Step 1: Import Database

1. Open **phpMyAdmin** in your browser: `http://localhost/phpmyadmin`
2. Click on **"Import"** tab
3. Click **"Choose File"** and select `database_setup.sql`
4. Click **"Go"** to import

**OR** Create manually:

1. Create a new database named `psol_franca`
2. Copy the SQL from `database_setup.sql`
3. Paste and execute in the SQL tab

### Step 2: Verify Tables

After import, you should have 3 tables:

- `users` - Admin user accounts
- `inquiries` - Customer inquiry submissions
- `services` - Service offerings

---

## 🔐 Default Login Credentials

**Email:** `admin@psolfranca.com`  
**Password:** `admin123`

⚠️ **Important:** Change this password immediately after first login!

---

## 📁 New Files Created

1. **`admin_profile.php`** - Admin profile management page
2. **`forgot_password.php`** - Password recovery page
3. **`database_setup.sql`** - Database schema and initial data
4. **`README_ADMIN_SETUP.md`** - This file

---

## 🎯 How to Use

### For Admin:

1. **Login**
   - Go to `http://localhost/Psol%20Franca/login.php`
   - Use default credentials or your custom credentials
   - Click "Forgot Password?" if you forget your password

2. **Access Dashboard**
   - After login, you'll be redirected to `admin_dashboard.php`
   - View inquiries and manage services

3. **Update Profile**
   - Click "My Profile" in the sidebar
   - Update your name, email, or password
   - Save changes

4. **Reset Password (if forgotten)**
   - Click "Forgot Password?" on login page
   - Enter your email address
   - Follow the steps to reset your password

### For Customers:

1. **Submit Inquiry**
   - Go to `http://localhost/Psol%20Franca/get-started.php`
   - Fill out the inquiry form
   - Click "Submit Inquiry Now"
   - See success message confirmation

---

## 🔒 Security Features

✅ **Password Hashing** - All passwords stored with `password_hash()`  
✅ **Prepared Statements** - SQL injection protection  
✅ **Session Management** - Secure user sessions  
✅ **Input Validation** - Server-side validation for all forms  
✅ **Token-based Reset** - Secure password recovery with expiring tokens  
✅ **CSRF Protection** - Hidden form tokens

---

## 🎨 Features Highlights

### Admin Profile Page

- ✨ Clean, modern interface matching your brand
- 📝 Update name and email
- 🔐 Change password securely
- ✅ Real-time validation
- 🎯 Success/error notifications

### Forgot Password

- 📧 Email verification
- 🔑 Secure token generation
- ⏰ 1-hour token expiration
- 🎨 Beautiful multi-step UI
- ✅ Password strength validation

### Inquiry System

- 📋 Complete form with validation
- 💾 Automatic database storage
- 🎉 Animated success message
- 📊 Admin can view in dashboard
- 📱 Responsive design

---

## 🛠️ Troubleshooting

### Issue: "Connection failed" error

**Solution:** Check `includes/db_connect.php` and ensure:

- Database name is `psol_franca`
- MySQL is running in XAMPP
- Username is `root` and password is empty (default)

### Issue: "Invalid email or password"

**Solution:**

- Make sure you imported the database
- Use default credentials: `admin@psolfranca.com` / `admin123`
- Or use "Forgot Password" to reset

### Issue: Inquiry not saving

**Solution:**

- Verify `inquiries` table exists
- Check browser console for errors
- Ensure all required fields are filled

---

## 📞 Support

If you encounter any issues:

1. Check the browser console (F12) for JavaScript errors
2. Check PHP error logs in XAMPP
3. Verify database connection in `includes/db_connect.php`

---

## ✨ Next Steps

1. ✅ Import the database using `database_setup.sql`
2. ✅ Login with default credentials
3. ✅ Change your password in "My Profile"
4. ✅ Test the inquiry form
5. ✅ Customize your profile information

---

**Enjoy your new admin system! 🎉**
