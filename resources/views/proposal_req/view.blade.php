@extends('layouts.masterLayout')
<style>

    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
    .com {
        display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
        border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
        padding-bottom: 5px;
        font-size: 20px;
    }
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Proposal Request View.</small>
                    <div class=""><span class="span1">Proposal Request View</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
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
                        <form id="myForm" action="{{route('DummyQuotation.Reject')}}" method="POST">
                            @csrf
                            @foreach($proposal as  $key => $item )
                                <input type="hidden" name="DummyNo[]" value="{{$item->DummyNo}}">
                                <input type="hidden" name="QuotationType" value="{{$item->QuotationType}}">
                            @endforeach
                            <button type="submit" class="btn btn-secondary lift btn_modal"style="float: right" >Reject</button>
                        </form>
                    </div>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="col-md-12 col-12 row">
                @foreach($proposal as  $key => $item )
                    @php
                        $priceless50 =0;
                        $price50=0;
                        $Add50=0;
                        $Net50=0;
                        $pricebefore52 =0;
                        $price51=0;
                        $price52=0;
                        $Add52=0;
                        $Net52=0;
                        $sp50=0;
                        $sp51=0;
                        $sp52=0;
                        $sp = 0;
                    @endphp
                    <div class="col-6">
                        <div class="card mb-4" style="height: 830px;  overflow-x: hidden; overflow-y: auto;">
                            <div class='card-body'>
                                <h5 class="com">รหัสใบข้อเสนอ : {{$item->DummyNo}}</h5>
                                <input type="hidden" name="DummyNo[]" id="DummyNo" class="DummyNo" value="{{$item->DummyNo}}">
                                <div class="row">
                                    <div class="col-6">
                                        <span>จำนวนผู้เข้าพัก : {{$item->adult}} ผู้ใหญ่ {{$item->children}} เด็ก</span>
                                    </div>
                                    <div class="col-6">
                                        <span>Special Discount :  {{ $item->SpecialDiscount ?? 0 }}%</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        @if ($item->type_Proposal == 'Company')
                                            <span>นามบริษัท : {{ @$item->company->Company_Name}}</span>
                                        @else
                                            <span>นามบุคคล : {{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</span>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span>ตัวแทน : {{@$item->contact2->First_name ?? '-'}} {{@$item->contact2->Last_name}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <span>วันที่สร้างเอกสาร : {{@$item->issue_date}}</span>
                                    </div>
                                    <div class="col-6">
                                        <span>วันที่หมดอายุเอกสาร : {{@$item->Expirationdate}}</span>
                                    </div>
                                </div>
                                <table class="example ui striped table nowrap unstackable hover" style="width:100%;overflow-x: hidden; overflow-y: auto;" >
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th>DESCRIPTION</th>
                                            <th style="width: 5%">QUANTITY</th>
                                            <th style="width: 5%">DISCOUNT</th>
                                            <th style="width: 5%">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(@$item->document as $key =>$itemproduct)
                                                <tr>
                                                    <td style="text-align: center;">{{ $key+1}}</td>
                                                    <td>{{@$itemproduct->product->name_th}}</td>
                                                    <td style="text-align: center;">

                                                        {{@$itemproduct->Quantity }}
                                                    </td>
                                                    <td style="text-align: center;">

                                                        {{@$itemproduct->discount }}
                                                    </td>
                                                    <td style="text-align: center;">

                                                        {{ number_format(@$itemproduct->netpriceproduct) }}

                                                    </td>
                                                </tr>
                                                @php
                                                    $price50 += @$itemproduct->netpriceproduct;
                                                    $sp = $item->SpecialDiscountBath;
                                                    $sp50 = $price50-$item->SpecialDiscountBath;
                                                    $priceless50 = $sp50/1.07;
                                                    $Add50 = $sp50-$priceless50;
                                                    $Net50 = $priceless50+$Add50;

                                                    $price51 += @$itemproduct->netpriceproduct;
                                                    $sp51 = $price51-$item->SpecialDiscountBath;
                                                    $price52 += @$itemproduct->netpriceproduct;
                                                    $sp52 = $price52-$item->SpecialDiscountBath;
                                                    $Add52 = $sp52*7/100;
                                                    $pricebefore52 = $price52+$Add52;
                                                @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row mt-2">
                                    @if ($item->vat_type = 50)
                                        <div class="col-6"></div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-8">
                                                    <span id="Subtotal">Subtotal : </span><br>
                                                    <span id="Special">Special Discount : </span><br>
                                                    <span id="less">Subtotal less Discount : </span><br>
                                                    <span id="Before">Price Before Tax :</span><br>
                                                    <span id="Added">Value Added Tax : </span><br>
                                                    <span id="Net">Net Total : </span><br>
                                                </div>
                                                <div class="col-4">
                                                    {{ number_format($price50, 2, '.', ',') }} <br>
                                                    {{ number_format($sp, 2, '.', ',') }}<br>
                                                    {{ number_format($sp50, 2, '.', ',') }}<br>
                                                    {{ number_format($priceless50, 2, '.', ',') }}<br>
                                                    {{ number_format($Add50, 2, '.', ',') }}<br>
                                                    {{ number_format($Net50, 2, '.', ',') }}<br>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($item->vat_type = 51)
                                        <div class="col-6"></div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-8">
                                                    <span id="Subtotal">Subtotal : </span><br>
                                                    <span id="Special">Special Discount : </span><br>
                                                    <span id="less">Subtotal less Discount : </span><br>
                                                    <span id="Net">Net Total : </span><br>
                                                </div>
                                                <div class="col-4">
                                                    {{ number_format($price51, 2, '.', ',') }} <br>
                                                    {{ number_format($sp, 2, '.', ',') }}<br>
                                                    {{ number_format($sp51, 2, '.', ',') }}<br>
                                                    {{ number_format($sp51, 2, '.', ',') }} <br>
                                                </div>
                                            </div>

                                        </div>
                                    @elseif ($item->vat_type = 52)
                                        <div class="col-6"></div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-8">
                                                    <span id="Subtotal">Subtotal : </span><br>
                                                    <span id="Special">Special Discount : </span><br>
                                                    <span id="less">Subtotal less Discount : </span><br>
                                                    <span id="Added">Value Added Tax : </span><br>
                                                    <span id="Net">Net Total : </span><br>
                                                </div>
                                                <div class="col-4">
                                                    {{ number_format($price52, 2, '.', ',') }} <br>
                                                    {{ number_format($sp, 2, '.', ',') }}<br>
                                                    {{ number_format($sp52, 2, '.', ',') }} <br>
                                                    {{ number_format($Add52, 2, '.', ',') }} <br>
                                                    {{ number_format($pricebefore52, 2, '.', ',') }} <br>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <form id="myForm" action="{{route('DummyQuotation.Approve')}}" method="POST">
                                @csrf
                                <div class="row mt-2 my-4">
                                    <div class="col-3"></div>
                                    <input type="hidden" name="DummyNo" value="{{$item->DummyNo}}">
                                    <input type="hidden" name="QuotationType" value="{{$item->QuotationType}}">
                                    <div class="col-6" style="display:flex; justify-content:center; align-items:center;">
                                        <button type="submit" class="btn btn-success lift btn_modal" >
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                    </div>
                                    <div class="col-3">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
                <div class="col-12 row mt-5">
                    <div class="col-4"></div>
                    <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                        <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('ProposalReq.index') }}'">
                            Back
                        </button>
                    </div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    @include('script.script')
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
    </script>

@endsection
