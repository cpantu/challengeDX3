<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('partials.head')
<body>
@include('partials.header')

<div id="app">
    <property-detail id="{{\request()->route('id')}}"></property-detail>
</div>

@include('partials.footer')
</body>
</html>
