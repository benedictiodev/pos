<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Benedictio Dev - POS</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: "#eff6ff",
              100: "#dbeafe",
              200: "#bfdbfe",
              300: "#93c5fd",
              400: "#60a5fa",
              500: "#3b82f6",
              600: "#2563eb",
              700: "#1d4ed8",
              800: "#1e40af",
              900: "#1e3a8a",
            },
          },
          fontFamily: {
            sans: [
              "Inter",
              "ui-sans-serif",
              "system-ui",
              "-apple-system",
              "system-ui",
              "Segoe UI",
              "Roboto",
              "Helvetica Neue",
              "Arial",
              "Noto Sans",
              "sans-serif",
              "Apple Color Emoji",
              "Segoe UI Emoji",
              "Segoe UI Symbol",
              "Noto Color Emoji",
            ],
            body: [
              "Inter",
              "ui-sans-serif",
              "system-ui",
              "-apple-system",
              "system-ui",
              "Segoe UI",
              "Roboto",
              "Helvetica Neue",
              "Arial",
              "Noto Sans",
              "sans-serif",
              "Apple Color Emoji",
              "Segoe UI Emoji",
              "Segoe UI Symbol",
              "Noto Color Emoji",
            ],
            mono: [
              "ui-monospace",
              "SFMono-Regular",
              "Menlo",
              "Monaco",
              "Consolas",
              "Liberation Mono",
              "Courier New",
              "monospace",
            ],
          },
          transitionProperty: {
            width: "width",
          },
          textDecoration: ["active"],
          minWidth: {
            kanban: "28rem",
          },
        },
      },
    }
  </script>
  {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

  <!-- Styles -->
</head>

<body class="bg-gray-200">
  @include('layouts.header')
  <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
    @include('layouts.sidebar')

    <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
      <main class="h-[calc(100vh-72px)] bg-slate-50 px-4 pt-6">@yield('main')</main>
      {{-- @include('layouts.footer') --}}
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('assets/helpers/helpers.js') }}"></script>
  @stack('script')
</body>

</html>
