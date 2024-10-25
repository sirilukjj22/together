@extends('layouts.masterLayout')
<style>
    .select2{
        margin: 4px 0;
        border: 1px solid #ffffff;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .custom-accordion {
        border: 1px solid #ccc;
        margin-bottom: 20px;
        border-radius: 5px;
        overflow: hidden;
    }

    .custom-accordion input[type="checkbox"] {
        display: none;
    }

    .custom-accordion label {
        font-size: 18px;
        background-color: #f0f0f0;
        display: block;
        cursor: pointer;
        padding: 15px 20px;
    }
    .custom-accordion label::before {
        content: "\2610";
        margin-right: 10px;
        font-size: 24px;
    }

    .custom-accordion input[type="checkbox"]:checked+label::before {
        content: "\2611";
    }

    .custom-accordion-content {
        font-size: 16px;
        padding: 5% 10%;
        display: none;
        border-top: 1px solid #ffffff;
    }

    .custom-accordion input[type="checkbox"]:checked+label+.custom-accordion-content {
        display: block;
    }
    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to View List Contract Name.</small>
                    <div class=""><span class="span1"> View List Contract Name</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                        <hr>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">ERROR 500!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table class="table-together striped table nowrap unstackable hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th style="text-align: center;"data-priority="1">Contact ID</th>
                                            <th class="text-center">First Name</th>
                                            <th class="text-center">Last Name</th>
                                            <th class="text-center">Telephone Number</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Company))
                                            @foreach ($Company as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">{{ $key + 1 }}</td>
                                                <td style="text-align: center;">{{ $item->Profile_ID }}</td>
                                                <td style="text-align: center;">{{ $item->First_name }}</td>
                                                <td style="text-align: center;">{{ $item->Last_name }}</td>
                                                <td style="text-align: center;">
                                                    {{ $item->Phone_numbers }}
                                                </td>
                                                <td style="text-align: center;">

                                                    @if ($item->status == 1)
                                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                    @else
                                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                    @endif
                                                </td>
                                                @php
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/contact/'.$item->id) }}">View</a></li>
                                                                @endif
                                                                {{-- @if ($canEditProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/contact/'.$item->id) }}">Edit</a></li>
                                                                @endif --}}
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/contact/'.$item->id) }}">View</a></li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3 col-sm-12"></div>
                                <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{url('/Company/edit/'.$CompanyID)}}'">{{ __('ย้อนกลับ') }}</button>
                                </div>
                                <div class="col-lg-3 col-sm-12"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/helper/searchTable.js')}}"></script>
    @include('script.script')
    <script>
        function btnstatus(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/company/change-status/Contact/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
    </script>
@endsection
