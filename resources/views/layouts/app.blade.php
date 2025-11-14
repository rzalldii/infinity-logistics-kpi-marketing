<!DOCTYPE html>
<html lang="en">

@include('layouts.partials.header')

<body>
    @include('layouts.partials.navbar')

    @yield('content')

    @include('layouts.partials.footer')
    
    @include('layouts.partials.script')

    @yield('script')
</body>
</html>