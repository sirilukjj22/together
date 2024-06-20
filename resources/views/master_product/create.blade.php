@extends('layouts.test')

@section('content')
<style>
    input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type=tel], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="date"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
        font-size: 16px;
        background-color: #f8f8f8; /* เพิ่มสีพื้นหลัง */
    }
    input[type="number"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }



    .add-phone:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }


    .card {
        width: 460px; /* กำหนดความกว้างตามต้องการ */
        height: 300px; /* กำหนดความสูงตามต้องการ */
        background-color: #fff;
        border: 1px solid #ccc; /* เพิ่มเส้นขอบ */
        border-radius: 10px; /* เพิ่มมุมโค้งมน */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
        overflow: hidden; /* ซ่อนส่วนเกิน */
        position: relative; /* สำหรับการวางปุ่ม */
    }

    .image-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        background: url('{{ asset('assets2/images/no-image.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
    }

    .image-upload-button {
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 10px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 32px; /* ขนาดของปุ่ม */
        background: url('{{ asset('assets2/images/photo-camera.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
    }
    .deleteImage{
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 10px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 32px; /* ขนาดของปุ่ม */
        background: url('{{ asset('assets2/images/delete.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }
    .input-group{
        float: left;
        width:70%;
    }
    .modal-content {
        display: flex;
        flex-direction: column;
        align-items: center; /* จัดตำแหน่งลูกในแนวนอน */
        justify-content: center; /* จัดตำแหน่งลูกในแนวตั้ง */
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    #cropImage {
        max-width: 100%;
    }
    .button-return {
        align-items: center;
        appearance: none;
        background-color: #6b6b6b;
        border-radius: 8px;
        border-style: none;
        box-shadow: rgba(0, 0, 0, 0.2) 0 3px 5px -1px,
        rgba(0, 0, 0, 0.14) 0 6px 10px 0, rgba(0, 0, 0, 0.12) 0 1px 18px 0;
        box-sizing: border-box;
        color: #ffffff;
        cursor: pointer;
        display: inline-flex;
        fill: currentcolor;
        font-size: 14px;
        font-weight: 500;
        height: 40px;
        justify-content: center;
        letter-spacing: 0.25px;
        line-height: normal;
        max-width: 100%;
        overflow: visible;
        padding: 2px 24px;
        position: relative;
        text-align: center;
        text-transform: none;
        transition: box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1),
        opacity 15ms linear 30ms, transform 270ms cubic-bezier(0, 0, 0.2, 1) 0ms;
        touch-action: manipulation;
        width: auto;
        will-change: transform, opacity;
        margin-left: 5px;
    }

    .button-return:hover {
        background-color: #ffffff !important;
        color: #000000;
        transform: scale(1.1);
    }
    @media (max-width: 768px) {
        .image-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            background: url('{{ asset('assets2/images/no-image.jpg') }}') no-repeat center center;
            background-size: cover;
            position: relative;
        }
        .card {
            width: 260px; /* กำหนดความกว้างตามต้องการ */
            height: 200px; /* กำหนดความสูงตามต้องการ */
            background-color: #fff;
            border: 1px solid #ccc; /* เพิ่มเส้นขอบ */
            border-radius: 10px; /* เพิ่มมุมโค้งมน */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
            overflow: hidden; /* ซ่อนส่วนเกิน */
            position: relative; /* สำหรับการวางปุ่ม */
        }
        .input-group{
            float: left;
            width:100%;
        }
        .col-4{
            width: 100%;
        }
        .col-7{
            width: 100%;
        }
        .col-6{
            width: 100%;
        }
        .col-3{
            width: 100%;
        }
        h1{
            margin-top:32px;
        }
    }
