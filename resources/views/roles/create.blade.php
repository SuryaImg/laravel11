@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Role Page'])
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-9 col-12 mx-auto">
                <div class="card">
                    <form role="form" method="POST" action="{{ route('roles.store') }}" enctype="multipart/form-data" id="roleCreate">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Create Role</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Name</label>
                                        <input class="form-control" type="text" name="name" value="{{ old('name') }}">
                                        @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <!-- <label for="example-text-input" class="form-control-label">Permission</label><br> -->
                                        @php
                                        $currentHeading = null;
                                        @endphp

                                        @foreach ($permission as $key => $value)
                                            @php
                                                $heading = explode('-', $value->name)[0];
                                                $class = "class='roleHead text-capitalize'";
                                                if ($heading !== $currentHeading) {
                                                    echo "<br/><strong {$class}>* {$heading} Permission</strong><br/>";
                                                    $currentHeading = $heading;
                                                }
                                            @endphp
                                            <input type="checkbox" name="permission[]" value="{{ $value->id ?? '' }}">&nbsp; {{ $value->name }}<br/>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
