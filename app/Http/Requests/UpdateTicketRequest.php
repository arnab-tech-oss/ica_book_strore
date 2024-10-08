<?php

namespace App\Http\Requests;

use App\Ticket;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateTicketRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'       => [
                'required',
            ],
            'status_id'   => [
                'required',
                'integer',
            ],
            'service_id' => [
                'required',
                'array',
            ],
        ];
    }
}
