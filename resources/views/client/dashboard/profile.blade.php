@extends('client.dashboard.layouts.app')

@section('content')
    <div class="col-lg-9">
        <div class="dashboard-profile dashboard">
            <div class="box-head">
                <h3>Profile</h3>
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Edit</a>
            </div>
            <ul class="dash-profile">
                <li>
                    <div class="left">
                        <h6 class="font-light">Company Name</h6>
                    </div>
                    <div class="right">
                        <h6>Voxo Fashion</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Country / Region</h6>
                    </div>
                    <div class="right">
                        <h6>Downers Grove, IL</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Year Established</h6>
                    </div>
                    <div class="right">
                        <h6>2018</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Total Employees</h6>
                    </div>
                    <div class="right">
                        <h6>101 - 200 People</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Category</h6>
                    </div>
                    <div class="right">
                        <h6>Clothing</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Street Address</h6>
                    </div>
                    <div class="right">
                        <h6>549 Sulphur Springs Road</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">City/State</h6>
                    </div>
                    <div class="right">
                        <h6>Downers Grove, IL</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Zip</h6>
                    </div>
                    <div class="right">
                        <h6>60515</h6>
                    </div>
                </li>
            </ul>

            <div class="box-head mt-lg-5 mt-3">
                <h3>Login Details</h3>
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#resetEmail">Edit</a>
            </div>

            <ul class="dash-profile">
                <li>
                    <div class="left">
                        <h6 class="font-light">Email Address</h6>
                    </div>
                    <div class="right">
                        <h6>mark.jugal@gmail.com</h6>
                    </div>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#resetEmail">Edit</a>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Phone No.</h6>
                    </div>
                    <div class="right">
                        <h6>+1-202-555-0198</h6>
                    </div>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#resetEmail">Edit</a>
                </li>

                <li class="mb-0">
                    <div class="left">
                        <h6 class="font-light">Password</h6>
                    </div>
                    <div class="right">
                        <h6>●●●●●●</h6>
                    </div>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#resetEmail">Edit</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="modal fade edit-profile-modal" id="editProfile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label font-light">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label font-light">Avatar</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar"
                                name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                            @if (auth()->user()->avatar)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="Avatar"
                                        class="rounded-circle" width="60" height="60">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label font-light">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                            @error('phone')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer pt-0 text-end d-block">
                        <button type="button" class="btn bg-secondary text-white rounded-1"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-solid-default rounded-1">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
