<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyServiceRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Role;
use App\RoleUser;
use App\Service;
use App\User;
use Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Mail\AddserviceMail;


class ServicesController extends Controller
{
    use MediaUploadingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        /*
        if ($request->ajax()) {
            $query = Service::select(sprintf('%s.*', (new Service)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'service_show';
                $editGate      = 'service_edit';
                $deleteGate    = 'service_delete';
                $crudRoutePart = 'services';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->addColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->addColumn('service_type', function ($row) {
                return $row->service_type ? $row->service_type : '';
            });

            $table->addColumn('category_Name', function ($row) {
                return $row->category ? $row->category->category_name : '';
            });
            $table->addColumn('cost', function ($row) {
                return $row->cost ? $row->cost : '';
            });

            $table->addColumn('contact_info', function ($row) {
                return $row->contact_info ? $row->contact_info : '';
            });
            $table->addColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });

            $table->addColumn('view_link', function ($row) {
                return route('admin.services.show', $row->id);
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }*/
        $services = Service::with(['category'])->orderBy('created_at', 'desc')->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = Category::orderBy('created_at', 'desc')->get();
        $deptAdmin = config('constant.department_admin_role_id');
        $users = User::where("status", '1')->join('role_user', 'user_id', 'id')->whereIn('role_user.role_id', [$deptAdmin])->get();
        $parentServices = Service::where('parent_service_id', null)->where('status', '1')->pluck('name', 'id');
        return view('admin.services.create', compact('categories', 'users', 'parentServices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $request)
    {
        $fullPath = null;
        $sub_category = $request->input('sub_category');
        if (isset($sub_category)) {
            $serviceId = $request->input('parent_service_id');
            $service = Service::where("id", $serviceId)->first();
            $request['category_id'] = $service->category_id;
            $request['contact_info'] = $service->contact_info;
            $request['assigned_user'] = $service->assigned_user;
            //$request['attachments'] = $service->banner;
        } else {
            $request['parent_service_id'] = null;
        }
        $str = $request->input('cost');
        $member_cost = $request->input('member_cost');
        if (!empty($str)) {
            if (str_contains($request->input('cost'), '₹')) {
                $str = str_replace('₹', '', $request->input('cost'));
            }
            if (str_contains($request->input('cost'), '$')) {
                $str = str_replace('$', '', $request->input('cost'));
            }
            if (!is_numeric($str)) {
                Session::flash('message', "Please enter valid cost value");
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin.services.index');
            }
            $request->merge(['cost' => $str]);
        }
        if (!empty($member_cost)) {
            if (str_contains($request->input('member_cost'), '₹')) {
                $member_cost = str_replace('₹', '', $request->input('member_cost'));
            }
            if (str_contains($request->input('member_cost'), '$')) {
                $member_cost = str_replace('$', '', $request->input('member_cost'));
            }
            if (!is_numeric($str)) {
                Session::flash('message', "Please enter valid member cost value");
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin.services.index');
            }
            $request->merge(['member_cost' => $member_cost]);
        }
        //dd($request->all());
        // if ($request->attachments) {
        //     foreach ($request->attachments as $attachment) {
        //         $imageName = time() . '.' . $attachment->extension();
        //         return $request->all();
        //         $attachment->move(public_path('images/product'), $imageName);
        //         $fullPath = 'images/product/' . $imageName;
        //         // Save each attachment path if necessary, depending on your logic
        //     }
        // }
        foreach ($request->input('attachments', []) as $file) {
            $filePath = 'tmp/uploads/' . $file;
            $fileName = basename($filePath);
            $publicPath = 'images/product/' . $fileName;

            // Check if the file exists in the storage directory
            if (Storage::disk('local')->exists($filePath)) {
                // Move the file to the public directory
                Storage::disk('public')->putFileAs('images/product', $filePath, $fileName);

                // Optionally, delete the file from the storage directory
                Storage::disk('local')->delete($filePath);
            }
        }

        $request->request->add(['reg_no' => 'ICCTRD-SRV-' . date('y') . '-000' . Service::count('*') + 1, 'banner' => $fileName]);

        $service = Service::create($request->all());
        $check = User::where('id', $request->assigned_user)->first();
        if ($check->email !== null) {
            $this->service_add($check->email, $check->id);
        }
        // foreach ($request->input('attachments', []) as $file) {
        //     $service->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        // }


        return redirect()->route('admin.services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        abort_if(Gate::denies('service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service->load('category');
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        abort_if(Gate::denies('service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = Category::orderBy('created_at', 'desc')->get();
        $deptAdmin = config('constant.department_admin_role_id');
        $users = User::where("status", '1')->join('role_user', 'user_id', 'id')->whereIn('role_user.role_id', [$deptAdmin])->get();
        $parentServices = Service::where('parent_service_id', null)->where('status', '1')->pluck('name', 'id');
        return view('admin.services.edit', compact('service', 'categories', 'users', 'parentServices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $sub_category = $request->input('sub_category');
        if (isset($sub_category)) {
            $serviceId = $request->input('parent_service_id');
            $ser = Service::where("id", $serviceId)->first();
            $request['category_id'] = $ser->category_id;
            $request['contact_info'] = $ser->contact_info;
        } else {
            $request['parent_service_id'] = null;
        }
        $str = $request->input('cost');
        $member_cost = $request->input('member_cost');
        if (!empty($str)) {
            if (str_contains($request->input('cost'), '₹')) {
                $str = str_replace('₹', '', $request->input('cost'));
            }
            if (str_contains($request->input('cost'), '$')) {
                $str = str_replace('$', '', $request->input('cost'));
            }
            if (!is_numeric($str)) {
                Session::flash('message', "Please enter valid cost value");
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin.services.index');
            }
            $request->merge(['cost' => $str]);
        }
        if (!empty($member_cost)) {
            if (str_contains($request->input('member_cost'), '₹')) {
                $member_cost = str_replace('₹', '', $request->input('member_cost'));
            }
            if (str_contains($request->input('member_cost'), '$')) {
                $member_cost = str_replace('$', '', $request->input('member_cost'));
            }
            if (!is_numeric($str)) {
                Session::flash('message', "Please enter valid member cost value");
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin.services.index');
            }
            $request->merge(['member_cost' => $member_cost]);
        }
        $service->update($request->except('attachments'));
        // if (isset($service->attachments) && $request->attachments != ''  &&  count($service->attachments) > 0) {

        //     foreach ($service->attachments as $media) {
        //         if (!in_array($media->file_name, $request->input('attachments', []))) {
        //             $media->delete();
        //         }
        //     }
        // }
        // $media = $service->attachments->pluck('banner')->toArray();
        // foreach ($request->input('attachments', []) as $file) {
        //     if (count($media) === 0 || !in_array($file, $media)) {
        //         $service->addMedia(public_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        //     }
        // }

        foreach ($request->input('attachments', []) as $file) {
            $filePath = 'tmp/uploads/' . $file;
            $fileName = basename($filePath);
            $publicPath = 'images/product/' . $fileName;

            // Check if the file exists in the storage directory
            if (Storage::disk('local')->exists($filePath)) {
                // Move the file to the public directory
                Storage::disk('public')->putFileAs('images/product', $filePath, $fileName);
                // Optionally, delete the file from the storage directory
                // Storage::disk('local')->delete($filePath);
            }
            $service->update([
                'banner' => $fileName
            ]);
        }

        return redirect()->route('admin.services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        abort_if(Gate::denies('service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $service->delete();

        return back();
    }

    public function massDestroy(MassDestroyServiceRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function service_add($email, $id)
    {
        $data = Service::where('id', $id)->first();
        try {
            $maildata = [
                // 'name' => $data->name,
                // 'phone' => $data->phone,
                'app_name' => "Ica Trade Desk",
            ];

            Mail::to($email)->send(new AddserviceMail($maildata));
        } catch (Throwable $t) {
            Log::error('Mail sending failed: ' . $t->getMessage());
            throw $t;
        }
    }
}
