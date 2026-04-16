<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
</head>
<body>
    @include('layouts.partials.sidebar')

    @include('layouts.partials.navbar')

    @yield('content')

    @include('layouts.partials.footer')
    
    @include('layouts.partials.script')

    @yield('script')
</body>
</html>