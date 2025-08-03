#!/bin/bash

# Laravel Phone Auth Package Deployment Script
# This script automates the release process

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Laravel Phone Auth Package Deployment${NC}"

# Check if we're on main branch
if [[ $(git branch --show-current) != "main" ]]; then
    echo -e "${RED}❌ Error: You must be on the main branch to deploy${NC}"
    exit 1
fi

# Check if there are uncommitted changes
if [[ -n $(git status --porcelain) ]]; then
    echo -e "${RED}❌ Error: You have uncommitted changes. Please commit or stash them first.${NC}"
    exit 1
fi

# Get current version from composer.json
CURRENT_VERSION=$(grep '"version"' composer.json | sed 's/.*"version": "\(.*\)",/\1/')

if [[ -z "$CURRENT_VERSION" ]]; then
    echo -e "${RED}❌ Error: Could not find version in composer.json${NC}"
    exit 1
fi

echo -e "${YELLOW}📦 Current version: $CURRENT_VERSION${NC}"

# Ask for new version
read -p "Enter new version (e.g., 1.0.0): " NEW_VERSION

if [[ -z "$NEW_VERSION" ]]; then
    echo -e "${RED}❌ Error: Version cannot be empty${NC}"
    exit 1
fi

# Validate version format
if ! [[ $NEW_VERSION =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    echo -e "${RED}❌ Error: Invalid version format. Use semantic versioning (e.g., 1.0.0)${NC}"
    exit 1
fi

echo -e "${YELLOW}🔄 Updating version to $NEW_VERSION...${NC}"

# Update version in composer.json
sed -i "s/\"version\": \"$CURRENT_VERSION\"/\"version\": \"$NEW_VERSION\"/" composer.json

# Update CHANGELOG.md
echo -e "${YELLOW}📝 Updating CHANGELOG.md...${NC}"
TODAY=$(date +%Y-%m-%d)
sed -i "s/## \[Unreleased\]/## \[Unreleased\]\n\n## \[$NEW_VERSION\] - $TODAY/" CHANGELOG.md

# Run tests
echo -e "${YELLOW}🧪 Running tests...${NC}"
composer test

if [[ $? -ne 0 ]]; then
    echo -e "${RED}❌ Tests failed. Aborting deployment.${NC}"
    exit 1
fi

# Commit changes
echo -e "${YELLOW}💾 Committing changes...${NC}"
git add .
git commit -m "chore: release version $NEW_VERSION"

# Create and push tag
echo -e "${YELLOW}🏷️  Creating tag v$NEW_VERSION...${NC}"
git tag -a "v$NEW_VERSION" -m "Release version $NEW_VERSION"
git push origin main
git push origin "v$NEW_VERSION"

echo -e "${GREEN}✅ Successfully deployed version $NEW_VERSION!${NC}"
echo -e "${YELLOW}📋 Next steps:${NC}"
echo -e "  1. Check GitHub Actions for automated release"
echo -e "  2. Update documentation if needed"
echo -e "  3. Share the release with the community" 