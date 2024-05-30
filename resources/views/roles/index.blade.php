@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Role Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="alert alert-light" role="alert">
                 <strong>You can make the changes here as per your necessity </strong>. Check it
            </div>
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between"><div>
                        <h5 class="mb-0">All Roles</h5>
                    </div>
                    @can('role-create')
                    <a href="{{ route('roles.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; New Role</a>
                    @endcan
                </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="roleDataTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ asset('assets/img/team-1.jpg') }}" class="avatar me-3" alt="image">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{$role->name}}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="text-center">
                                            @can('role-edit')
                                                <a href="{{ route('roles.edit', $role->id) }}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                                                    <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
                                                </a>
                                            @endcan
                                            @can('role-delete')
                                                <i class="cursor-pointer fas fa-trash text-secondary" aria-hidden="true" onclick="argon.showSwal('warning-message-and-cancel','delete_form_{{$role->id}}')"></i>
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" id="delete_form_{{$role->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
<script>
    const roleDataTable = new simpleDatatables.DataTable("#roleDataTable", {
        searchable: true,
        fixedHeight: true
    });
</script>
@endpush