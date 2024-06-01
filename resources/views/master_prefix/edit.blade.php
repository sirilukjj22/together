@extends('layouts.test')

@section('content')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }
        .button-guest{
        background-color: #2D7F7B;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        border-width: 1px;
        border-radius: 8px;
        float: right;
        margin-Top: 10px;
        margin-Left: 100px;
        text-align: center;

        }
        .button-guest-end{
            background-color:#ff0000;
            color: whitesmoke;
            border-color: #9a9a9a;
            border-style: solid;
            width: 30%;
            float: left;
            border-width: 1px;
            border-radius: 8px;
            margin-Top: 10px;
            text-align: center;

        }
    </style>
    <div class="Usertable">
        <div class="usertopic">
            <h1>Master Company type</h1>
        </div>
        <br>
        <form action="{{url('/Mprefix/master_prefix/Mprefix_update/'.$M_prefix->id)}}" method="POST">
        {!! csrf_field() !!}
            <div>
                <div class="col-12 row">
                    <div class="col-2" ></div>
                    <div class="col-4" >
                        <label for="Name_th">Name_th</label><br>
                        <input type="text" id="name_th" name="name_th"maxlength="70" required value="{{$M_prefix->name_th}}">
                    </div>
                    <div class="col-4" >
                        <label for="name_en">Name_en</label><br>
                        <input type="text" id="name_en" name="name_en"maxlength="70" required value="{{$M_prefix->name_en}}">
                    </div>
                </div>
                <div class="col-12 row mt-2">
                    <div class="col-2"></div>
                    <div class="col-4">
                        <div class="button-guest">
                            <button type="submit" class="btn">ตกลง</button>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="button-guest-end">
                            <button type="button" class="btn" onclick="window.location.href='{{ route('Mprefix.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


@endsection
