<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('partials.head')
    <body>
    @include('partials.header')

    <div id="app">
        <properties-list></properties-list>
    </div>
        @include('partials.footer')
    </body>
</html>
