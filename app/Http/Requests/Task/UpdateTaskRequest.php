<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required | integer | exists:tasks,id',
            'title' => 'nullable | max:255',
            'description' => 'nullable',
            'status' => 'nullable | integer|in:' . implode(',', [
                    TaskStatus::pending,
                    TaskStatus::in_progress,
                    TaskStatus::completed,
                    TaskStatus::canceled,
                ]),
        ];
    }


    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.exists' => 'The task with the given id does not exist. ',
            'status.in' => 'The status must be 1, 2, 3, or 4.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(returnError($validator->errors()->all()));
    }
}
