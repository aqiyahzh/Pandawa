@extends('layouts.admin')

@section('content')
<div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Logout</h3>
                                </div>

                                <div class="card-body text-center">
                                    <p class="mb-4">
                                        Kamu yakin ingin keluar dari dashboard?
                                    </p>

                                    <a href="{{ route('/admin/login') }}" class="btn btn-danger w-100">
                                        Yes, Logout
                                    </a>
                                </div>

                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        <a href="{{ route('/admin/dashboard') }}">Cancel, go back to Dashboard</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</div>
@endsection