<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>TOGETHER DEVELOPMENT</title>

    <link rel="icon" href="{{ asset('image/Logo1-01.png') }}" type="image/x-icon"> <!-- Favicon -->
    <link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai+Looped:wght@100;200;300;400;500;600;700;800;900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets2/css/dataTables.dataTables.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background: #e4e4e4;">
    @php
        $role_permisstion = App\Models\Role_permission_menu::where('user_id', Auth::user()->id)->first();
    @endphp
    <div class="navi">
        <nav class="bg-white border-gray-200 dark:bg-gray-900"
            style="background: linear-gradient(180deg,rgba(45, 127, 123, 1) 0%,rgba(9, 49, 69, 1) 100%);">
            <div class="max-w-screen-xl flex flex-wrap iteFms-center justify-between mx-auto p-4">
                <a href="{{ route('sms-alert') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset('assets2/images/Logo.png') }}" class="h-12" alt="" />
                    <span class="self-center text-lg font-semibold whitespace-nowrap text-white">Together
                        Development</span>
                </a>
                <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900 dark:text-white">Name</span>
                            <span
                                class="block text-sm  text-gray-500 truncate dark:text-gray-400">name@together.com</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            @if ($role_permisstion->sms_alert == 1)
                                <li>
                                    <a href="{{ route('sms-alert') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        SMS Alert
                                    </a>
                                </li>
                            @endif
                            @if ($role_permisstion->revenue == 1)
                                <li>
                                    <a href="{{ route('revenue') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        Revenue
                                    </a>
                                </li>
                            @endif

                            @if ($role_permisstion->user == 1)
                                <li>
                                    <a href="{{ route('users', 'index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        User
                                    </a>
                                </li>
                            @endif
                            @if ($role_permisstion->bank == 1)
                                <li>
                                    <a href="{{ route('master', 'bank') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        Master Bank
                                    </a>
                                </li>
                            @endif

                            @if ($role_permisstion->company == 1)
                                <li>
                                    <a href="{{ route('Company.index') }}"
                                        class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                        Company
                                    </a>
                                </li>
                            @endif
                            @if ($role_permisstion->guest == 1)
                                <li>
                                    <a href="{{ route('guest.index') }}"
                                        class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                        Guest
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    Sign out
                                </a>
                            </li>
                        </ul>
                    </div>
                    <button data-collapse-toggle="navbar-user" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="navbar-user" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>

                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                    <ul
                        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        @if ($role_permisstion->sms_alert == 1)
                            <li>
                                <a href="{{ route('sms-alert') }}"
                                    class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                    SMS Alert
                                </a>
                            </li>
                        @endif
                        @if ($role_permisstion->revenue == 1)
                            <li>
                                <a href="{{ route('revenue') }}"
                                    class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                    Revenue
                                </a>
                            </li>
                        @endif

                        @if ($role_permisstion->user == 1)
                            <li>
                                <a href="{{ route('master', 'bank') }}"
                                    class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                    Master Bank
                                </a>
                            </li>
                        @endif
                        @if ($role_permisstion->bank == 1)
                            <li>
                                <a href="{{ route('master', 'bank') }}"
                                    class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                    Master Bank
                                </a>
                            </li>
                        @endif
                        @if ($role_permisstion->company == 1)
                            <li>
                                <a href="{{ route('Company.index') }}"
                                    class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                    Company
                                </a>
                            </li>
                        @endif
                        @if ($role_permisstion->guest == 1)
                            <li>
                                <a href="{{ route('guest.index') }}"
                                    class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                                    Guest
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="#"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                data-toggle="modal" data-target="#staticBackdrop">
                                Sign out
                            </a>
                        </li>

                        <!-- Button trigger modal -->
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    {{-- <div class="for100vh"> --}}
    <div class="wrapper">
        <div class="menu">
            <div class="logo">
                <a href="{{ route('sms-alert') }}"><img src="{{ asset('assets2/images/Logo.png') }}"
                        id="logo_togeter"></a>
            </div>
            <h1>Menu</h1>

            @if ($role_permisstion->sms_alert == 1)
                <a href="{{ route('sms-alert') }}">
                    <div class="<?php echo $_SERVER['REQUEST_URI'] == '/sms-alert' ? 'active' : 'menu2'; ?>">SMS Alert</div>
                </a>
            @endif
            @if ($role_permisstion->revenue == 1)
                <a href="{{ route('revenue') }}">
                    <div class="<?php echo $_SERVER['REQUEST_URI'] == '/revenue' ? 'active' : 'menu2'; ?>">Revenue</div>
                </a>
            @endif
            @if ($role_permisstion->user == 1)
                <a href="{{ route('users', 'index') }}">
                    <div class="<?php echo $_SERVER['REQUEST_URI'] == '/users/index' ? 'active' : 'menu2'; ?>">Users</div>
                </a>
            @endif
            @if ($role_permisstion->bank == 1)
                <a href="{{ route('master', 'bank') }}">
                    <div class="<?php echo $_SERVER['REQUEST_URI'] == '/master/bank' ? 'active' : 'menu2'; ?>">Bank</div>
                </a>
            @endif

            @if ($role_permisstion->debtor == 1)
            <div class="dropdown">
                <button onclick="myFunctionDebtor()" class="dropbtn">Debtor &nbsp; 
                    <i class="fa-solid fa-caret-down"></i></button>
                <div id="myDropdownDebtor" class="dropdown-content">
                    @if ($role_permisstion->agoda == 1)
                        <a class="menu2" href="{{ route('debit-agoda-revenue') }}">Agoda</a>
                    @endif
                    @if ($role_permisstion->elexa == 1)
                        <a class="menu2" href="#">Elexa</a>
                    @endif
                </div>
            </div>
            @endif

            @if ($role_permisstion->profile == 1)
            <div class="dropdown">
                <button onclick="myFunction()" class="dropbtn">Profile &nbsp; <i
                        class="fa-solid fa-caret-down"></i></button>
                <div id="myDropdown" class="dropdown-content">
                    @if ($role_permisstion->company == 1)
                        <a class="menu2" href="{{ route('Company.index') }}">Company</a>
                    @endif
                    @if ($role_permisstion->guest == 1)
                        <a class="menu2" href="{{ route('guest.index') }}">Guest</a>
                    @endif
                </div>
            </div>
            @endif

            <div class="">
                <!-- Button trigger modal -->
                <button type="button" class="menu3" style="width:100%;" data-toggle="modal"
                    data-target="#exampleModalCenter3">
                    Logout
                </button>
            </div>
        </div>

        @yield('content')

    </div>
    {{-- </div> --}}

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Log out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align: center; font-size: 24px; font-weight: 600; padding: 84px;">
                    <img src="{{ asset('assets2/images/logout.png') }}" alt=""><br>
                    Are You Sure to Logout
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-17 button-18"
                        style="background-color: #f44336; color: black;" data-dismiss="modal">Close</button>
                    <button type="button" class="button-17 button-18"
                        onclick="location.href='{{ route('logout') }}'">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter3" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center; font-size: 28px; font-weight: 600; padding: 84px;">
                    <img src="{{ asset('assets2/images/logout.png') }}" alt=""><br>
                    Are You Sure to Logout
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-17 button-18"
                        style="background-color: #f44336; color: white;" data-dismiss="modal">Close</button>
                    <button type="button" class="button-17 button-18"
                        onclick="location.href='{{ route('logout') }}'">Logout</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    /* When the user clicks on the button, toggle between hiding and showing the dropdown content */

    function myFunctionDebtor() {
        var dropdownDebtor = document.getElementById("myDropdownDebtor");

        dropdownDebtor.classList.toggle("show");

        if (dropdownDebtor.classList.contains("show")) {
            dropdownDebtor.style.maxHeight = dropdownDebtor.scrollHeight + "px";
        } else {
            dropdownDebtor.style.maxHeight = "0";
        }
    }

    function myFunction() {
        var dropdown = document.getElementById("myDropdown");

        dropdown.classList.toggle("show");

        if (dropdown.classList.contains("show")) {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        } else {
            dropdown.style.maxHeight = "0";
        }
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches(".dropbtn")) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                // if (openDropdown.classList.contains("show")) {
                //   openDropdown.classList.remove("show");
                //   openDropdown.style.maxHeight = "0";
                // }
            }
        }
    }
</script>

</html>
