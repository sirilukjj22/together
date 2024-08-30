@extends('layouts.masterLayout')
<style>
    .select2{
        margin: 4px 0;
        border: 1px solid #ffffff;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .phone-group {
        position: relative;
    }

    .phone-group input {
        width: 100%;
        padding-right: 40px;
        /* space for the remove button */
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
                    <small class="text-muted">Welcome to Edit Guest.</small>
                    <div class=""><span class="span1">Edit Guest (แก้ไขลูกค้า)</span></div>
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
                            <div class="row">
                                <div class="col-lg-11 col-md-11 col-sm-12"></div>
                                <div class="col-lg-1 col-md-1 col-sm-12">
                                    <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Guest->Profile_ID}}" disabled>
                                </div>
                            </div>
                            {{-- <form action="{{url('/guest/edit/update/'.$Guest->id)}}" method="POST"> --}}
                                @csrf
                                <div class="row mt-3">
                                    <div class="col-lg-2 col-md-2 col-sm-12">
                                        <label for="Preface" >คำนำหน้า / Title</label><br>
                                        <select name="Preface" id="PrefaceSelect" class="form-select"disabled>
                                            <option value=""></option>
                                            @foreach($prefix as $item)
                                            <option value="{{ $item->id }}"{{$Guest->preface == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-12">
                                        <label for="first_name">ชื่อจริง / First Name</label><br>
                                        <input type="text" class="form-control" placeholder="First Name" id="first_name" name="first_name" maxlength="70"  value="{{$Guest->First_name}}" disabled>
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
                                        <input type="text"class="form-control" placeholder="Last Name" id="last_name" name="last_name" maxlength="70" value="{{$Guest->Last_name}}" disabled>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-12"><label for="country">ประเทศ / Country</label><br>
                                        <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()"disabled>
                                            <option value="Thailand" {{$Guest->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                            <option value="Other_countries" {{$Guest->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                                        </select>
                                    </div>
                                    @if (($Guest->Country === 'Thailand'))
                                    <div class="col-lg-6 col-md-6 col-sm-12" >
                                        <label for="city">จังหวัด / Province</label>
                                        <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                                            @foreach($provinceNames as $item)
                                            <option value="{{ $item->id }}" {{$Guest->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-lg-6 col-md-6 col-sm-12" >
                                        <label for="city">จังหวัด / Province</label>
                                        <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                                            <option value=""></option>
                                            @foreach($provinceNames as $item)
                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                                <div class="row mt-2">
                                    @if ($Guest->Country === 'Thailand')
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Amphures">อำเภอ / District</label>
                                        <select name="amphures" id="amphures" class="select2" onchange="select_amphures()"disabled>
                                            @foreach($amphures as $item)
                                            <option value="{{ $item->id }}" {{ $Guest->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Amphures">อำเภอ / District</label>
                                        <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" disabled>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @endif

                                    @if ($Guest->Country === 'Thailand')
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Tambon">ตำบล / Sub-district </label><br>
                                        <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()"disabled>
                                            @foreach($Tambon as $item)
                                            <option value="{{ $item->id }}" {{ $Guest->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Tambon">ตำบล / Sub-district </label><br>
                                        <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" disabled>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @endif

                                    @if ($Guest->Country === 'Thailand')
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                        <select name="zip_code" id="zip_code" class="select2"disabled>
                                            @foreach($Zip_code as $item)
                                            <option value="{{ $item->zip_code }}" {{ $Guest->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                        <select name="zip_code" id="zip_code" class="select2" disabled>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @endif
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label for="address">ที่อยู่ / Address</label><br>
                                        <textarea type="text" id="address" name="address" rows="5" cols="25" class="textarea form-control" aria-label="With textarea" disabled>{{$Guest->Address}}</textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <span for="Company_Phone" class="flex-container">
                                            โทรศัพท์/ Phone number
                                        </span>
                                        <button type="button" class="btn btn-color-green my-2" id="add-phone" disabled>เพิ่มหมายเลขโทรศัพท์</button>
                                    </div>
                                    <div id="phone-containerN" class="flex-container row"disabled>
                                        @foreach($phoneDataArray as $phone)
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                            <div class="input-group show">
                                                <input type="text" name="phone[]" class="form-control"value="{{ $phone['Phone_number'] }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" disabled>
                                                <button type="button" class="btn btn-outline-danger remove-phone"disabled><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="email">อีเมล / Email</label><br>
                                        <input class="email form-control" type="text" id="email" name="email" maxlength="70" value="{{$Guest->Email}}" disabled>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="booking_channel">ช่องทางการจอง / Booking Channel</label><br>
                                        <select name="booking_channel[]" id="booking_channel" class="select2" multiple disabled>
                                            @php
                                                $booking = explode(',',$Guest->Booking_Channel);
                                            @endphp
                                            @foreach($booking_channel as $item)
                                                <option value="{{ $item->id }}" {{ in_array($item->id, $booking) ? 'selected' : '' }}>
                                                    {{ $item->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="identification_number">หมายเลขประจำตัว / Identification Number</label><br>
                                        <input type="text" class="form-control" id="identification_number" name="identification_number" value="{{$Guest->Identification_Number}}" disabled>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                        <div class="datestyle"><input class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" onchange="Onclickreadonly()"  value="{{$Guest->Contract_Rate_Start_Date}}"disabled></div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                        <div class="datestyle">
                                        <input type="date" class="form-control" id="contract_rate_end_date" name="contract_rate_end_date" readonly value="{{$Guest->Contract_Rate_End_Date}}"disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="discount_contract_rate">Discount Contract Rate (%)</label><br>
                                        <script>
                                            function checkInput() {
                                                var input = document.getElementById("discount_contract_rate");
                                                if (input.value > 100) {
                                                    input.value = 100; // กำหนดค่าใหม่เป็น 100
                                                }
                                            }
                                        </script>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="discount_contract_rate" name="discount_contract_rate" oninput="checkInput()" min="0" max="100" value="{{$Guest->Discount_Contract_Rate}}"disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label for="latest_introduced_by">แนะนำล่าสุดโดย / Latest Introduced By</label><br>
                                        <input type="text" class="form-control" id="latest_introduced_by" name="latest_introduced_by" value="{{$Guest->Lastest_Introduce_By}}" disabled>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-3 col-sm-12"></div>
                                    <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('guest.index') }}'">{{ __('ย้อนกลับ') }}</button>
                                        {{-- <button type="submit" class="btn btn-color-green lift ">บันทึกข้อมูล</button> --}}
                                    </div>
                                    <div class="col-lg-3 col-sm-12"></div>
                                </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('script.script')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        $(document).ready(function() {
            $('#identification_number').mask('0-0000-00000-00-0');
        });
        function openHistory(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("nav-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        function showcityInput() {
            var countrySelect = document.getElementById("countrySelect");
            var province = document.getElementById("province");
            var amphuresSelect = document.getElementById("amphures");
            var tambonSelect = document.getElementById("Tambon");
            var zipCodeSelect = document.getElementById("zip_code");

            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
            if (countrySelect.value === "Other_countries") {
                // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                province.disabled = true;
                amphuresSelect.disabled = true;
                tambonSelect.disabled = true;
                zipCodeSelect.disabled = true;
            } else {
                // ถ้าไม่เลือก "Other_countries" ซ่อน input field สำหรับเมืองอื่นๆ และแสดง input field สำหรับเมืองไทย
                province.disabled = false;
                amphuresSelect.disabled = false;
                tambonSelect.disabled = false;
                zipCodeSelect.disabled = false;
                select_amphures();
            }
        }

        function Onclickreadonly() {
            var startDate = document.getElementById('contract_rate_start_date').value;
            if (startDate !== '') {
                // หากมีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
                document.getElementById('contract_rate_end_date').readOnly = false;
            } else {
                // หากไม่มีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
                document.getElementById('contract_rate_end_date').readOnly = true;
            }
        }

        function select_province() {
            var provinceID = $('#province').val();
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/guest/amphures/" + provinceID + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    jQuery('#amphures').children().remove().end();
                    //ตัวแปร
                    $('#amphures').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var amphures = new Option(value.name_th, value.id);
                        $('#amphures').append(amphures);
                    });
                },
            })
        }

        function select_amphures() {
            var amphuresID = $('#amphures').val();
            $.ajax({
                type: "GET",
                url: "{!! url('/guest/Tambon/" + amphuresID + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    jQuery('#Tambon').children().remove().end();
                    $('#Tambon').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var Tambon = new Option(value.name_th, value.id);
                        $('#Tambon ').append(Tambon);
                    });
                },
            })
        }

        function select_Tambon() {
            var Tambon = $('#Tambon').val();
            $.ajax({
                type: "GET",
                url: "{!! url('/guest/districts/" + Tambon + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    jQuery('#zip_code').children().remove().end();
                    $('#zip_code').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var zip_code = new Option(value.zip_code, value.zip_code);
                        $('#zip_code ').append(zip_code);
                    });
                },
            })
        }
    </script>
    <script>
        // document.getElementById('add-phone').addEventListener('click', function() {
        //     var phoneContainer = document.getElementById('phone-containerN');
        //     var newCol = document.createElement('div');
        //     newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
        //     newCol.innerHTML = `
        //         <div class="input-group mt-2">
        //             <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
        //             <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
        //         </div>
        //     `;
        //     phoneContainer.appendChild(newCol);

        //     // Add the show class after a slight delay to trigger the transition
        //     setTimeout(function() {
        //         newCol.querySelector('.input-group').classList.add('show');
        //     }, 10);

        //     attachRemoveEvent(newCol.querySelector('.remove-phone'));
        // });

        // function attachRemoveEvent(button) {
        //     button.addEventListener('click', function() {
        //         var phoneContainer = document.getElementById('phone-containerN');
        //         if (phoneContainer.childElementCount > 1) {
        //             phoneContainer.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
        //         }
        //     });
        // }

        // // Attach the remove event to the initial remove buttons
        // document.querySelectorAll('.remove-phone').forEach(function(button) {
        //     attachRemoveEvent(button);
        // });
        // Function 1
        document.getElementById('add-phone').addEventListener('click', function() {
            var phoneContainer = document.getElementById('phone-containerN');
            var newCol = document.createElement('div');
            newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
            newCol.innerHTML = `
                <div class="input-group mt-2">
                    <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                    <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                </div>
            `;
            phoneContainer.appendChild(newCol);

            setTimeout(function() {
                newCol.querySelector('.input-group').classList.add('show');
            }, 10);

            attachRemoveEventPhone(newCol.querySelector('.remove-phone'));
        });

        function attachRemoveEventPhone(button) {
            button.addEventListener('click', function() {
                var phoneContainer = document.getElementById('phone-containerN');
                if (phoneContainer.childElementCount > 1) {
                    phoneContainer.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
                }
            });
        }

        // Attach the remove event to the initial remove buttons
        document.querySelectorAll('.remove-phone').forEach(function(button) {
            attachRemoveEventPhone(button);
        });

        // Function 2
        document.getElementById('add-phoneTax').addEventListener('click', function() {
            var phoneContainerTax = document.getElementById('phone-containerTax');
            var newColTax = document.createElement('div');
            newColTax.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
            newColTax.innerHTML = `
                <div class="input-group mt-2">
                    <input type="text" name="phoneTax[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                    <button type="button" class="btn btn-outline-danger remove-phoneTax"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                </div>
            `;
            phoneContainerTax.appendChild(newColTax);

            setTimeout(function() {
                newColTax.querySelector('.input-group').classList.add('show');
            }, 10);

            attachRemoveEventPhoneTax(newColTax.querySelector('.remove-phoneTax'));
        });

        function attachRemoveEventPhoneTax(button) {
            button.addEventListener('click', function() {
                var phoneContainerTax = document.getElementById('phone-containerTax');
                if (phoneContainerTax.childElementCount > 1) {
                    phoneContainerTax.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
                }
            });
        }

        // Attach the remove event to the initial remove buttons
        document.querySelectorAll('.remove-phoneTax').forEach(function(button) {
            attachRemoveEventPhoneTax(button);
        });

    </script>


    {{-- <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableLogGuest.js')}}"></script>
    <script>
        $(document).ready(function() {
            new DataTable('.example', {
                searching: false,
                paging: false,
                info: false,
                columnDefs: [{
                    className: 'dtr-control',
                    orderable: true,
                    target: null,
                }],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        });
        $(document).on('keyup', '.search-data-log', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-guest').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            console.log(search_value);

            if (search_value != '') {
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/logguest-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        guest_profile: guest_profile,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                    columnDefs: [
                                { targets: [0, 1, 2, 3], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Company/Individual' },
                        { data: 'Branch' },
                        { data: 'Status' },
                        { data: 'Order' },
                    ],

                });
            }
            else {
                $('#'+id+'-paginate').children().remove().end();
                $('#'+id+'-showingEntries').text(showingEntriesSearch(total, id));
                $('#'+id+'-paginate').append(paginateSearch(total, id, getUrl));
            }

            document.getElementById(id).focus();
        });
    </script>

<script>
    $(document).on('keyup', '.search-data-guest-Tax', function () {
        var id = $(this).attr('id');
        var search_value = $(this).val();
        var table_name = id+'Table';
        var guest_profile = $('#profile-guest').val();
        var type_status = $('#status').val();
        var total = parseInt($('#get-total-'+id).val());
        console.log(search_value);

        if (search_value != '') {
            $('#'+table_name).DataTable().destroy();
            var table = $('#'+table_name).dataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                url: '/tax-guest-search-table',
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    search_value: search_value,
                    table_name: table_name,
                    guest_profile: guest_profile,
                    status: type_status,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            },
                columnDefs: [
                            { targets: [0, 2, 3,4], className: 'dt-center td-content-center' },
                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columns: [
                    { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    { data: 'Company/Individual' },
                    { data: 'Branch' },
                    { data: 'Status' },
                    { data: 'Order' },
                ],

            });
        }
        else {
            $('#'+id+'-paginate').children().remove().end();
            $('#'+id+'-showingEntries').text(showingEntriesSearchTax(total, id));
            $('#'+id+'-paginate').append(paginateSearchTax(total, id, getUrl));
        }

        document.getElementById(id).focus();
    });
    function btnstatus(id) {
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/guest/change-status/tax/" + id + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                location.reload();
            },
        });
    }
</script> --}}
@endsection