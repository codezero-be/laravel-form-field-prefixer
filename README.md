# Laravel Form Field Prefixer

[![GitHub release](https://img.shields.io/github/release/codezero-be/laravel-form-field-prefixer.svg)]()
[![License](https://img.shields.io/packagist/l/codezero/laravel-form-field-prefixer.svg)]()
[![Build Status](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/badges/build.png?b=master)](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/codezero-be/laravel-form-field-prefixer/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/codezero/laravel-form-field-prefixer.svg)](https://packagist.org/packages/codezero/laravel-form-field-prefixer)

[![ko-fi](https://www.ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/R6R3UQ8V)

#### Reuse form partials and automatically add optional prefixes and array keys to your form fields.

## ✅ Requirements

- PHP >= 7.1
- Laravel >= 5.6

## 📦 Install

```bash
composer require codezero/laravel-form-field-prefixer
```

> Laravel will automatically register the ServiceProvider.

## ⚙️ Configure

#### ☑️ Publish Configuration File

```bash
php artisan vendor:publish --provider="CodeZero\FormFieldPrefixer\FormFieldPrefixerServiceProvider" --tag="config"
```

You will now find a `form-field-prefixer.php` file in the `config` folder.

## 🚧 Testing

```bash
composer test
```

## ☕️ Credits

- [Ivan Vermeyen](https://byterider.io)
- [All contributors](../../contributors)

## 🔓 Security

If you discover any security related issues, please [e-mail me](mailto:ivan@codezero.be) instead of using the issue tracker.

## 📑 Changelog

See a list of important changes in the [changelog](CHANGELOG.md).

## 📜 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
