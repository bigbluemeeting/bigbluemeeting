<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingsRequest extends FormRequest
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
            'name' => 'required|max:50',
            'maximum_people' => 'required|integer|min:2',
            'meeting_description' =>'required',
            'welcome_message' =>'required',
//            'startTime' => 'date_format:h:i A'
        ];
    }
    public function messages()
    {
        return [
            'name.required' =>'Meeting Name Required',
            'name.max' =>'Maximum 50 Characters Allowed For Meeting Name',
            'maximum_people.required' =>'Maximum People Required',
            'maximum_people.integer' =>'Maximum People Field Only Accept Numbers ',
            'maximum_people.min' =>'Minimum Two Person Required For Meeting',
            'meeting_description.required' =>'Meeting Description Required',
            'welcome_message.required' =>'Welcome Message Required For Meeting'

        ];
    }
}
