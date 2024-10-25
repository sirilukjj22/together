@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Guest.</small>
                    <div class=""><span class="span1">Guest (ลูกค้า)</span></div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/guest-create') }}'">
                        <i class="fa fa-plus"></i> เพิ่มลูกค้า
                    </button>
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
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
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
                <div class="col-auto">
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center mb-2" >
                                <div class="col">
                                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ol>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-color-green text-white dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if ($menu == 'guest.all')
                                                    All
                                                @elseif ($menu == 'guest.ac')
                                                    Active
                                                @elseif ($menu == 'guest.no')
                                                    Disabled
                                                @else
                                                    Status
                                                @endif

                                        </button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('guest', 'guest.all') }}">All</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('guest', 'guest.ac') }}">Active</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('guest', 'guest.no') }}">Disabled</a></li>
                                        </ul>
                                    </div>
                            </div>
                            <div style="min-height: 70vh;" class="mt-2">
                                <table class="table-together striped table nowrap unstackable hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">เรียงลำดับ</th>
                                            <th style="text-align: center;"data-priority="1">รหัสลูกค้า</th>
                                            <th data-priority="1">ชื่อและนามสกุลผู้ใช้งาน</th>
                                            <th>Telephone</th>
                                            <th class="text-center">สถานะการใช้งาน</th>
                                            <th class="text-center">คำสั่ง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Guest))
                                            @foreach ($Guest as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">{{ $key + 1 }}</td>
                                                <td style="text-align: center;">{{ $item->Profile_ID }}</td>
                                                <td>{{ $item->First_name }} {{ $item->Last_name }}</td>
                                                <td>
                                                    {{$item->Phone_numbers}}
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="hidden" id="status" value="{{ $item->status }}">

                                                    @if ($item->status == 1)
                                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                    @else
                                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                    @endif
                                                </td>
                                                @php
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Guest', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Guest', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/guest/view/'.$item->id) }}">ดูรายละเอียด</a></li>
                                                                @endif
                                                                @if ($canEditProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/guest/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/guest/view/'.$item->id) }}">ดูรายละเอียด</a></li>
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
        function fetchStatus(status) {
            if (status == 'all' ) {
                $('#StatusName').text('All');
            }else if (status == 'Active') {
                $('#StatusName').text('Active');
            }
            else if (status == 'Disabled') {
                $('#StatusName').text('Disabled');
            }
            else if (status == ' ') {
                $('#StatusName').text('สถานะการใช้งาน');
            }
        }
        function btnstatus(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/guest/change-status/" + id + "') !!}",
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
