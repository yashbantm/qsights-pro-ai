# 🚀 GitHub Deployment Guide

## Step 1: Initialize Local Git Repository

```bash
cd /tmp/qsights-pro-ai
git init
git add .
git commit -m "Initial commit: QSights 2.0 - Complete enterprise platform

- Backend: Laravel 11 with PostgreSQL, UUID support, Sanctum auth
- Frontend: React + Vite with TailwindCSS and ShadCN UI
- Complete database structure with migrations
- All models with relationships
- Role-permission system (9 roles)
- Activity approval workflow
- Multilingual support
- Participant experience
- Dashboard with analytics
- Audit logging system"
```

## Step 2: Create GitHub Repository

### Option A: Via GitHub Website

1. Go to https://github.com/new
2. **Repository name**: `qsights-pro-ai`
3. **Description**: QSights 2.0 - Enterprise Survey, Poll, and Assessment Platform
4. **Visibility**: Private (recommended) or Public
5. **DO NOT** initialize with README, .gitignore, or license (we already have these)
6. Click **"Create repository"**

### Option B: Via GitHub CLI (if installed)

```bash
gh repo create qsights-pro-ai --private --source=. --remote=origin
```

## Step 3: Connect Local Repository to GitHub

After creating the repository on GitHub, you'll see commands like these. Run them:

```bash
git remote add origin https://github.com/YOUR_USERNAME/qsights-pro-ai.git
git branch -M main
git push -u origin main
```

**Replace `YOUR_USERNAME` with your actual GitHub username!**

## Step 4: Verify Upload

1. Go to your repository: `https://github.com/YOUR_USERNAME/qsights-pro-ai`
2. You should see:
   - `backend/` folder with Laravel code
   - `frontend/` folder with React code
   - `README.md`
   - `INSTALLATION.md`
   - All other files

## Step 5: Clone and Setup on Another Machine

When you or someone else wants to download this:

```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/qsights-pro-ai.git
cd qsights-pro-ai

# Follow INSTALLATION.md for setup
```

## 📦 What Gets Pushed to GitHub

✅ **Included:**
- All source code (backend + frontend)
- Database migrations
- Models and controllers
- All React components and pages
- Configuration files (.env.example)
- Documentation (README, INSTALLATION)
- .gitignore files

❌ **Excluded (via .gitignore):**
- `node_modules/`
- `vendor/`
- `.env` files (sensitive data)
- `dist/` build folders
- Log files
- IDE settings

## 🔒 Important Security Notes

1. **Never commit `.env` files** - they contain sensitive credentials
2. The `.env.example` files are included as templates
3. After cloning, users must create their own `.env` files
4. AWS keys, database passwords, API tokens are NOT in the repository

## 🔄 Making Updates

After making changes to your code:

```bash
git add .
git commit -m "Description of your changes"
git push origin main
```

## 📥 Pulling Updates

If someone else made changes:

```bash
git pull origin main
```

## 🌿 Branch Strategy (Optional)

For team development:

```bash
# Create a feature branch
git checkout -b feature/new-feature

# Make changes, commit them
git add .
git commit -m "Add new feature"

# Push feature branch
git push origin feature/new-feature

# Create Pull Request on GitHub
# After review and approval, merge to main
```

## 📋 Repository Settings (Recommended)

On GitHub, go to Settings:

1. **General**:
   - Add description: "QSights 2.0 - Enterprise Survey, Poll, and Assessment Platform"
   - Add topics: `laravel`, `react`, `survey`, `assessment`, `enterprise`

2. **Collaborators** (if team project):
   - Add team members

3. **Branches**:
   - Protect `main` branch
   - Require pull request reviews (optional)

## ✅ Verification Checklist

- [ ] Repository created on GitHub
- [ ] Local repository initialized
- [ ] Remote origin added
- [ ] Code pushed successfully
- [ ] All files visible on GitHub
- [ ] .gitignore working (no node_modules or vendor pushed)
- [ ] README.md displays properly
- [ ] .env files excluded
- [ ] Can clone from another location

## 🎉 Success!

Your QSights 2.0 platform is now on GitHub!

**Repository URL**: `https://github.com/YOUR_USERNAME/qsights-pro-ai`

Share this URL with team members or for deployment to servers.

---

## 📱 Quick Commands Reference

```bash
# Check status
git status

# See commit history
git log --oneline

# Create new branch
git checkout -b branch-name

# Switch branches
git checkout branch-name

# Update from remote
git pull

# Push changes
git push

# See remotes
git remote -v
```

## 🆘 Troubleshooting

### "Permission denied" error
Use HTTPS or setup SSH keys. For HTTPS:
```bash
git remote set-url origin https://github.com/YOUR_USERNAME/qsights-pro-ai.git
```

### Files too large
GitHub has a 100MB file size limit. Check your .gitignore is working.

### Authentication required
You may need to use a Personal Access Token instead of password.
Generate one at: https://github.com/settings/tokens

---

Need help? Check GitHub's documentation: https://docs.github.com
