<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Invoices;
use App\Media;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Facades\Invoice;

class FileManagerController extends Controller
{
    public function index()
    {
        $data = Media::paginate(12);
        return view('admin.file_manager.index', ['data' => $data]);
    }
    public function get_file(Request $request)
    {
        $search = $request->search;
        $data = Media::where('name', 'LIKE', '%' . $search . '%')->orWhere('file_name', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%')->orWhere('mime_type', 'LIKE', '%' . $search . '%')->paginate(50);
        return view('admin.file_manager.get_file',['data'=>$data]);
    }
}
