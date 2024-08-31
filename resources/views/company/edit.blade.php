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
                    <small class="text-muted">Welcome to Create Company / Agent.</small>
                    <div class=""><span class="span1">Create Company / Agent (เพิ่มบริษัทและตัวแทน)</span></div>
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
                            <div class="row">
                                <div class="col-lg-11 col-md-11 col-sm-12"></div>
                                <div class="col-lg-1 col-md-1 col-sm-12">
                                    <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Profile_ID}}" disabled>
                                </div>
                            </div>
                            <form id="myForm" action="{{url('/Company/Company_edit/Company_update/'.$Company->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Company_type">ประเภทบริษัท / Company Type</label>
                                        <select name="Company_type" id="Company_type" class="form-select">
                                            <option value=""></option>
                                            @foreach($MCompany_type as $item)
                                                <option value="{{ $item->id }}" {{$Company->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-8 col-md-6 col-sm-12">
                                        <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                                        <input type="text" id="Company_Name" class="form-control" name="Company_Name" maxlength="70" required value="{{$Company->Company_Name}}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <label for="Branch">สาขา / Company Branch <span>&nbsp;&nbsp;&nbsp; </span><input class="form-check-input" type="radio" name="flexRadioDefaultBranch" id="flexRadioDefaultBranch"> สำนักงานใหญ่</label>
                                        <input type="text" id="Branch" name="Branch" class="form-control" maxlength="70" required value="{{$Company->Branch}}">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <label for="Market">กลุ่มตลาด / Market</label>
                                        <select name="Market" id="Mmarket" class="form-select">
                                            <option value=""></option>
                                            @foreach($Mmarket as $item)
                                                <option value="{{ $item->id }}" {{$Company->Market == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <label for="booking_channel">ช่องทางการจอง / Booking Channel</label>
                                        <select name="Booking_Channel" id="booking_channel" class="select2">
                                            <option value=""></option>
                                            @foreach($booking_channel as $item)
                                                <option value="{{ $item->id }}" {{$Company->Booking_Channel == $item->id ? 'selected' : ''}}>{{ $item->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <label for="country">ประเทศ / Country</label>
                                        <select name="Country" id="countrySelect" class="form-select" onchange="showcityInput()">
                                            <option value="Thailand" {{$Company->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                            <option value="Other_countries" {{$Company->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label for="address">ที่อยู่ / Address</label>
                                        <textarea type="text" id="address" name="Address" rows="5" cols="25" class="form-control" aria-label="With textarea" required>{{$Company->Address}}</textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label for="city">จังหวัด / Province</label>
                                        <select name="City" id="province" class="select2" onchange="select_province()">
                                            @foreach($provinceNames as $item)
                                            <option value="{{ $item->id }}" {{$Company->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label for="Amphures">อำเภอ / District</label>
                                        <select name="Amphures" id="amphures" class="select2" onchange="select_amphures()">
                                            @foreach($amphures as $item)
                                            <option value="{{ $item->id }}" {{ $Company->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label for="Tambon">ตำบล / Subdistrict </label>
                                        <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
                                            @foreach($Tambon as $item)
                                            <option value="{{ $item->id }}" {{ $Company->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                        <select name="Zip_Code" id="zip_code" class="select2">
                                            @foreach($Zip_code as $item)
                                            <option value="{{ $item->zip_code }}" {{ $Company->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                            <label for="Company_Phone">
                                                Company Phone number
                                            </label>
                                            <button  type="button" class="btn btn-color-green my-2 add-input" id="add-input">เพิ่มหมายเลขโทรศัพท์</button>
                                        <div id="phone-inputs-container" class="flex-container">
                                            @foreach($phoneDataArray as $index => $phone)
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="phone-main" name="phone[]" value="{{ $phone['Phone_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Phone_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"required>
                                                    <button class="btn btn-outline-danger remove-input" type="button" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <label for="Company_Phone" class="flex-container">
                                            แฟกซ์ของบริษัท / Company Fax number
                                        </label>
                                        <button type="button" class="btn btn-color-green my-2" id="add-fax">เพิ่มหมายเลขแฟกซ์</button>
                                        <div id="fax-inputs-container" class="flex-container">
                                            @foreach($faxArray as $index => $phone)
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control"  name="fax[]" maxlength="10"  value="{{ $phone['Fax_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Fax_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                    <button class="btn btn-outline-danger remove-input" type="button" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Company_Email">ที่อยู่อีเมลของบริษัท / Company Email</label>
                                        <input type="email" class="email form-control" id="Company_Email"  name="Company_Email" maxlength="70" required value="{{$Company->Company_Email}}">
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Company_Website">เว็บไซต์ของบริษัท / Company Website</label><br>
                                        <input type="text" id="Company_Website" class="form-control" name="Company_Website" maxlength="70" required value="{{$Company->Company_Website}}">
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                                        <input type="text" id="Taxpayer_Identification" class="form-control" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" required value="{{$Company->Taxpayer_Identification}}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                                        <input type="text" id="Discount_Contract_Rate" class="form-control" name="Discount_Contract_Rate" maxlength="70" disabled value="{{$Company->Discount_Contract_Rate}}">
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                        <input class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" value="{{$Company->Contract_Rate_Start_Date}}" disabled>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                        <input class="form-control" type="date" id="contract_rate_end_date" name="contract_rate_end_date" value="{{$Company->Contract_Rate_End_Date}}"disabled>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                                        <input type="text" id="Lastest_Introduce_By" class="form-control" name="Lastest_Introduce_By" maxlength="70" required value="{{$Company->Lastest_Introduce_By}}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-3 col-sm-12"></div>
                                    <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('Company.index') }}'">{{ __('ย้อนกลับ') }}</button>
                                        <button type="submit" class="btn btn-color-green lift " onclick="confirmSubmit(event)">บันทึกข้อมูล</button>
                                    </div>
                                    <div class="col-lg-3 col-sm-12"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                    <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Tax" role="tab">Additional Company Tax Invoice</a></li>{{--ประวัติการแก้ไข--}}
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Visit" role="tab">Lastest Visit info</a></li>{{--QUOTAION--}}
                    <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Billing" role="tab">Billing Folio info </a></li>{{--เอกสารออกบิล--}}
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Contract" role="tab">Contract Rate Document</a></li>{{--Doc. number--}}
                    <li class="nav-item" id="nav5"><a class="nav-link " data-bs-toggle="tab" href="#nav-Freelancer" role="tab">Latest Freelancer By</a></li>{{--ชื่อ คนแนะนำ ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Commission" role="tab">Lastest Freelancer Commission</a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav7"><a class="nav-link" data-bs-toggle="tab" href="#nav-User" role="tab">User logs</a></li>{{--ประวัติการแก้ไข--}}
                </ul>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade  show active" id="nav-Tax" role="tabpanel" rel="0">
                                <div class="row my-3">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2  d-flex justify-content-end align-items-end">
                                        <button type="button" class="btn btn-color-green lift btn_modal"  data-bs-toggle="modal" data-bs-target="#CreateCompany"><i class="fa fa-plus"></i> Add tax invoice</button>
                                    </div>
                                </div>
                                <div style="min-height: 70vh;" class="mt-2">
                                    <table id="company-TaxTable" class="example ui striped table nowrap unstackable hover">
                                        <caption class="caption-top">
                                            <div>
                                                <div class="flex-end-g2">
                                                    <label class="entriespage-label">entries per page :</label>
                                                    <select class="entriespage-button" id="search-per-page-company-Tax" onchange="getPageTax(1, this.value, 'company-Tax')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "company-Tax" ? 'selected' : '' }}>10</option>
                                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "company-Tax" ? 'selected' : '' }}>25</option>
                                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "company-Tax" ? 'selected' : '' }}>50</option>
                                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "company-Tax" ? 'selected' : '' }}>100</option>
                                                    </select>
                                                    <input class="search-button search-data-company-Tax" id="company-Tax" style="text-align:left;" placeholder="Search" />
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th class="text-center" data-priority="1">No</th>
                                                <th class="text-center" data-priority="1">Profile_ID</th>
                                                <th class="text-center" data-priority="1">Company/Individual</th>
                                                <th class="text-center">Branch</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($company_tax))
                                                @foreach ($company_tax as $key => $item)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $item->ComTax_ID }}</td>
                                                            @if ($item->Tax_Type == 'Company')
                                                                <td>{{ $item->Companny_name }}</td>
                                                            @else
                                                                <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                                            @endif
                                                            <td>{{ $item->BranchTax }}</td>
                                                            <td style="text-align: center;">
                                                                <input type="hidden" id="status" value="{{ $item->status }}">
                                                                @if ($item->status == 1)
                                                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatusTax({{ $item->id }})">ใช้งาน</button>
                                                                @else
                                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatusTax({{ $item->id }})">ปิดใช้งาน</button>
                                                                @endif
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/viewTax/'.$item->id) }}">view</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/editTax/'.$item->id) }}">Edit</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <input type="hidden" id="profile-company-Tax" name="profile-company" value="{{$Company->Profile_ID}}">
                                        <input type="hidden" id="get-total-company-Tax" value="{{ $company_tax->total() }}">
                                        <input type="hidden" id="currentPage-company-Tax" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="company-Tax-showingEntries">{{ showingEntriesTableTax($company_tax, 'company-Tax') }}</p>
                                                    <div id="company-Tax-paginate">
                                                        {!! paginateTableTax($company_tax, 'company-Tax') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Visit" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <table id="company-VisitTable" class="example2 ui striped table nowrap unstackable hover">
                                        <caption class="caption-top">
                                            <div>
                                                <div class="flex-end-g2">
                                                    <label class="entriespage-label">entries per page :</label>
                                                    <select class="entriespage-button" id="search-per-page-company-Visit" onchange="getPageVisit(1, this.value, 'company-Visit')">
                                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "company-Visit" ? 'selected' : '' }}>10</option>
                                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "company-Visit" ? 'selected' : '' }}>25</option>
                                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "company-Visit" ? 'selected' : '' }}>50</option>
                                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "company-Visit" ? 'selected' : '' }}>100</option>
                                                    </select>
                                                    <input class="search-button search-data-company-Visit" id="company-Visit" style="text-align:left;" placeholder="Search" />
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th class="text-center" data-priority="1">No</th>
                                                <th class="text-center" data-priority="1">ID</th>
                                                <th data-priority="1">Company</th>
                                                <th class="text-center">Issue Date</th>
                                                <th class="text-center">Expiration Date</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th class="text-center">Discount </th>
                                                <th class="text-center">Operated By</th>
                                                <th class="text-center">Document status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Quotation))
                                                @foreach ($Quotation as $key => $item)
                                                <tr>
                                                    <td style="text-align: center;">
                                                        {{$key +1}}
                                                    </td>
                                                    <td>{{ $item->Quotation_ID }}</td>
                                                    <td>{{ @$item->company->Company_Name}}</td>
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->checkin)->format('d/m/Y') }}</td>
                                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->checkout)->format('d/m/Y') }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td style="text-align: center;">
                                                        @if ($item->SpecialDiscountBath	== 0)
                                                            -
                                                        @else
                                                            {{$item->SpecialDiscountBath}}
                                                        @endif
                                                    </td>
                                                    <td >{{ @$item->userOperated->name }}</td>
                                                    <td style="text-align: center;">
                                                        @if($item->status_guest == 1)
                                                            <span class="badge rounded-pill bg-success">Approved</span>
                                                        @else
                                                            @if($item->status_document == 0)
                                                                <span class="badge rounded-pill bg-danger">Cancel</span>
                                                            @elseif ($item->status_document == 1)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                            @elseif ($item->status_document == 2)
                                                                <span class="badge rounded-pill bg-warning">Awaiting Approva</span>
                                                            @elseif ($item->status_document == 3)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                            @elseif ($item->status_document == 4)
                                                                <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                            @elseif ($item->status_document == 6)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <input type="hidden" id="profile-company-Visit" name="profile-company" value="{{$Company->Profile_ID}}">
                                        <input type="hidden" id="get-total-company-Visit" value="{{ $Quotation->total() }}">
                                        <input type="hidden" id="currentPage-company-Visit" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="company-Visit-showingEntries">{{ showingEntriesTableVisit($Quotation, 'company-Visit') }}</p>
                                                    <div id="company-Visit-paginate">
                                                        {!! paginateTableVisit($Quotation, 'company-Visit') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('script.script')

    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the radio buttons
        const Branch = document.getElementById('flexRadioDefaultBranch');

        Branch.addEventListener('change', function() {
            if (Branch.checked) {
                $('#Branch').val('สำนักงานใหญ่');
            }
        });
    });
    $(document).ready(function() {
        var countrySelect = $('#countrySelect');
        var TaxSelectA = countrySelect.val(); // ใช้ jQuery เพื่อเลือก element
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");
        var province = document.getElementById("province");

        if (TaxSelectA == "Other_countries") {
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
            province.disabled = true;

        } else if (TaxSelectA == "Thailand"){
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;
            province.disabled = false;
        }
    });
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
    });
    function showcityInput() {
        var countrySelect = document.getElementById("countrySelect");
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");
        var province = document.getElementById("province");

        // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
        if (countrySelect.value === "Other_countries") {
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
            province.disabled = true;
        } else if (countrySelect.value === "Thailand") {

            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;
            province.disabled = false;
            select_amphures();
            select_province();
            select_Tambon();
            $('#zip_code').empty();
        }
    }
    function select_province() {
        var provinceID = $('#province').val();
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Company/amphures/" + provinceID + "') !!}",
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
            url: "{!! url('/Company/Tambon/" + amphuresID + "') !!}",
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
            url: "{!! url('/Company/districts/" + Tambon + "') !!}",
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
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the form from submitting
        var Company_Name = $('#Company_Name').val();
        var Branch = $('#Branch').val();
        var message = `หากบันทึกข้อมูลบริษัท ${Company_Name} สาขา ${Branch} หรือไม่`;
        Swal.fire({
            title: "คุณต้องการบันทึกใช่หรือไม่?",
            text: message,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "บันทึกข้อมูล",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, submit the form
                document.getElementById("myForm").submit();
            }
        });
    }
    var alertMessage = "{{ session('alert_') }}";
    var alerterror = "{{ session('error_') }}";
    if (alertMessage) {
        // แสดง SweetAlert ทันทีเมื่อโหลดหน้าเว็บ
        Swal.fire({
            icon: 'success',
            title: alertMessage,
            showConfirmButton: false,
            timer: 1500
        });
    }
    if (alerterror) {
        Swal.fire({
            icon: 'error',
            title: alerterror,
            showConfirmButton: false,
            timer: 1500
        });
    }
    </script>
    <script>
        document.getElementById('add-input').addEventListener('click', function() {
            var container = document.getElementById('phone-inputs-container');
            var index = container.querySelectorAll('.input-group').length;
            var newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group');
            newInputGroup.innerHTML = `
            <div class="input-group mb-3">
                <input type="text" name="phone[]" class="form-control phone-input" maxlength="10" value="" data-index="${index}" data-old-value="">
                <button type="button" class="btn btn-outline-danger remove-input" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
            </div>
            `;
            container.appendChild(newInputGroup);
            addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
            addInputChangeListener(newInputGroup.querySelector('.phone-input'));
            updateWindowPhoneChanged();
        });

        document.getElementById('add-fax').addEventListener('click', function() {
            var container = document.getElementById('fax-inputs-container');
            var index = container.querySelectorAll('.input-group').length;
            var newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group');
            newInputGroup.innerHTML = `
                <div class="input-group mb-3">
                    <input type="text" name="fax[]" class="form-control fax-input" maxlength="10" value="" data-index="${index}" data-old-value="">
                    <button class="btn btn-outline-danger remove-input" type="button" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                </div>
            `;
            container.appendChild(newInputGroup);
            addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
            addInputChangeListener(newInputGroup.querySelector('.fax-input'));
            updateWindowPhoneChanged();
        });

        document.querySelectorAll('.remove-input').forEach(function(button) {
            addRemoveButtonListener(button);
        });

        document.querySelectorAll('.phone-input, .fax-input').forEach(function(input) {
            addInputChangeListener(input);
        });

        function addRemoveButtonListener(button) {
            button.addEventListener('click', function() {
                var container = button.closest('.input-group, .input-group');
                container.parentElement.removeChild(container);
                updateIndices();
                updateWindowPhoneChanged();
            });
        }

        function addInputChangeListener(input) {
            input.addEventListener('input', function() {
                if (input.value !== input.getAttribute('data-old-value')) {
                    updateWindowPhoneChanged();
                }
            });
        }

        function updateIndices() {
            var phoneInputs = document.querySelectorAll('.phone-input');
            phoneInputs.forEach(function(input, index) {
                input.setAttribute('data-index', index);
                input.setAttribute('name', `phone[${index}]`);
            });

            var faxInputs = document.querySelectorAll('.fax-input');
            faxInputs.forEach(function(input, index) {
                input.setAttribute('data-index', index);
                input.setAttribute('name', `fax[${index}]`);
            });
        }

        function updateWindowPhoneChanged() {
            window.phoneChanged1 = 1;
            console.log('phoneChanged1:', window.phoneChanged1);
        }
    </script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<!-- dataTable -->
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
<script type="text/javascript" src="{{ asset('assets/helper/searchTableCompany.js')}}"></script>
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
        $(document).on('keyup', '.search-data-company-Tax', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-company-Tax').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(guest_profile);


                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/tax-company-search-table',
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
                    "initComplete": function (settings,json){

                        if ($('#'+id+'Table .dataTable_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        }else{
                            var count = 0;
                        }
                        if (search_value == '') {
                            count_total = total;
                        }else{
                            count_total = count;
                        }
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearchTax(1,count_total, id));
                        $('#'+id+'-paginate').append(paginateSearchTax(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 1, 3,4,5], className: 'dt-center td-content-center' },
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
                        { data: 'Profile_ID_TAX' },
                        { data: 'Company/Individual' },
                        { data: 'Branch' },
                        { data: 'Status' },
                        { data: 'Order' },
                    ],

                });
            document.getElementById(id).focus();
        });
        function btnstatusTax(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/company/change-status/tax/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
    </script>
    <script>
        $('#nav2').on('click', function () {
            new DataTable('#company-VisitTable', {
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
        $(document).on('keyup', '.search-data-company-Visit', function () {

            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-company-Visit').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/Visit-company-search-table',
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
                    "initComplete": function (settings,json){

                        if ($('#'+id+'Table .dataTable_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        }else{
                            var count = 0;
                        }
                        if (search_value == '') {
                            count_total = total;
                        }else{
                            count_total = count;
                        }
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearchVisit(1,count_total, id));
                        $('#'+id+'-paginate').append(paginateSearchVisit(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10], className: 'dt-center td-content-center' },
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
                        { data: 'ID' },
                        { data: 'Company' },
                        { data: 'IssueDate' },
                        { data: 'ExpirationDate' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'Discount' },
                        { data: 'OperatedBy' },
                        { data: 'Documentstatus' },
                    ],

                });
            document.getElementById(id).focus();
        });
    </script>
@endsection
