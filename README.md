# Laravel Backpack Dropzone

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jargoud/laravel-backpack-dropzone.svg?style=flat-square)](https://packagist.org/packages/jargoud/laravel-backpack-dropzone)
[![Build Status](https://img.shields.io/travis/jargoud/laravel-backpack-dropzone/master.svg?style=flat-square)](https://travis-ci.org/jargoud/laravel-backpack-dropzone)
[![Quality Score](https://img.shields.io/scrutinizer/g/jargoud/laravel-backpack-dropzone.svg?style=flat-square)](https://scrutinizer-ci.com/g/jargoud/laravel-backpack-dropzone)
[![Total Downloads](https://img.shields.io/packagist/dt/jargoud/laravel-backpack-dropzone.svg?style=flat-square)](https://packagist.org/packages/jargoud/laravel-backpack-dropzone)

This package provides a [Dropzone](https://www.dropzonejs.com/) field
for [Laravel Backpack](http://backpackforlaravel.com).

## Installation

You can install the package via composer:

```bash
composer config repositories.laravel-backpack-dropzone vcs https://github.com/jargoud/laravel-backpack-dropzone.git
composer require jargoud/laravel-backpack-dropzone
```

Then, publish the package assets and config:

```shell
php artisan vendor:publish --provider="Jargoud\LaravelBackpackDropzone\Providers\LaravelBackpackDropzoneServiceProvider"
```

As this package relies on [pionl/laravel-chunk-upload](https://github.com/pionl/laravel-chunk-upload), you can publish
its config:

```shell
php artisan vendor:publish --provider="Pion\Laravel\ChunkUpload\Providers\ChunkUploadServiceProvider"
```

## Usage

In your Backpack CRUD controller, add a dropzone field:

``` php
CRUD::addField([
    'name' => 'video',
    'type' => 'dropzone',
    'view_namespace' => 'dropzone::fields',
    'allow_multiple' => false, // false if missing key
    'config' => [
        // any option from the Javascript library
        'chunkSize' => 1024 * 1024 * 2, // for 2 MB
        'chunking' => true,
    ],
]);
```

Library options can be found on [Dropzone documentation](https://www.dropzonejs.com/#configuration-options).

In your request, to validate the input value, use our [Dropzone](./src/Rules/Dropzone.php) rule:

```php
use Jargoud\LaravelBackpackDropzone\Rules\Dropzone;

public function rules(): array {
    return [
        'video' => [
            new Dropzone(['video/mp4']),
        ],
        'photos' => [
            'array',
        ],
        'photos.*' => [
            new Dropzone(['image/png'], [MyModel::find($this->id), "isPhotoFileExisting"]),
        ],
    ];
}
```

It works like Laravel's `mimetypes` validation rule and needs an array of mime types.

Then, uploaded file is stored in `storage/app/upload`. It can be handled through Laravel's filesystem:

```php
\Storage::disk(config('chunk-upload.storage.disk'))->path($filePath);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Development

### Assets

To compile assets, run the following commands:

```shell
npm install
npm run mix
```

It will generate CSS and JS files in [resources](./resources) directory.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email jeremy.argoud@gmail.com instead of using the issue tracker.

## Credits

- [Jérémy Argoud](https://github.com/jargoud)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