</style>
    <div  class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <h1>Master Product Item</h1>
        <br>
        <form action="{{route('Mproduct.save')}}" method="POST"enctype="multipart/form-data">
        {!! csrf_field() !!}
            <div>
                <div class="col-12 row">
                    <div class="col-lg-5 col-md-5 col-sm-5 mt-4">
                        <form id="image_upload_form" enctype="multipart/form-data">
                            <div class="card">
                                <div class="image-container">
                                    <button type="button" class="image-upload-button" ></button>
                                    <input type="file" name="imageFile" id="imageFile" accept="image/jpeg, image/png, image/svg" required style="display: none;">
                                    <img src="" width="460" height="300" class="image_preview" style="display: none;">
                                    <button class="buttonIcon" type="button" id="imageSubmit" style="display: none;"></button>
                                    <button class="deleteImage" id="deleteImage" type="button" style="display: none;"></button>
                                </div>
                            </div>
                        </form>
                        <div class="input-group mt-5 " >
                            <input type="file" class="form-control " name="image_other[]" id="image_other" aria-describedby="image_other" aria-label="Upload" multiple>
                        </div>
                    </div>
                    <div class="col-7 ">
                        <div class="row">
                            <div class="col-4  col-md-4 col-sm-12 mt-2">
                                <label for="Profile_ID">Profile ID</label><br>
                                <input type="text" id="Profile_ID" name="Profile_ID"maxlength="70" disabled>
                            </div>
                            <div class="col-4 col-md-4 col-sm-12 mt-2">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="select2" >
                                    <option value="" ></option>
                                    <option value="Room_Type">Room Type</option>
                                    <option value="Banquet">Banquet</option>
                                    <option value="Meals">Meals</option>
                                    <option value="Entertainment">Entertainment</option>
                                </select>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-12">
                                <label for="Name_th">Name_th</label><br>
                                <input type="text" id="name_th" name="name_th"maxlength="70" >

                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-12">
                                <label for="name_en" >Name_en</label><br>
                                <input type="text" id="name_en" name="name_en"maxlength="70" >
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-12">
                                <label for="detail_th">Detail_th</label><br>
                                <input type="text" id="detail_th" name="detail_th"maxlength="70" >
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-12">
                                <label for="detail_en">Detail_en</label><br>
                                <input type="text" id="detail_en" name="detail_en"maxlength="70" >
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-6" ><label for="pax">Pax</label><br>
                                <input type="text" id="pax" name="pax"maxlength="70" >
                            </div>
                            <div class="col-6" ><label for="room_size">Room size (Sqm.)</label><br>
                                <input type="text" id="room_size" class="price-input" name="room_size"maxlength="70">
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-3" >
                                <label for="normal_price">Normal Price <br>(Include VAT)</label><br>
                                <input type="text"  class="price-input"id="normal_price" name="normal_price"maxlength="70" >
                            </div>
                            <div class="col-3 ">
                                <label for="weekend_price">Weekday Price (Include VAT)</label><br>
                                <input type="text"  class="price-input"id="weekend_price" name="weekend_price"maxlength="70">
                            </div>
                            <div class="col-3" >
                                <label for="long_weekend_price">Long Weekend Price (Include VAT)</label><br>
                                <input type="text"  class="price-input"id="long_weekend_price" name="long_weekend_price"maxlength="70">
                            </div>
                            <div class="col-3" >
                                <label for="long_weekend_price">End Weekend Price (Include VAT)</label><br>
                                <input type="text"  class="price-input"id="end_weekend_price" name="end_weekend_price"maxlength="70">
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-4" ><label for="Quantity">Quantity</label><br>
                                <select name="quantity" id = "quantity" class="select2">
                                    <option value=""></option>
                                    @foreach($quantity as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }} ({{ $item->name_en }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4" ><label for="Unit">Unit</label><br>
                                <select name="unit" id = "unit" class="select2">
                                    <option value=""></option>
                                    @foreach($unit as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }} ({{ $item->name_en }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4" ><label for="Maximum_Discount">Maximum Discount</label><br>
                                <input type="text" id="Maximum_Discount" name="Maximum_Discount"maxlength="70" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row">
                        <div class="col-6 "></div>
                        <div class="col-6 " style="display:flex; justify-content:center; align-items:center;">
                            <button type="button" class="button-return" onclick="window.location.href='{{ route('Mproduct.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                            <button type="submit" class="button-10" style="background-color: #109699;">บันทึกข้อมูล</button>
                        </div>

                    </div>
                    </div>
                </div>

    </form>

</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>

    $(document).ready(function(){
        $('#identification_number').mask('0-0000-00000-00-0');
    });
    $(document).ready(function() {
        $('#category').change(function() {
            var selectedCategory = $(this).val();
            $.ajax({
                url: '/Mproduct/check/Category', // Your Laravel route
                type: 'POST',
                data: {
                    category: selectedCategory,
                    _token: '{{ csrf_token() }}' // Ensure CSRF token is included
                },
                success: function(response) {
                    $('#Profile_ID').val(response.data);
                    console.log(response.data);
                },
                error: function(xhr) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
        });
    });
    var priceInputs = document.querySelectorAll('.price-input');

    // ฟังก์ชันในการจัดรูปแบบตัวเลข
    function formatNumberWithCommas(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // ติดตั้ง event listener สำหรับแต่ละฟิลด์ input
    priceInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            var value = parseFloat(this.value).toFixed(2);
            if (!isNaN(value)) {
                this.value = formatNumberWithCommas(value);
            }
        });
    });
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // เปิด file input เมื่อคลิกที่ปุ่มอัปโหลด
        $(document).on('click', '.image-upload-button', function() {
            $('#imageFile').click();
        });

        // แสดงตัวอย่างรูปภาพเมื่อมีการเลือกไฟล์
        $(document).on('change', '#imageFile', function() {
            if (this.files && this.files[0]) {
                let img = document.querySelector('.image_preview');
                let buttonIcon = document.querySelector('.buttonIcon');
                let imageUploadButton = document.querySelector('.image-upload-button');
                let deleteImage = document.querySelector('.deleteImage');

                // รีเซ็ตรูปภาพเก่า

                // ตั้งค่าใหม่สำหรับรูปภาพที่ถูกเลือก
                img.onload = () => {
                    URL.revokeObjectURL(img.src);
                };
                img.src = URL.createObjectURL(this.files[0]);
                img.style.display = 'block'; // แสดงรูปภาพที่อัปโหลด
               // buttonIcon.style.display = 'block'; // แสดงปุ่มไอคอน
                imageUploadButton.style.display = 'none'; // ซ่อนปุ่มอัปโหลด
                deleteImage.style.display = 'block';
                // แสดงปุ่มลบ
            }
        });
        $(document).on('click', '#deleteImage', function(event) {
            event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

            let img = document.querySelector('.image_preview');
            img.src = ""; // ตั้งค่า src ของรูปเป็นค่าว่าง

            img.style.display = 'none'; // ซ่อนรูปภาพ
            let buttonIcon = document.querySelector('.buttonIcon');
            buttonIcon.style.display = 'none'; // ซ่อนปุ่มไอคอน
            let imageUploadButton = document.querySelector('.image-upload-button');
            imageUploadButton.style.display = 'block'; // แสดงปุ่มอัปโหลด
            let deleteImage = document.querySelector('.deleteImage');
            deleteImage.style.display = 'none'; // ซ่อนปุ่มลบ

            let inputImageFile = document.getElementById('imageFile');
            inputImageFile.value = ""; // ตั้งค่าค่า value เป็นค่าว่าง
        });
        // ส่งรูปภาพไปยังเซิร์ฟเวอร์เมื่อฟอร์มถูกส่ง
        $(document).on("click", '#imageSubmit', function(e) {
            const fileInput = document.getElementById('imageFile');
            const file = fileInput.files[0];
            var formData = new FormData();

            formData.append('image', file);

            $.ajax({
                type: 'POST',
                url: "{{route('Mproduct.save')}}", // ตรวจสอบ URL
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (response) => {
                    console.log(response.success);
                    // รีเซ็ตฟอร์มหรือแสดงข้อความสำเร็จ
                },
                error: function(data) {

                    console.log('Error:', data);
                }
            });
        });
    });

    </script>


</script>
























    @if(Session::has('error'))
    <script>
        swal({
            title: "{{ Session::get('error') }}",
            icon: "error",
            customClass: {
                title: "my-swal-title" // กำหนดคลาสใหม่สำหรับข้อความหัวเรื่อง
            }
        });

            // แสดงการแจ้งเตือน (error) ด้วย JavaScript โดยใช้ค่าจาก Controller
        var msg = "{{ $msg ?? '' }}"; // กำหนดค่า msg จาก Controller
        if (msg) {
            alert(msg);
        }
    </script>
    @endif
    @if(Session::has('alert'))
    <script>
        swal({
            title: "{{ Session::get('alert') }}",
            icon: "success",
            customClass: {
                title: "my-swal-title" // กำหนดคลาสใหม่สำหรับข้อความหัวเรื่อง
            }
        });

            // แสดงการแจ้งเตือน (alert) ด้วย JavaScript โดยใช้ค่าจาก Controller
            var msg = "{{ $msg ?? '' }}"; // กำหนดค่า msg จาก Controller
        if (msg) {
            alert(msg);
        }
    </script>
    @endif

@endsection
