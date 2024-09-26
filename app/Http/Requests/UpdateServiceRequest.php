<?php

namespace App\Http\Requests;

use App\Service;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateServiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        $is_payable = $this->input('is_payable');
        if(!isset($is_payable)){
            return [
                'name'       => [
                    'required',
                ],
                'description'   => [
                    'required',
                ],
                'service_type' => [
                    'required',
                ],
                'category_id' => [
                    'required',
                    'integer',
                ],
                'cost' => [
                    'required',
                ],
                'contact_info' => [
                    'required',
                ],
                'currency' => [
                    'required',
                ],
                'status' => [
                    'required',
                ],
            ];
        }else{
            return [
                'name'       => [
                    'required',
                ],
                'description'   => [
                    'required',
                ],
                'service_type' => [
                    'required',
                ],
                'category_id' => [
                    'required',
                    'integer',
                ],
                'contact_info' => [
                    'required',
                ],
                'currency' => [
                    'required',
                ],
                'status' => [
                    'required',
                ],
            ];
        }
    }
}
