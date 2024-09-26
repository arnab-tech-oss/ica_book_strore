@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}</h6>
            <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.user.fields.name') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">{{ trans('cruds.user.fields.email') }} <strong class="text-danger">*</strong></label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email', isset($user) ? $user->email : '') }}" required readonly>
                    @if($errors->has('email'))
                        <em class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.email_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control">
                    @if($errors->has('password'))
                        <em class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.password_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                    <label for="roles">{{ trans('cruds.user.fields.roles') }} <strong class="text-danger">*</strong></label>
                <!--                    <span class="btn btn-info btn-xs select-all-user" id="select-all-user">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all-user" id="deselect-all-user">{{ trans('global.deselect_all') }}</span>-->
                    <!--    multiple="multiple"     -->
                    <select name="roles[]" id="roles" class="form-control" required>
                        @foreach($roles as $id => $roles)
                            <option
                                value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <em class="invalid-feedback">
                            {{ $errors->first('roles') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.roles_helper') }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country_id" id="country" class="form-control select2 country_input" >
                        <option value="" selected>Select Country</option>
                        @foreach($countries as $id => $country)
                            <option value="{{ $country->id }}"
                                    @if($user->country_id == $country->id) selected @endif >{{ $country->name }}</option>
                        @endforeach
                    </select>
                    @error('country')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="currency">Currency</label>
                    <input id="currency" type="text" class="form-control @error('currency') is-invalid @enderror"
                           name="currency" readonly autocomplete="currency" value="{{ $user->currency }}">

                    @error('currency')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="mb-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.user.fields.status') }} <strong class="text-danger">*</strong></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="" selected>Select Status</option>
                        <option
                            value="1" {{$user->status == '1' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option
                            value="0" {{ $user->status == '0' ? 'selected' : '' }}>
                            In Active
                        </option>
                    </select>
                    @if($errors->has('status'))
                        <em class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.status_helper') }}
                    </p>
                </div>

                <div>
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.update') }}">
                </div>
            </form>


        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function hideCurrencyAndCountry($this = null) {
            let option = null;
            if ($this != null) {
                option = $($this).find("option:selected").text();
            } else {
                option = $("#roles").find("option:selected").text();
            }
            if (option != null) {
                option = option.toLowerCase();
                if (option == 'customer') {
                    $("#country_div").show();
                    $("#currency_div").show();
                    $("#country").prop('required',true);
                } else {
                    $("#country_div").hide();
                    $("#currency_div").hide();
                    $("#country").removeAttr('required');
                }
            }
        }

        hideCurrencyAndCountry();
        $(".country_input").on('change', function () {
            if ($(".country_input option:selected").text().toLowerCase() == 'india') {
                $("#currency").val("INR");
            } else {
                $("#currency").val("USD");
            }
        });

        $("#roles").on('change', function () {
            let $this = this;
            hideCurrencyAndCountry($this);
        });

        $("#select-all-user").on('click', function () {
            $("#roles option").prop('selected', true);
        });

        $("#deselect-all-user").on('click', function () {
            $("#roles option").prop('selected', false);
        });
    </script>
@endsection
