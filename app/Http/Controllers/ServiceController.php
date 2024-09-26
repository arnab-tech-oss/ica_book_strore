<?php

namespace App\Http\Controllers;

use App\Service;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Notifications\CommentEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $services = Service::with('category')->where('status', '1')->orderBy('category_id')->get();
        return view('services.index', compact('services'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!empty($id) && is_string($id)) {
            $name = str_replace("-", ' ',str_replace("--", '/', $id));
            $service = Service::with('category')->where('name', $name)->firstOrFail();
            return view('services.service', compact('service'));
        }
    }
}
