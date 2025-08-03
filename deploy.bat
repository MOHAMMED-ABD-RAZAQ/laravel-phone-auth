@echo off
setlocal enabledelayedexpansion

REM Laravel Phone Auth Package Deployment Script for Windows
REM This script automates the release process

echo 🚀 Laravel Phone Auth Package Deployment

REM Check if we're on main branch
for /f "tokens=*" %%i in ('git branch --show-current') do set CURRENT_BRANCH=%%i
if not "%CURRENT_BRANCH%"=="main" (
    echo ❌ Error: You must be on the main branch to deploy
    exit /b 1
)

REM Check if there are uncommitted changes
git status --porcelain >nul 2>&1
if %errorlevel% equ 0 (
    echo ❌ Error: You have uncommitted changes. Please commit or stash them first.
    exit /b 1
)

REM Get current version from composer.json
for /f "tokens=2 delims=:," %%i in ('findstr "version" composer.json') do set CURRENT_VERSION=%%i
set CURRENT_VERSION=!CURRENT_VERSION:"=!

if "!CURRENT_VERSION!"=="" (
    echo ❌ Error: Could not find version in composer.json
    exit /b 1
)

echo 📦 Current version: !CURRENT_VERSION!

REM Ask for new version
set /p NEW_VERSION="Enter new version (e.g., 1.0.0): "

if "!NEW_VERSION!"=="" (
    echo ❌ Error: Version cannot be empty
    exit /b 1
)

echo 🔄 Updating version to !NEW_VERSION!...

REM Update version in composer.json (Windows compatible)
powershell -Command "(Get-Content composer.json) -replace '\"version\": \"!CURRENT_VERSION!\"', '\"version\": \"!NEW_VERSION!\"' | Set-Content composer.json"

REM Update CHANGELOG.md
echo 📝 Updating CHANGELOG.md...
for /f "tokens=1-3 delims=/ " %%a in ('date /t') do set TODAY=%%a-%%b-%%c
powershell -Command "(Get-Content CHANGELOG.md) -replace '## \[Unreleased\]', '## [Unreleased]`n`n## [!NEW_VERSION!] - !TODAY!' | Set-Content CHANGELOG.md"

REM Run tests
echo 🧪 Running tests...
composer test
if %errorlevel% neq 0 (
    echo ❌ Tests failed. Aborting deployment.
    exit /b 1
)

REM Commit changes
echo 💾 Committing changes...
git add .
git commit -m "chore: release version !NEW_VERSION!"

REM Create and push tag
echo 🏷️  Creating tag v!NEW_VERSION!...
git tag -a "v!NEW_VERSION!" -m "Release version !NEW_VERSION!"
git push origin main
git push origin "v!NEW_VERSION!"

echo ✅ Successfully deployed version !NEW_VERSION!!
echo 📋 Next steps:
echo   1. Check GitHub Actions for automated release
echo   2. Update documentation if needed
echo   3. Share the release with the community

pause 