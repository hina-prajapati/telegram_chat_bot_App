<!DOCTYPE html>
<html lang="en">
@include('pages.partials.header')
<body>
    <!-- Topbar Start -->
  @include('pages.partials.topbar')
    <!-- Topbar End -->


    <!-- Navbar Start -->
    {{-- @include('pages.partials.navbar') --}}
    <!-- Navbar End -->

     @yield('main-content')
        

    <!-- Footer Start -->
    @include('pages.partials.footer')
    <!-- Footer End -->



</body>

</html>