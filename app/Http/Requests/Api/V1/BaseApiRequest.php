<?php

namespace app\Http\Requests\Api\V1;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseApiRequest extends FormRequest
{
    use ResponseTrait;

    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        if (in_array(array_keys($validator->errors()->toArray())[0],
            ['blocked', 'needActive', 'go_to_complete_data'])) {
            $key = array_keys($validator->errors()->toArray())[0];
            $code = 403;
        } else {
            $code = 422;
            $key = 'fail';
        }
        throw new HttpResponseException($this->jsonResponse(msg: $validator->errors()->first(), code: $code,
            error: true, errors: $validator->errors()->toArray(), key: $key));
    }

    protected function mimesImage(): string
    {
        $extension = [
            'gif',
            'jpeg',
            'png',
            'swf',
            'psd',
            'bmp',
            'jpg',
            'tiff',
            'tiff',
            'jpc',
            'jp2',
            'jpf',
            'jb2',
            'swc',
            'aiff',
            'wbmp',
            'xbm',
            'webp'
        ];

        return implode(',', $extension);
    }

    protected function mimesVideo(): string
    {
        $extension = [
            'mp4',
            'avi',
            'mov',
            'wmv',
            'flv',
            'mkv',
            'webm',
            '3gp',
            'ogv',
            'mpeg',
            'm4v',
            'ts',
            'f4v',
            'swf',
            'vob',
            'asf',
        ];
        return implode(',', $extension);
    }

    protected function mimesDocument(): string
    {
        $extension = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        return implode(',', $extension);
    }

    protected function mimesAudio(): string
    {
        $extension = ['mp3', 'mp3', 'mp3'];
        return implode(',', $extension);
    }

    protected function languages(): string
    {
        return implode(',', languages());
    }

    protected function deviceTypes(): string
    {
        return implode(',', ['android', 'ios', 'web']);
    }
}
