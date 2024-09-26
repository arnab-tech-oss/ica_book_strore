@extends('layouts.admin')
@section('content')
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}</h6>
            <form action="{{ route('admin.roles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">{{ trans('cruds.role.fields.title') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="text" id="title" name="title" class="form-control"
                        value="{{ old('title', isset($role) ? $role->title : '') }}" required>
                    @if ($errors->has('title'))
                        <em class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.role.fields.title_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('permissions') ? 'has-error' : '' }}">
                    <label for="permissions">{{ trans('cruds.role.fields.permissions') }} <strong
                            class="text-danger">*</strong></label>
                    <span class="btn btn-info btn-xs select-all-role"
                        id="select-all-role">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all-role"
                        id="deselect-all-role">{{ trans('global.deselect_all') }}</span>
                    <select name="permissions[]" id="permissions" class="form-control" size="13" style="height: 100%;" onclick="multiselect(this)"
                        multiple required>
                        @foreach ($permissions as $id => $permissions)
                            <option value="{{ $id }}"
                                {{ in_array($id, old('permissions', [])) || (isset($role) && $role->permissions->contains($id)) ? 'selected' : '' }}>
                                {{ $permissions }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('permissions'))
                        <em class="invalid-feedback">
                            {{ $errors->first('permissions') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.role.fields.permissions_helper') }}
                    </p>
                </div>
                <input class="btn btn-primary" type="submit" value="{{ trans('global.submit') }}">
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#select-all-role").on('click', function() {
            $("#permissions option").prop('selected', true);
        });

        $("#deselect-all-role").on('click', function() {
            $("#permissions option").prop('selected', false);
        });
        var selected_array = [];

        function multiselect(selectBox) {
            selectBox.style.height = "auto"
            var selectedValue = selectBox.selectedIndex;
            if (selected_array.includes(selectBox.selectedIndex)) {
                selected_array.pop()
                document.getElementById('permissions').options[selectBox.selectedIndex].selected = ""

            } else {
                selected_array.push(selectedValue);
            }
            console.log(selected_array);
            for (i = 0; i < selected_array.length; i++) {
                document.getElementById('permissions').options[selected_array[i]].selected = "true";
                document.getElementById('permissions').options[selected_array[i]].classList.add('fas')
                document.getElementById('permissions').options[selected_array[i]].classList.add('fa-check')

            }
        }
    </script>
@endsection
