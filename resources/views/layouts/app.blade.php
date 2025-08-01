@include('layouts.header')

<div class="wrapper">
    <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- End Sidebar -->

    <div class="main-panel">

        {{-- main_header --}}
        @include('layouts.main_header')
        {{-- Enad_main --}}

        {{-- main_contain --}}


        <div style="padding-top:65px;">
            @yield('data_one')
         
        </div>
    </div>
</div>
@stack('scripts')
@include('layouts.footer')
