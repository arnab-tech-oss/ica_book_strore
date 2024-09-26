@extends('layouts.admin')
@section('content')
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Settings</h6>
            <form action="{{ route('admin.setting.store') }}" name="setting" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <h3 class="mb-2 text-muted">Software Info :</h3>
                <div class="px-3">
                    <div class="form-group">
                        <label for="software_title">Title :</label>
                        <input type="hidden" value="{{ $setting->id ?? '' }}" name="id" id="id">
                        <input type="text" class="form-control" value="{{ $setting->software_title ?? '' }}"
                               name="software_title"
                               id="software_title">
                    </div>
                    <div class="form-group">
                        <label for="software_description">Description :</label>
                        <textarea class="form-control" name="software_description"
                                  id="software_description"
                                  style="min-height: 150px;">{{ $setting->software_description ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="software_version">Version :</label>
                        <input type="number" class="form-control" value="{{ !empty($setting->software_version) ? $setting->software_version : config('app.version') }}"
                               name="software_version" id="software_version" readonly>
                    </div>

                    <div class="form-group">
                        <label for="api_key">Api Key :</label>
                        <input type="text" class="form-control" value="{{ $setting->api_key ?? '' }}"
                               name="api_key" id="api_key" readonly>
                        <button type="button" id="generate_api_key" name="generate_api_key"
                                class="my-2 btn btn-primary">Generate Key
                        </button>
                    </div>
                </div>
                <hr/>
                <h3 class="mb-2 text-muted">Company Info :</h3>
                <div class="px-3">
                    <div class="form-group">
                        <label for="company_name">Name :</label>
                        <input type="text" class="form-control" value="{{ $setting->company_name ?? '' }}"
                               name="company_name"
                               id="company_name">
                    </div>
                    <div class="form-group">
                        <label for="company_logo">Logo :</label>
                        <input type="file" class="form-control" value="{{ $setting->company_logo ?? '' }}"
                               name="company_logo"
                               id="company_logo">
                        @if(!empty($setting->company_logo))
                            <img src="{{  url($setting->company_logo) }}" class="img-thumbnail my-2"
                                 height="250"
                                 width="250">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="company_intro">Introduction :</label>
                        <textarea class="form-control" style="min-height: 150px;" name="company_intro" id="company_intro">{{ $setting->company_intro ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="company_email">Email :</label>
                        <input type="email" class="form-control" value="{{ $setting->company_email ?? '' }}"
                               name="company_email"
                               id="company_email">
                    </div>
                    <div class="form-group">
                        <label for="company_alternative_email">Alternative Email :</label>
                        <input type="email" class="form-control" value="{{ $setting->company_alternative_email ?? '' }}"
                               name="company_alternative_email" id="company_alternative_email">
                    </div>
                    <div class="form-group">
                        <label for="company_contact_no">Contact No :</label>
                        <input type="number" class="form-control" value="{{ $setting->company_contact_no ?? '' }}"
                               name="company_contact_no" id="company_contact_no">
                    </div>
                    <div class="form-group">
                        <label for="company_alternative_contact_no">Alternative Contact No :</label>
                        <input type="number" class="form-control"
                               value="{{ $setting->company_alternative_contact_no ?? '' }}"
                               name="company_alternative_contact_no" id="company_alternative_contact_no">
                    </div>

                    <div class="form-group">
                        <label for="company_gst_no">GST No :</label>
                        <input type="text" class="form-control" value="{{ $setting->company_gst_no ?? '' }}"
                               name="company_gst_no" id="company_gst_no">
                    </div>
                </div>
                <h3 class="mb-2 text-muted">Billing Info :</h3>
                <div class="px-3">
                    <div class="form-group">
                        <label for="billing_header">Header :</label>
                        <textarea class="form-control" name="billing_header"
                                  id="billing_header"
                                  style="min-height: 150px;">{{ $setting->billing_header ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="billing_footer">Footer :</label>
                        <textarea class="form-control" name="billing_footer"
                                  id="billing_footer"
                                  style="min-height: 150px;">{{ $setting->billing_footer ?? '' }}</textarea>
                    </div>
                </div>
                <h3 class="mb-2 text-muted">Notification Emails :</h3>
                <div class="px-3">
                    <div class="form-group">
                        <label for="email_cc">CC :</label>
                        <input type="text" class="form-control" value="{{ $setting->email_cc ?? '' }}" name="email_cc"
                               id="email_cc">
                    </div>
                    <div class="form-group">
                        <label for="email_bcc">BCC :</label>
                        <input type="text" class="form-control" value="{{ $setting->email_bcc ?? '' }}"
                               name="email_bcc"
                               id="email_bcc">
                    </div>
                </div>
                <h3 class="mb-2 text-muted">Accounts Info :</h3>
                <div class="px-3">
                    <div class="form-group">
                        <label for="account_email">Account Email :</label>
                        <input type="email" class="form-control" value="{{ $setting->account_email ?? '' }}" name="account_email"
                               id="account_email">
                    </div>
                </div>
                <input type="submit" name="btnSetting" class="btn btn-primary" value="Submit">
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#generate_api_key').on('click', function () {
            const id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            $('#api_key').val(id);
        });
    </script>
@endsection

