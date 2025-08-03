# Contributing to Laravel Phone Auth Package

Thank you for your interest in contributing to the Laravel Phone Auth Package! This document provides guidelines for contributing to this project.

## 🚀 Getting Started

### Prerequisites

- PHP 8.0 or higher
- Composer
- Git

### Installation

1. Fork the repository
2. Clone your fork:
   ```bash
   git clone https://github.com/your-username/laravel-phone-auth.git
   cd laravel-phone-auth
   ```
3. Install dependencies:
   ```bash
   composer install
   ```

## 🧪 Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run specific test suite
vendor/bin/phpunit --testsuite=Unit
vendor/bin/phpunit --testsuite=Feature
```

## 📝 Development Guidelines

### Code Style

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add proper PHPDoc comments
- Keep methods small and focused

### Commit Messages

Use conventional commit format:
```
type(scope): description

feat(auth): add phone verification endpoint
fix(otp): resolve negative time calculation issue
docs(readme): update installation instructions
```

### Pull Request Process

1. Create a feature branch from `develop`
2. Make your changes
3. Add tests for new functionality
4. Ensure all tests pass
5. Update documentation if needed
6. Submit a pull request

## 🐛 Reporting Issues

When reporting issues, please include:

- PHP version
- Laravel version
- Package version
- Steps to reproduce
- Expected vs actual behavior
- Error logs (if applicable)

## 📦 Release Process

### For Maintainers

1. Update version in `composer.json`
2. Update `CHANGELOG.md`
3. Create and push a new tag:
   ```bash
   git tag v1.0.0
   git push origin v1.0.0
   ```
4. GitHub Actions will automatically create a release

## 🤝 Code of Conduct

- Be respectful and inclusive
- Focus on the code, not the person
- Help others learn and grow
- Welcome new contributors

## 📄 License

By contributing, you agree that your contributions will be licensed under the MIT License. 