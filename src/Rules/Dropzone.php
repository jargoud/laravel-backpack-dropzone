<?php

namespace Jargoud\LaravelBackpackDropzone\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dropzone implements Rule
{
    public function __construct(protected array $mimeTypes = [], protected ?Closure $isExistingUrlCallback = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        $disk = Storage::disk(
            config('dropzone.storage.destination_disk')
        );

        if ($disk->exists($value)) {
            return empty($this->mimeTypes) || in_array($disk->mimeType($value), $this->mimeTypes);
        }

        if (!empty($this->isExistingUrlCallback)) {
            $callback = $this->isExistingUrlCallback;
            return $callback($value);
        }

        $publicRelativePath = Str::replaceFirst(url("/"), "", $value);
        $publicAbsolutePath = public_path($publicRelativePath);

        return file_exists($publicAbsolutePath);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return trans(
            'validation.mimetypes',
            [
                'values' => implode(',', $this->mimeTypes),
            ]
        );
    }
}
