@if(session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
@if(\Session::has('message'))
    <div class="d-flex justify-content-center">
        <div @if($type == "admin") id="snackbar-admin" @else id="snackbar" @endif
             class="show {{ \Session::get('alert-class', 'alert-info') }}">{{ \Session::get('message') }}</div>
    </div>
@endif
