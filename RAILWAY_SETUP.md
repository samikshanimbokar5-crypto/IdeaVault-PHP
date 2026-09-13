# IdeaVault Railway Deployment - Complete Setup Guide

## Status Check

- ✅ IdeaVault-PHP service deployed
- ✅ MySQL database created
- ✅ Tables `project_ideas` and `users` exist
- ❌ **Website not accessible** (DNS resolution failing)
- ❌ Database variables not connected to web service

---

## Step 1: Fix the IdeaVault-PHP Service Domain

1. Go to your Railway project: https://railway.com/project/0c032ae0-fd6f-4442-8b53-1e5b2241d753
2. Click on the **IdeaVault-PHP** service card
3. Look for the **"Deployments"** or **"Networking"** tab
4. Find the **Public URL** or **Domain** section
5. If no domain is shown:
   - Click **"Generate Domain"** or **"Add Domain"**
   - Railway will create a new public domain like: `https://ideavault-php-production.up.railway.app`
6. If the domain exists but is not working:
   - Delete it and regenerate a new one
   - Wait 60 seconds for DNS to propagate

---

## Step 2: Add Database Environment Variables to IdeaVault-PHP

The IdeaVault-PHP service needs to connect to the MySQL service.

1. In the IdeaVault-PHP service page, click **"Variables"** tab
2. Add the following environment variables (Railway provides these from the MySQL service):

| Key                     | Value                                                                   |
| ----------------------- | ----------------------------------------------------------------------- |
| `IDEAVAULT_DB_HOST`     | `mysql.railway.internal`                                                |
| `IDEAVAULT_DB_PORT`     | `3306`                                                                  |
| `IDEAVAULT_DB_NAME`     | `railway` (or your database name)                                       |
| `IDEAVAULT_DB_USER`     | `root`                                                                  |
| `IDEAVAULT_DB_PASSWORD` | _[Get from MySQL service Variables tab - look for MYSQL_ROOT_PASSWORD]_ |

**How to get the MySQL password:**

1. Click on the **MySQL** service in your project
2. Open the **Variables** tab
3. Look for `MYSQL_ROOT_PASSWORD` - copy the value
4. Paste it in the `IDEAVAULT_DB_PASSWORD` field in IdeaVault-PHP

---

## Step 3: Alternative - Use MySQL Variable References (Recommended)

Instead of manually copying values, Railway can automatically link variables:

1. In IdeaVault-PHP service **Variables** tab
2. Click **"Add Variable"** for each:
   - `IDEAVAULT_DB_HOST` → Reference `MYSQLHOST`
   - `IDEAVAULT_DB_PORT` → Reference `MYSQLPORT` (or set to `3306`)
   - `IDEAVAULT_DB_NAME` → Reference `MYSQLDATABASE`
   - `IDEAVAULT_DB_USER` → Reference `MYSQLUSER`
   - `IDEAVAULT_DB_PASSWORD` → Reference `MYSQLPASSWORD`

---

## Step 4: Verify Database Schema

The app auto-creates the `users` table, but you need to ensure `project_ideas` table exists:

1. Click on **MySQL** service
2. Click **"Database"** tab
3. Look for `project_ideas` and `users` tables
4. If `project_ideas` is missing:
   - Click **Console** tab
   - Run this SQL:

```sql
CREATE TABLE project_ideas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    domain VARCHAR(80) NOT NULL,
    description LONGTEXT NOT NULL,
    technologies VARCHAR(255) NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    status ENUM('Idea', 'Planning', 'In progress', 'Completed') NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

---

## Step 5: Redeploy the Service

After adding variables:

1. Go back to IdeaVault-PHP service
2. Click **"Deployments"** tab
3. Click **"Redeploy"** button to restart with new variables
4. Wait for deployment to complete (green "Online" status)

---

## Step 6: Test the Website

1. Copy the public domain from IdeaVault-PHP service (e.g., `https://ideavault-php-production.up.railway.app`)
2. Paste into browser
3. You should see the **IdeaVault Login Page**
4. Click "Create an account" to register a test user
5. Create an idea from the dashboard

---

## Step 7 (Optional): Enable Google Sign-In

If you want Google OAuth login:

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create an OAuth 2.0 Web Application credential
3. Add this Authorized Redirect URI: `https://your-app-domain.up.railway.app/google-callback.php`
4. Copy the Client ID and Client Secret
5. In IdeaVault-PHP Variables, add:
   - `GOOGLE_CLIENT_ID` = _your client ID_
   - `GOOGLE_CLIENT_SECRET` = _your client secret_
   - `GOOGLE_REDIRECT_URI` = `https://your-app-domain.up.railway.app/google-callback.php`
6. Redeploy

---

## Common Issues & Fixes

### "This site can't be reached" - DNS_PROBE_FINISHED_NXDOMAIN

- Domain not generated yet → Click "Generate Domain"
- Domain generated but not resolving → Delete and regenerate
- Wait 2-3 minutes for DNS propagation

### "IdeaVault is temporarily unavailable"

- Database connection failed → Check IDEAVAULT*DB*\* variables
- Variables not set → Add them to IdeaVault-PHP service
- MySQL service offline → Check MySQL service status

### "Login page shows but can't create account"

- Database schema incomplete → Check if `users` table exists
- App will auto-create on first run → Try again
- Check app logs in Console tab for SQL errors

### "Can't upload PDF exports"

- Check if directory permissions allow PHP writes
- Check Railway logs for permission errors

---

## Next Steps

1. **Immediate**: Follow Steps 1-5 above to get the site online
2. **Verification**: Test login and idea creation
3. **Optional**: Set up Google OAuth if needed
4. **Development**: Make changes to `/config.example.php` or other files locally, then push to GitHub to auto-deploy
