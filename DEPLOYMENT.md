# 🚀 Package Deployment Guide

This guide will help you deploy your Laravel Phone Auth Package to GitHub and make it available for other developers.

## 📋 Prerequisites

- GitHub account
- Git installed and configured
- Composer installed
- PHP 8.0+ installed

## 🎯 Step-by-Step Deployment Process

### 1. **Prepare Your Package**

First, ensure your package is ready for deployment:

```bash
# Navigate to package directory
cd package

# Install dependencies
composer install

# Run tests to ensure everything works
composer test
```

### 2. **Create GitHub Repository**

1. Go to [GitHub](https://github.com) and create a new repository
2. Name it `laravel-phone-auth` (or your preferred name)
3. Make it public
4. Don't initialize with README (we already have one)

### 3. **Initialize Git Repository**

```bash
# Initialize git repository
git init

# Add all files
git add .

# Make initial commit
git commit -m "feat: initial release of Laravel Phone Auth Package"

# Add remote repository
git remote add origin https://github.com/your-username/laravel-phone-auth.git

# Push to GitHub
git push -u origin main
```

### 4. **Update Package Information**

Before deploying, update these files with your information:

#### `composer.json`
```json
{
         "name": "mohammed-abd-razaq/laravel-phone-auth",
    "authors": [
        {
            "name": "Your Real Name",
            "email": "your.real.email@example.com",
            "homepage": "https://github.com/your-username",
            "role": "Developer"
        }
    ],
    "support": {
                 "issues": "https://github.com/mohammed-abd-razaq/laravel-phone-auth/issues",
         "source": "https://github.com/mohammed-abd-razaq/laravel-phone-auth",
         "docs": "https://github.com/mohammed-abd-razaq/laravel-phone-auth#readme"
    }
}
```

#### `README.md`
Update the installation command:
```bash
composer require mohammed-abd-razaq/laravel-phone-auth
```

### 5. **Create First Release**

#### Option A: Using Deployment Script (Recommended)

**For Windows:**
```bash
# Run the deployment script
deploy.bat
```

**For Linux/Mac:**
```bash
# Make script executable
chmod +x deploy.sh

# Run the deployment script
./deploy.sh
```

#### Option B: Manual Release

```bash
# Update version in composer.json
# Update CHANGELOG.md

# Run tests
composer test

# Commit changes
git add .
git commit -m "chore: release version 1.0.0"

# Create and push tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin main
git push origin v1.0.0
```

### 6. **Verify GitHub Actions**

1. Go to your GitHub repository
2. Check the "Actions" tab
3. Verify that tests are passing
4. Check that the release was created automatically

### 7. **Publish to Packagist (Optional)**

To make your package available via `composer require`, publish to Packagist:

1. Go to [Packagist](https://packagist.org)
2. Click "Submit Package"
3. Enter your GitHub repository URL
4. Follow the verification process
5. Your package will be available via Composer

## 🔧 Configuration

### GitHub Actions

The package includes GitHub Actions workflows that will:

- **Tests**: Run on every push and pull request
- **Release**: Automatically create releases when you push tags

### Environment Variables

If you want to use advanced features, set these in your GitHub repository:

1. Go to Settings → Secrets and variables → Actions
2. Add any required secrets (API keys, etc.)

## 📦 Version Management

### Semantic Versioning

Use semantic versioning for releases:
- `1.0.0` - Major release (breaking changes)
- `1.1.0` - Minor release (new features)
- `1.0.1` - Patch release (bug fixes)

### Release Process

1. **Development**: Work on `develop` branch
2. **Testing**: Create pull requests to `main`
3. **Release**: Use deployment script to create tags
4. **Automation**: GitHub Actions handles the rest

## 🧪 Testing Before Release

Always test your package before releasing:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Test in a real Laravel application
# Create a test Laravel app and install your package
```

## 📚 Documentation

Ensure your documentation is complete:

- ✅ README.md with installation instructions
- ✅ API.md with endpoint documentation
- ✅ CHANGELOG.md with version history
- ✅ CONTRIBUTING.md for contributors
- ✅ LICENSE file

## 🚨 Common Issues

### Tests Failing
- Check PHP version compatibility
- Verify all dependencies are installed
- Check for syntax errors

### GitHub Actions Not Working
- Ensure workflows are in `.github/workflows/`
- Check repository permissions
- Verify YAML syntax

### Package Not Found
- Ensure repository is public
- Check composer.json syntax
- Verify package name is correct

## 🎉 Success Checklist

Before considering your package ready:

- [ ] All tests pass
- [ ] Documentation is complete
- [ ] GitHub Actions are working
- [ ] Release is created successfully
- [ ] Package can be installed via Composer
- [ ] README has clear installation instructions
- [ ] API documentation is accurate
- [ ] License is appropriate

## 📞 Support

If you encounter issues:

1. Check the GitHub Issues section
2. Review the CONTRIBUTING.md file
3. Ensure all prerequisites are met
4. Verify your Git and GitHub setup

---

**Congratulations!** 🎉 Your Laravel Phone Auth Package is now ready for the world to use! 