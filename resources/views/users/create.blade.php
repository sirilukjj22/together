@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">User</span><span class="span2"> / Create User</span></div>
                    <div class="span3">Create User</div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('users', 'index') }}" type="button" class="btn btn-color-green text-white lift">Back</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    @php
        $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
    @endphp
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="min-height: 70vh;">
                            <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="username" class="col-sm-3 col-form-label fw-bold">ชื่อผู้ใช้งาน / Username <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" placeholder="กรุณาระบุชื่อผู้ใช้งาน" maxlength="70" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label fw-bold">อีเมล์ / Email <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="email" placeholder="email@website.com" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">รหัสผ่าน / Password <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="permission-select2" class="col-sm-3 col-form-label fw-bold">สิทธิ์ในการเข้าถึง / Access rights <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="permission" id="permission-select2">
                                            <option value="0">ผู้ใช้งานทั่วไป</option>
                                            <option value="1">แอดมิน</option>
                                        </select>
                                    </div>
                                    <label for="permission-edit-select2" class="col-sm-2 col-form-label text-right fw-bold">สิทธิ์ในการใช้ข้อมูล<sup class="text-danger">*</sup></label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="permission_edit" id="permission-edit-select2">
                                            <option value="0">ดูได้อย่างเดียว</option>
                                            <option value="1" selected>สามารถแก้ไขข้อมูลตัวเอง</option>
                                            <option value="2">สามารถแก้ไขข้อมูลตัวเอง และดูข้อมูลคนอื่นได้</option>
                                            <option value="3">สามารถแก้ไขข้อมูลตัวเอง และแก้ไขข้อมูลคนอื่นได้</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">ส่วนลด / Discount</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" min="0" max="100" class="form-control" name="discount" value="0">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <label for="close_day" class="col-sm-3 col-form-label fw-bold text-right">Close Day</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="close_day" id="close_day" value="1">
                                            <label class="form-check-label" for="close_day">Close Day</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">สิทธิ์การใช้งานเมนู / Menu Permissions</label>
                                    <div class="col-sm-9">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="select_menu_all" id="select_menu_all" value="0">
                                            <label class="form-check-label" for="select_menu_all">เลือกทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-12 col-md-12 mb-3">
                                        <div class="accordion card p-0 p-lg-4" id="accordionExample">
                                            <div class="card border-0">
                                                <div class="card-body" id="heading1">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                                        <b>เมนู / Menu</b><span style="float: right; color:rgb(26, 107, 87);">ย่อ/ขยาย</span>
                                                    </h6>
                                                </div>
                                                <div id="faq1" class="collapse" aria-labelledby="heading1" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="col-12 table_wrapper print_invoice">
                                                            <table class="items">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <th>ชื่อเมนู</th>
                                                                        <th>เพิ่มข้อมูล</th>
                                                                        <th>แก้ไขข้อมูล</th>
                                                                        <th>ลบข้อมูล</th>
                                                                        <th>ดูข้อมูล</th>
                                                                        <th>Discount</th>
                                                                        <th>Special Discount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($tb_menu))
                                                                        @foreach ($tb_menu as $item)
                                                                            @if ($item->category_name == 1)
                                                                                <tr>
                                                                                    <td>
                                                                                        <div>
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}" id="menu_{{ $item->name2 }}" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}"><b>{{ $item->name_en }}</b></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    @if ($item->name_en == "Product Item")
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_add" id="menu_{{ $item->name2 }}_add" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_add"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_edit" id="menu_{{ $item->name2 }}_edit" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_edit"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_delete" id="menu_{{ $item->name2 }}_delete" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_delete"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_view" id="menu_{{ $item->name2 }}_view" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_view"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_discount" id="menu_{{ $item->name2 }}_discount" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_special_discount" id="menu_{{ $item->name2 }}_special_discount" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_special_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                    @endif
                                                                                </tr>
                                                                            @endif
                                                                            @foreach ($tb_menu as $item2)
                                                                                @if ($item2->category_name == 2 && $item2->menu_main == $item->id)
                                                                                    <tr>
                                                                                        <td>
                                                                                           <div>
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}" id="menu_{{ $item2->name2 }}" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}">{{ $item2->name_en }}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_add" id="menu_{{ $item2->name2 }}_add" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_add"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_edit" id="menu_{{ $item2->name2 }}_edit" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_edit"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_delete" id="menu_{{ $item2->name2 }}_delete" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_delete"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_view" id="menu_{{ $item2->name2 }}_view" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_view"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_discount" id="menu_{{ $item2->name2 }}_discount" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_special_discount" id="menu_{{ $item2->name2 }}_special_discount" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_special_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom"></div>
                                            </div> <!-- .card - FAQ 1  -->
                                        </div>
                                    </div>
                                </div> <!-- Row end  -->
                                <div class="row mb-3 mt-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">สิทธิ์ใช้งานประเภทรายได้ / <br> Revenue type permissions</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="select_revenue_all" id="select_revenue_all" value="0">
                                            <label class="form-check-label" for="select_revenue_all">เลือกทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    {{-- <div class="col-lg-3 col-md-3"></div> --}}
                                    <div class="col-lg-12 col-md-12 mb-3">
                                        <div class="accordion card p-0 p-lg-4" id="accordionExample2">
                                            <div class="card border-0">
                                                <div class="card-body" id="heading2">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="true" aria-controls="faq2"><b>ประเภทรายได้ / Revenue Type</b> <span style="float: right; color:rgb(26, 107, 87);">ย่อ/ขยาย</span></h6>
                                                </div>
                                                <div id="faq2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample2">
                                                    <div class="card-body">
                                                        <div class="col-12 table_wrapper print_invoice">
                                                            <table class="items">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <th></th>
                                                                        <th>ชื่อเมนู</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($tb_revenue_type))
                                                                        @foreach ($tb_revenue_type as $key => $item)
                                                                        @php
                                                                            $name2 = $tb_revenue_type2[$key];
                                                                        @endphp
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="text-center">
                                                                                        <input class="form-check-input select_revenue" type="checkbox" name="{{ $name2 }}" id="{{ $name2 }}" value="1">
                                                                                        <label class="form-check-label" for="{{ $name2 }}"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    {{ $item }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom"></div>
                                            </div> <!-- .card - FAQ 2  -->
                                        </div>
                                    </div>
                                </div> <!-- Row end  -->
                                <div class="text-end col-12">
                                    <a href="{{ route('users', 'index') }}" type="button" class="btn btn-outline-dark lift">Cancle</a>
                                    <button type="submit" class="btn btn-color-green lift">Save</button>
                                </div>
                            </form>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <script>
        $(document).ready(function() {
            $('#permission-select2').select2();
            $('#permission-edit-select2').select2();
        });

        $('#select_menu_all').on('click', function() {
            var menu = $('#select_menu_all').val();

            if (menu == 0) {
                $('.select_menu').prop('checked', true);
                $('#select_menu_all').val(1);
            } else { 
                $('.select_menu').prop('checked', false);
                $('#select_menu_all').val(0);
            }
        });

        $('#select_revenue_all').on('click', function() {
            var revenue = $('#select_revenue_all').val();

            if (revenue == 0) {
                $('.select_revenue').prop('checked', true);
                $('#select_revenue_all').val(1);
            } else {
                $('.select_revenue').prop('checked', false);
                $('#select_revenue_all').val(0);
            }
        });

        $('.select_menu').on('click', function() {
            var select_menu = $(this).attr('id');

            $('#select_menu_all').val(0);
            $('#select_menu_all').prop('checked', false);

            if ($(this).is(':checked')) {
                $('.select_'+select_menu).prop('checked', true);
            } else {
                $('.select_'+select_menu).prop('checked', false);
            }
        });

        $('.select_revenue').on('click', function() {
            $('#select_revenue_all').val(0);
            $('#select_revenue_all').prop('checked', false);
        });
    </script>
@endsection
