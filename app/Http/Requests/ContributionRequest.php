<?php

namespace App\Http\Requests;

use Request;
use Illuminate\Foundation\Http\FormRequest;

class ContributionRequest extends FormRequest
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
        $email = $this->request->get('email');
        $rulesGuest = [];
        if (isset($email)) {
            $rulesGuest = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
            ];
        }

        $rules = [
            'campaign_id' => 'required|numeric|exists:campaigns,id',
            'amount' => 'required|amount:amount',
            'description' => 'required',
        ];

        return array_merge($rules, $rulesGuest);
    }

    public function messages()
    {
        return [
            'amount.amount' => trans('campaign.validate.amount'),
        ];
    }
}
