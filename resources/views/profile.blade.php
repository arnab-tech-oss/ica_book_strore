@extends('welcome')
@section('content')
    <!-- profile Start -->
    <div class="container my-5 d-flex justify-content-center items-center" id="profile">
        <div class="container w-75">
            <h3 class="highlight">Update Profile</h3>
            <div class="wow fadeInUp" data-wow-delay="0.5s">
                <form action="{{ route('profileStore') }}" method="post">
                    @csrf
                    <div class="form-floating form-group">
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Your Name" value="{{ $user->name }}" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating form-group">
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="Your Email" value="{{ $user->email }}" readonly>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating form-group">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Password" value="{{ $user->password }}" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating form-group">
                        <input type="text" class="form-control" id="country" name="country"
                               placeholder="Country" value="{{ isset($user->country) ? $user->country->name : '' }}"readonly>
                        <label for="country">Country</label>
                    </div>
                    <div class="form-floating form-group">
                        <input type="text" class="form-control" id="currency" name="currency"
                               placeholder="Currency" value="{{ $user->currency }}" readonly>
                        <label for="currency">Currency</label>
                    </div>
                    <div class="form-floating form-group">
                        <textarea class="form-control" id="address" name="address"
                               placeholder="Address" >{{ $user->billing_address }}</textarea>
                        <label for="address">Address</label>
                    </div>
                    <div class="col-12">
                        <button class="theme-btn mt-4" type="submit">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- profile End -->

@endsection
