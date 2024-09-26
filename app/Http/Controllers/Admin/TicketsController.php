<?php
namespace App\Http\Controllers\Admin;

use App\Bill;
use App\BillTicketServices;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Link;
use App\Mail\FeedbackUrlMail;
use App\Mail\SupportRequestMail;
use App\Media;
use Illuminate\Support\Facades\Mail;
use App\Notifications\DataChangeEmailNotification;
use App\Payments;
use App\Priority;
use App\Role;
use App\RoleUser;
use App\Service;
use App\Status;
use App\Tags;
use App\Ticket;
use App\TicketFeedBackUrl;
use App\TicketServices;
use App\TicketsTags;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Api;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class TicketsController extends Controller
{
    use MediaUploadingTrait;

    public $cc = null, $bcc = null;

    public function __construct()
    {
        $setting = config('app.setting');
        if (!empty($setting)) {
            $this->cc = explode(',',$setting['email_cc']);
            $this->bcc = explode(',',$setting['email_bcc']);
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ticket::with(['ticketServices', 'status', 'priority', 'category', 'assigned_to_user', 'createdBy', 'comments'])
                ->filterTickets($request);
            if (auth()->user()->isUser()) {
                $query->where('created_by', Auth::user()->id);
            } else if (!auth()->user()->isAdmin() && auth()->user()->isDeptUser()) {
                $query->where('assigned_to_user_id', Auth::user()->id);
            }
            $query->select(sprintf('%s.*', (new Ticket)->table));

            $table = Datatables::of($query);
           $table ->addIndexColumn();

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'ticket_show';
                $editGate = 'ticket_edit';
                $deleteGate = 'ticket_delete';
                $crudRoutePart = 'tickets';

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
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->editColumn('service', function ($row) {
                return $row->ticketServices != null ? $row->ticketServices->toArray() : "";
            });
            $table->editColumn('serviceIds', function ($row) {
                return $row->ticketServices != null ? $row->ticketServices->pluck('id')->toArray() : "";
            });
            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('priority_name', function ($row) {
                return $row->priority ? $row->priority->name : '';
            });
            $table->addColumn('priority_color', function ($row) {
                return $row->priority ? $row->priority->color : '#000000';
            });

            $table->addColumn('created_by', function ($row) {
                return $row->createdBy ? $row->createdBy->name : '';
            });

            $table->addColumn('assigned_to_user', function ($row) {
                return $row->assigned_to_user ? $row->assigned_to_user->name : ' - ';
            });

            $table->addColumn('comments_count', function ($row) {
                return $row->comments->count();
            });

            $table->addColumn('view_link', function ($row) {
                return route('admin.tickets.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'priority', 'category', 'assigned_to_user']);

            return $table->make(true);
        }
        $statuses = Status::all();
        $services = Service::all();

        $user = Auth::user();
        $notIn = Service::where('parent_service_id', '!=', null)
            ->pluck('parent_service_id')->toArray();
        $serviceCards = Service::whereNotIn('id', $notIn)
            ->where('status', '1')
            ->where('currency', $user->currency)
            ->get();

        return view('admin.tickets.index', compact('statuses', 'services', 'serviceCards'));
    }

    public function create()
    {
        // abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        //$services = Service::where('status', '1')->orderBy('created_at', 'desc')->get();
//        $notIn = Service::where('parent_service_id', '!=', null)
//            ->pluck('parent_service_id')->toArray();
//        $services = Service::whereNotIn('id', $notIn)
//            ->where('status', '1')
//            ->get();
        $user = Auth::user();
        // if ($user->isAdmin()) {
            $notIn = Service::where('parent_service_id', '!=', null)
                ->pluck('parent_service_id')->toArray();
            $services = Service::whereNotIn('id', $notIn)
                ->orderBy('created_at', 'desc')
                ->where('status', '1');
            $serviceIds = $services->pluck('id')->toArray();

            $assigned_to_users = $services->pluck('assigned_user')->toArray();
            $services = $services->get();
        // } else {

        //     $notIn = Service::where('parent_service_id', '!=', null)
        //         ->pluck('parent_service_id')->toArray();
        //     $services = Service::whereNotIn('id', $notIn)
        //         ->where('status', '1')
        //         ->orderBy('created_at', 'desc')
        //         ->where('currency', $user->currency);
        //     $serviceIds = $services->pluck('id')->toArray();
        //     $assigned_to_users = $services->pluck('assigned_user')->toArray();
        //     $services = $services->get();
        // }

        $assigned_to_users = User::where("status",'1')->whereHas('roles', function ($query) {
            $query->whereIn('role_id', [config('constant.department_user_role_id'), config('constant.department_admin_role_id')]);
        })
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $terms = \App\Pages::where('page_name','terms_and_condition')->first();

        return view('admin.tickets.create', compact('statuses', 'priorities', 'services', 'assigned_to_users','terms'));
    }

    public function createWithService($id)
    {
        // abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = Auth::user();
        if ($user->isAdmin()) {
            $notIn = Service::where('parent_service_id', '!=', null)
                ->pluck('parent_service_id')->toArray();
            $services = Service::whereNotIn('id', $notIn)
                ->orderBy('created_at', 'desc')
                ->where('status', '1');
            $serviceIds = $services->pluck('id')->toArray();

            $assigned_to_users = $services->pluck('assigned_user')->toArray();
            $services = $services->get();
        } else {
            $notIn = Service::where('parent_service_id', '!=', null)
                ->pluck('parent_service_id')->toArray();
            $services = Service::whereNotIn('id', $notIn)
                ->where('status', '1')
                ->orderBy('created_at', 'desc')
                ->where('currency', $user->currency);
            $serviceIds = $services->pluck('id')->toArray();
            $assigned_to_users = $services->pluck('assigned_user')->toArray();
            $services = $services->get();
        }
        if (!in_array($id, $serviceIds)) {
            Session::flash('message', "Your selected service is not available for you. please select another service.");
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('home');
        }

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        //$services = Service::where('status', '1')->orderBy('created_at', 'desc')->get();

        $assigned_to_users = User::where("status",'1')->whereIn('id',$assigned_to_users)
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');
        $selectedServiceId = $id;
        $terms = \App\Pages::where('page_name','terms_and_condition')->first();
        return view('admin.tickets.create', compact('terms','statuses', 'priorities', 'services', 'assigned_to_users', 'selectedServiceId'));
    }

    public function store(StoreTicketRequest $request)
    {

        $user_id = null;
        $service_id = $request->input('service_id');
        $assigned_to_user = $request->input('assigned_to_user_id');
        if (is_array($service_id) && count($service_id) > 1) {
            $user_id = config('constant.department_admin_role_id');
        } else {
            if (empty($assigned_to_user)) {
                $service = Service::where('id', $service_id)->first();
                $user_id = $service->assigned_user;
            } else {
                $user_id = $assigned_to_user;
            }
        }
//        //$created_user_id = $request->input('assigned_to_user_id');
//        if (!isset($created_user_id)) {
        $created_user_id = Auth::user()->id;
        //}
        $request->request->add([

            'created_by' => $created_user_id,
            'status_id' => 1, //open

            'reg_no' => 'ICCTRD-SR-' . date('y-m') . '-000' . Ticket::count('*') + 1



        ]);
        $ticket = Ticket::create($request->all());
        $ticket->ticketServices()->sync($request->input('service_id', []));
        $check = User::where('id',$request->assigned_to_user_id)->first();
        if($check !== null && $check->email !== null){
              $this->ticket_request_add($check->email , $check->id);
           }

        //    foreach ($request->input('attachments', []) as $file) {
        //     $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        //      }

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

        $tickets = Ticket::with('status', 'assigned_to_user', 'user')->where('id', $ticket->id)->first();
        $data = [
            'type' => 'Created',
            'name' => $tickets->title ?? '',
            'status' => $ticket->status->name ?? '',
            'assigned_user' => $tickets->assigned_to_user->name ?? '',
            'user' => $tickets->user->name ?? ''
        ];

        // if($tickets->assigned_to_user){
        //     $this->cc = array_merge($this->cc,explode(',',$tickets->assigned_to_user->email));
        // }
        // try {
        //     Mail::to($tickets->user["email"])->send(new SupportRequestMail($data,$this->cc,$this->bcc));
        // }catch(\Exception $e){
        //     Log::info($e->getMessage());
        // }
        if($tickets){
            Session::flash('message', "Ticket data is inserted successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to insert ticket data.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        // abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        //$user = $ticket->createdBy;
        $user = Auth::user();
        $services = null;
        if ($user->isAdmin()) {
            $notIn = Service::where('parent_service_id', '!=', null)
                ->pluck('parent_service_id')->toArray();
            $services = Service::whereNotIn('id', $notIn)
                ->orderBy('created_at', 'desc')
                ->where('status', '1');
            $serviceIds = $services->pluck('id')->toArray();
            $assigned_to_users = $services->pluck('assigned_user')->toArray();
            $services = $services->get();
        } else {
            $notIn = Service::where('parent_service_id', '!=', null)
                ->pluck('parent_service_id')->toArray();
            $services = Service::whereNotIn('id', $notIn)
                ->where('status', '1')
                ->orderBy('created_at', 'desc');
            if (isset($user->currency) && $user->currency != null) {
                $services = $services->where('currency', $user->currency);
            }
            $serviceIds = $services->pluck('id')->toArray();
            $assigned_to_users = $services->pluck('assigned_user')->toArray();
            $services = $services->get();
        }

        $assigned_to_users = User::where("status",'1')->whereHas('roles', function ($query) {
            $query->whereIn('role_id', [config('constant.department_user_role_id'), config('constant.department_admin_role_id')]);
        })
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $ticket->load('ticketServices', 'status', 'priority', 'category', 'assigned_to_user');
        $terms = \App\Pages::where('page_name','terms_and_condition')->first();
        return view('admin.tickets.edit', compact('terms','statuses', 'priorities', 'services', 'assigned_to_users', 'ticket'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $user_id = null;
        $service_id = $request->input('service_id');
        $assigned_to_user = $request->input('assigned_to_user_id');
        if (is_array($service_id) && count($service_id) > 1) {
            $user_id = config('constant.department_admin_role_id');
        } else {
            if (empty($assigned_to_user)) {
                $service = Service::where('id', $service_id)->first();
                $user_id = $service->assigned_user;
            } else {
                $user_id = $assigned_to_user;
            }
        }

        $ticket->update($request->all());
        $ticket->ticketServices()->sync($request->input('service_id', []));

        if (count($ticket->attachments) > 0) {
            foreach ($ticket->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $ticket->attachments->pluck('file_name')->toArray();

        // foreach ($request->input('attachments', []) as $file) {
        //     if (count($media) === 0 || !in_array($file, $media)) {
        //         $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
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

        $tickets = Ticket::with('status', 'assigned_to_user', 'user')->where('id', $ticket->id)->first();
        $users_assign = User::where('id',$tickets->assigned_to_user_id)->withTrashed()->first();
        $data = [
            'type' => 'Updated',
            'name' => $tickets->title,
            'status' => $ticket->status->name,
            'assigned_user' => $users_assign->name ?? "",
            'user' => $tickets->user->name
        ];
        if($tickets->assigned_to_user){
            $this->cc = array_merge($this->cc,explode(',',$tickets->assigned_to_user->email));
        }
        try {
            Mail::to($tickets->user["email"])->send(new SupportRequestMail($data,$this->cc,$this->bcc));
        }catch(\Exception $e){
            Log::info($e->getMessage());
        }
        $data = ['action' => 'Ticket has been updated!', 'model_name' => 'Ticket', 'ticket' => $tickets];
        $users = \App\User::where('id', $tickets->assigned_to_user_id)->get();
        //Notification::send($users, new DataChangeEmailNotification($data));

        if($tickets){
            Session::flash('message', "Ticket data is updated successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to update ticket data.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.tickets.index');
    }

    public function show(Ticket $ticket)
    {
        if (Gate::denies('ticket_show')) {
            abort(response()->json(['message' => '403 Forbidden'], 403));
        }
        $id = auth()->user()->id;

        $ticket->with(['status', 'ticketTags', 'priority', 'category', 'assigned_to_user', 'createdBy', 'comments', 'documents', 'ticketFeedbacks']);
        $role_id = RoleUser::where('user_id', $id)->first();

        if ($ticket->status_id == Status::OPEN_ID && $role_id->role_id != 2) {
            $statusId = Status::where('name', 'WIP')->firstOrFail()->id;
            $ticket->update(['status_id' => $statusId]);
        }

        $ticketTags = $ticket->ticketTags->pluck('tag_id')->toArray();

        return view('admin.tickets.show', compact('ticket', 'ticketTags'));
    }


    public function destroy(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketRequest $request)
    {
        Ticket::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $comment = $request->input('comment_text');
        $status = $request->input('status');
        $tags = $request->input('tags');
        $file_description = $request->input('file_description');
        $file_title = $request->input('file_title');
        $mailsent = 0;
        if (isset($status) && $status == 1) {
            $url = config('constant.base_url') . 'feedback/' . base64_encode($ticket->id);
            $user = User::find($ticket->created_by);
            TicketFeedBackUrl::insert([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->created_by,
                'url' => $url,
                'status' => 'active'
            ]);
            $data = [
                'ticket_id' => $ticket->id,
                'ticket_name' => $ticket->title,
                'user_id' => $ticket->created_by,
                'user_name' => $user->name,
                'url' => $url,
                'base_url' => config('constant.base_url')
            ];
            $cc = null;
            if($ticket->assigned_to_user){
                $cc = array_merge($this->cc,explode(',',$ticket->assigned_to_user->email));
            }
            try {
                Mail::to($user->email)->send(new FeedbackUrlMail($data,$ticket,$cc));
                $mailsent = 1;
            }catch(\Exception $e){
                Log::info($e->getMessage());
            }
        }

        $media = $ticket->documents->pluck('file_name')->toArray();

        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('documents');
            }
        }


        if(!empty($file_description) && !empty($file_title)){
            $media =  Media::where('model_id', $ticket->id)->orderBy('created_at','desc')->first();
            $media->update([
                'name' => $file_title,
                'description' => $file_description
            ]);
        }

        if (isset($tags) && !empty($tags) && is_array($tags)) {
            $ids = [];
            foreach ($tags as $key => $tag) {
                if (is_numeric($tag)) {
                    $ids[] = [
                        'tag_id' => $tag,
                        'ticket_id' => $ticket->id
                    ];
                } else {
                    $tags = Tags::create([
                        'name' => $tag
                    ]);
                    if ($tags != null) {
                        $ids[] = [
                            'tag_id' => $tags->id,
                            'ticket_id' => $ticket->id
                        ];
                    }
                }
            }
            if (count($ids) > 0) {
                TicketsTags::where('ticket_id', $ticket->id)->delete();
                TicketsTags::insert($ids);
            }
        }

        if (!empty($comment)) {
            $user = auth()->user();
            $comment = $ticket->comments()->create([
                'author_name' => $user->name,
                'author_email' => $user->email,
                'user_id' => $user->id,
                'comment_text' => $request->comment_text
            ]);

            $ticket->sendCommentNotification($comment,$ticket);
            if ($mailsent) {
                $ticket = Ticket::where('id',$ticket->id);
                $ticket->update(['status_id' => Status::CUSTOMER_FEEDBACK_AWAITED_ID]);
            }
            return redirect()->back()->withStatus('Your comment added successfully');
        } else {
            if ($mailsent) {
                $ticket = Ticket::where('id',$ticket->id);
                $ticket->update(['status_id' => Status::CUSTOMER_FEEDBACK_AWAITED_ID]);
            }
            Session::flash('message', "Your Service Delivered successfully And Sent Mail");
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        }
    }

    public function getServices(Request $request)
    {
        $userId = $request->input('user_id') ?? null;
        $service_id = $request->input('service_id') ?? null;
        $isUpdate = $request->input('isUpdate') ?? null;
        if (isset($userId)) {
            $user = User::find($userId);
            if ($user->currency != null) {
                $notIn = Service::where('parent_service_id', '!=', null)
                    ->pluck('parent_service_id')->toArray();
                $services = Service::with('childrens')
                    ->whereNotIn('id', $notIn)
                    ->where('status', '1')
                    ->where('currency', $user->currency)
                    ->get();
                return [
                    'services' => $services,
                    'currency' => $user->currency
                ];
            } else {
                $notIn = Service::where('parent_service_id', '!=', null)
                    ->pluck('parent_service_id')->toArray();
                $services = Service::with('childrens')
                    ->whereNotIn('id', $notIn)
                    ->where('status', '1')
                    ->where('currency', '!=', $user->currency)
                    ->get();
                return [
                    'services' => $services,
                    'currency' => 'USD'
                ];
            }
        } else if (isset($service_id) && !empty($service_id)) {
            return response()->json(["data" => Service::where('id', $service_id)->first()]);
        } else {
            $ticket_id = $request->input('id');
            if ($ticket_id) {
                $services = Service::join('service_ticket', 'service_id', 'id')->where('service_ticket.ticket_id', $ticket_id)->get();
                $billsTicket = BillTicketServices::whereIn('service_id', $services->pluck('id'))->where('ticket_id', $ticket_id)->get()->toArray();
                $updatedServices = [];
                foreach ($services as $service) {
                    $exists = array_filter($billsTicket, function ($bill) use ($service) {
                        return $service->id == $bill['service_id'];
                    });
                    if ($exists != null && $isUpdate == null) {
                        continue;
                    }
                    $updatedServices[] = $service;
                }
                return [
                    'services' => collect($updatedServices),
                    'message' => count($updatedServices) == 0 && $isUpdate == null ? 'Selected Service Bill is already generated' : ''
                ];
            }
        }
    }

    public function getResponse(Request $request)
    {
        $payment_id = $request->input('razorpay_payment_id');
        $payment_link_id = $request->input('razorpay_payment_link_id');
        $payment_link_reference_id = $request->input('razorpay_payment_link_reference_id');
        $payment_link_status = $request->input('razorpay_payment_link_status');
        $signature = $request->input('razorpay_signature');
        $api = new Api(config('constant.razorpay_key'), config('constant.razorpay_secret'));
        $payment = $api->payment->fetch($payment_id);
        $cost = ($payment->amount / 100);
        if ($payment) {
            $billId = $payment->notes->bill_id;
          $data=  Payments::insert([
                'payment_id' => $payment_id,
                'payment_link_id' => $payment_link_id,
                'payment_link_reference_id' => $payment_link_reference_id,
                'payment_link_status' => $payment_link_status,
                'signature' => $signature,
                'method' => $payment->method,
                'amount' => $cost,
                'bill_id' => $payment->notes->bill_id
            ]);
            if ($payment->status == 'captured') {
                $bill = Bill::where('id', $billId)->first();
                $paidCost = $cost;
                if ($bill->remaining_cost != 0) {
                    $paidCost = $bill->remaining_cost - $cost;
                } else {
                    $paidCost = $bill->bill_cost - $paidCost;
                }
                $status = Bill::PARTIALLY_PAID;
                if ($bill->bill_cost == $paidCost) {
                    $status = Bill::FULLY_PAID;
                }
                $bill = Bill::where('id', $billId)->update([
                    'remaining_cost' => $paidCost,
                    'status' => $status
                ]);
                $link = Link::where('bill_id', $billId)->update([
                    'payment_status' => $status
                ]);
                Session::flash('message', 'Your Payment is Successfully');
                Session::flash('alert-class', 'alert-success');
                return view('tickets.success',['data'=>$request]);
            } else {
                Session::flash('message', 'Your Payment is Failed');
                Session::flash('alert-class', 'alert-danger');
                return view('tickets.failed');
            }
        }
        return redirect()->route('thank-you')->with("data",$request);
    }

    public function getTags(Request $request)
    {
        $search = $request->input('query');
        $data = Tags::select("name", 'id')
            ->where("name", "LIKE", "%{$request->term}%")
            ->get();
        return response()->json(['status' => true, 'data' => $data]);
    }

    public function getTicketTags(Request $request, $id)
    {
        if ($id != null) {
            $data = TicketsTags::select('t.name as text', 't.id')->join('tags as t', 'tickets_tags.tag_id', 't.id')->where('tickets_tags.ticket_id', $id)->get()->toArray();
            return response()->json(['status' => true, 'data' => $data]);
        }
    }

    public function getPreviousComment(Request $request, $id)
    {
        $number = $request->currentCommentPage ?? 0;
        $skip = ($number * 3);
        if ($id != null) {
            $ticket = Ticket::find($id);
            $commentsWithDocument = collect(array_merge($ticket->comments->toArray(),$ticket->documents->toArray()));
            $commentsWithDocument = $commentsWithDocument->sortByDesc('created_at')->slice($skip)->take(3)->reverse();
            return response()->json(['status' => true, 'data' => $commentsWithDocument]);
        }
        return response()->json(['status' => false, 'data' => []]);
    }

    public function verifyUser($service_id = null){
        if(!Auth::user()){
            if($service_id != null){
                session(['service_id' => decrypt($service_id)]);
            }
            return view('services.verifyUser');
        }
    }

    public function ticket_request_add($email,$id){
        $data = Ticket::where('id',$id)->first();
        try{
            $maildata = [
                // 'name' => $data->name,
                // 'phone' => $data->phone,
                'app_name' => "Ica Trade Desk",
            ];

            Mail::to($email)->send(new SupportRequestMail($maildata));
        } catch (Throwable $t) {
            Log::error('Mail sending failed: ' . $t->getMessage());
            throw $t;
        }
}
}
