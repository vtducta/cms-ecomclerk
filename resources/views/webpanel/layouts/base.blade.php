@include('webpanel.includes.header')
@include('webpanel.includes.sidebar')

<div class="page-wrapper">
    <div class="container-fluid">
        @yield('body')
        </div>
</div>

@include('webpanel.includes.footer')