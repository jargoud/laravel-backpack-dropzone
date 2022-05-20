<?php

namespace Jargoud\LaravelBackpackDropzone\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dropzone implements Rule
{
    /**
     * @var array
     */
    protected $mimeTypes;
    /**
     * @var callable|null
     */
    protected $isExistingUrlCallback;

    /**
     * Create a new rule instance.
     *
     * @param array $mimeTypes
     */
    public function __construct(array $mimeTypes = [], callable $isExistingUrlCallback = null)
    {
        $this->mimeTypes = $mimeTypes;
        $this->isExistingUrlCallback = $isExistingUrlCallback;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
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
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans(
            'validation.mimetypes',
            [
                'values' => implode(',', $this->mimeTypes),
            ]
        );
    }
}
