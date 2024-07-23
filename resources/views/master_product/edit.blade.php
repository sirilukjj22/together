@extends('layouts.masterLayout')
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

    .card1 {
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

    .input-group{
        float: left;
        width:70%;
    }
    .image_preview {
        display: none; /* Hide the image by default */
        max-width: 100%; /* Ensure the image width does not exceed the container */
        max-height: 100%; /* Ensure the image height does not exceed the container */
        object-fit: contain;
        background-color: #ccc;
         /* Scale the image to maintain aspect ratio */
    }
    .select2{
        width: 100%;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .input-group {
            margin-top: 20px;
            width: 90%;
        }
        .image-preview-container {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap; /* Prevent wrapping */
            gap: 10px;
            margin-top: 20px;
            overflow-x: auto;
            margin-top: 10px;/* Enable horizontal scrolling if needed */
        }
        .image-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .lightbox {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.9); /* Black with opacity */
        }
        .lightbox-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-space {
            margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
        }
    @media (max-width: 768px) {
        .image-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            background-size: cover;
            position: relative;
        }
        .card1 {
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
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Create Product Item.</small>
                <h1 class="h4 mt-1">Create Product Item (เพิ่มโปรดักส์)</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="container">
    <div class="container mt-3">
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="col-12 col-sm-12">
                        <form action="{{url('/Mproduct/master_Mproduct/Mproduct_update/'.$product->id)}}" method="POST"enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="col-12 col-sm-12 row">
                                <form id="image_upload_form" enctype="multipart/form-data">
                                    <div class="col-12 "  style="display: flex; justify-content: center;">
                                        <div class="card1">
                                            <div class="image-container">
                                                <button type="button" class="image-upload-button"></button>
                                                <input type="file" name="imageFile" id="imageFile" accept="image/jpeg, image/png, image/svg" style="display: none;">
                                                <img src="{{ asset($product->image_product)}}" class="image_preview" style="display: block;">
                                                <button class="buttonIcon" type="button" id="imageSubmit" style="display: none;"></button>
                                                <button class="deleteImage" id="deleteImage" type="button" style="display: none;"></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-12" style="display: flex; justify-content: center;">
                                    <div class="input-group mt-5" style="width:40%; position: relative;">
                                        <label for="image_other" class="form-control" style="position: absolute; left: 0; top: 0; height: 100%; width: 100%; pointer-events: none; display: flex; align-items: center; padding-left: 1rem; color: #000000;   border-radius: 5px;">รูปเพิ่มเติม</label>
                                        <input type="file" class="form-control" name="image_other[]" id="image_other" aria-describedby="image_other" aria-label="Upload" multiple style="opacity: 0;">
                                    </div>
                                </div>
                                <div class="col-12 my-3" style="display: flex; justify-content: center;">
                                    <div class="image-preview-container mt-3" id="imagePreviewContainer">
                                        @foreach ($imagePaths as $path)
                                            <img src="{{ asset($path) }}" class="image_preview" alt="Image Preview" style="margin: 0 10px; max-width: 17%;height: auto;display: block; border-radius: 10px;">
                                        @endforeach
                                    </div>
                                </div>
                                <div id="lightbox" class="lightbox" style="display: none;">
                                    <span class="close" onclick="closeLightbox()">&times;</span>
                                    <img class="lightbox-content" id="lightboxImage" alt="Lightbox Image">
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-2">
                                            <label for="category"  style="padding: 5px">Category</label>
                                            <select name="category" id="category" class="select2" style="width:100%" disabled>
                                                <option value="" ></option>
                                                <option value="Room_Type"{{$product->Category == "Room_Type" ? 'selected' : ''}}>Room Type</option>
                                                <option value="Banquet"{{$product->Category == "Banquet" ? 'selected' : ''}}>Banquet</option>
                                                <option value="Meals"{{$product->Category == "Meals" ? 'selected' : ''}}>Meals</option>
                                                <option value="Entertainment"{{$product->Category == "Entertainment" ? 'selected' : ''}}>Entertainment</option>
                                            </select>
                                        </div>
                                        <div class="col-6"></div>
                                        <div class="col-lg-2 col-md-2 col-sm-12" >
                                            <label for="Profile_ID">Profile ID</label><br>
                                            <input type="text" id="Profile_ID" name="Profile_ID"maxlength="70" disabled value="{{$product->Product_ID}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="Name_th">Name_th</label><br>
                                            <input type="text" id="name_th" name="name_th"maxlength="70" value="{{$product->name_th}}">
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="name_en" >Name_en</label><br>
                                            <input type="text" id="name_en" name="name_en"maxlength="70" value="{{$product->name_en}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="detail_th">Detail_th</label><br>
                                            <input type="text" id="detail_th" name="detail_th"maxlength="70" value="{{$product->detail_th}}">
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="detail_en">Detail_en</label><br>
                                            <input type="text" id="detail_en" name="detail_en"maxlength="70" value="{{$product->detail_en}}">
                                        </div>
                                    </div>
                                    <div class=" row">
                                        <div class="col-lg-6 col-sm-12" ><label for="pax">Pax</label><br>
                                            <input type="text" id="pax" name="pax"maxlength="70"  value="{{$product->pax}}">
                                        </div>
                                        <div class="col-lg-6 col-sm-12" ><label for="room_size">Room size (Sqm.)</label><br>
                                            <input type="text" id="room_size" class="price-input" name="room_size"maxlength="70"value="{{$product->room_size}}">
                                        </div>
                                    </div>
                                    <div class=" row">
                                        @if ($product->Category == "Room_Type")
                                            <div class="col-lg-3 col-sm-12" id="normal_price_container" style="display: block;">
                                                <label for="normal_price">Normal Price <br>(Include VAT)</label><br>
                                                <input type="text" class="price-input" id="normal_price" name="normal_price" maxlength="70" value="{{$product->normal_price}}">
                                            </div>
                                            <div class="col-lg-3 col-sm-12" id="weekend_price_container" style="display: block;">
                                                <label for="weekend_price">Weekday Price <br>(Include VAT)</label><br>
                                                <input type="text" class="price-input" id="weekend_price" name="weekend_price" maxlength="70"value="{{$product->weekend_price}}">
                                            </div>
                                            <div class="col-lg-3 col-sm-12" id="long_weekend_price_container" style="display: block;">
                                                <label for="long_weekend_price">Long Weekend Price<br> (Include VAT)</label><br>
                                                <input type="text" class="price-input" id="long_weekend_price" name="long_weekend_price" maxlength="70"value="{{$product->long_weekend_price}}">
                                            </div>
                                            <div class="col-lg-3 col-sm-12" id="end_weekend_price_container" style="display: block;">
                                                <label for="end_weekend_price">End Weekend Price<br> (Include VAT)</label><br>
                                                <input type="text" class="price-input" id="end_weekend_price" name="end_weekend_price" maxlength="70"value="{{$product->end_weekend_price}}">
                                            </div>
                                        @else
                                            <div class="col-lg-3 col-sm-12" id="normal_price_container" style="display: block;">
                                                <label for="normal_price">Normal Price <br>(Include VAT)</label><br>
                                                <input type="text" class="price-input" id="normal_price" name="normal_price" maxlength="70" value="{{$product->normal_price}}">
                                            </div>
                                        @endif
                                    </div>
                                    <div class=" row">
                                        <div class="col-lg-4 col-sm-12" >
                                            <label for="Quantity"  style="padding: 5px">Quantity</label>
                                            <select name="quantity"  id = "quantity" class="select2">
                                                <option value=""></option>
                                                @foreach($quantity as $item)
                                                    <option value="{{ $item->id }}"{{$product->quantity== $item->id ? 'selected' : ''}}>{{ $item->name_th }} ({{ $item->name_en }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12" >
                                            <label for="Unit" style="padding: 5px">Unit</label>
                                            <select name="unit" id = "unit" class="select2">
                                                <option value=""></option>
                                                @foreach($unit as $item)
                                                    <option value="{{ $item->id }}"{{$product->unit== $item->id ? 'selected' : ''}}>{{ $item->name_th }} ({{ $item->name_en }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12" ><label for="Maximum_Discount">Maximum Discount</label><br>
                                            <input type="text" id="Maximum_Discount" name="Maximum_Discount"maxlength="70" required value="{{$product->maximum_discount}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-12"></div>
                                        <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                            <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('Mproduct.index') }}'">{{ __('ย้อนกลับ') }}</button>
                                            <button type="submit" class="btn btn-color-green lift ">บันทึกข้อมูล</button>
                                        </div>
                                        <div class="col-lg-3 col-sm-12"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>

 $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
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
                imageContainer.classList.add('background-white');
                // แสดงปุ่มลบ
            }
        });
        $(document).on('click', '#deleteImage', function(event) {
            event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

            let img = document.querySelector('.image_preview');
            img.src = ""; // ตั้งค่า src ของรูปเป็นค่าว่าง

            img.style.display = 'none'; // ซ่อนรูปภาพ
            let imageUploadButton = document.querySelector('.image-upload-button');
            imageUploadButton.style.display = 'block'; // แสดงปุ่มอัปโหลด
            let deleteImage = document.querySelector('.deleteImage');
            deleteImage.style.display = 'none'; // ซ่อนปุ่มลบ

            let inputImageFile = document.getElementById('imageFile');
            inputImageFile.value = ""; // ตั้งค่าค่า value เป็นค่าว่าง
            location.reload();
        });
        // ส่งรูปภาพไปยังเซิร์ฟเวอร์เมื่อฟอร์มถูกส่ง
    });
    document.getElementById('image_other').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = ''; // Clear existing images

        if (files.length > 5) {
            alert('You can upload a maximum of 5 images.');
            event.target.value = ''; // Clear the input field
            return;
        }

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'image-preview';
                img.addEventListener('click', () => openLightbox(e.target.result));
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });

    function openLightbox(src) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        lightbox.style.display = 'block';
        lightboxImage.src = src;
    }


    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.style.display = 'none';
    }
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
</script>
@endsection
