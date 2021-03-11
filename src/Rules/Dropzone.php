<?php

namespace Jargoud\LaravelBackpackDropzone\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class Dropzone implements Rule
{
    /**
     * @var array
     */
    protected $mimeTypes;

    /**
     * Create a new rule instance.
     *
     * @param array $mimeTypes
     */
    public function __construct(array $mimeTypes = [])
    {
        $this->mimeTypes = $mimeTypes;
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
            config('chunk-upload.storage.disk')
        );

        return $disk->exists($value)
            && (empty($this->mimeTypes) || in_array($disk->mimeType($value), $this->mimeTypes));
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
