<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Laravel</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Styles -->
</head>

<body>
  @include('layouts.header')
  <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
    @include('layouts.sidebar')

    <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
      <main class="calc(100vh-70px) bg-slate-50 px-4 pt-6">@yield('main')</main>
      {{-- @include('layouts.footer') --}}
    </div>
  </div>

  @stack('script')
</body>

</html>
