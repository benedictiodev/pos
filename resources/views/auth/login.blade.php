@extends('layouts.auth')

@section('main')
  <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
    <!-- Card -->
    <div class="w-full max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-2xl shadow-2xl dark:bg-gray-800">
      <div
        class="flex items-center justify-center mb-8 text-2xl font-semibold lg:mb-10 dark:text-white">
        <img src="{{ asset("images/logo_benedictiodev.png")}}" class="mr-4 h-24" alt="FlowBite Logo">
        <div>
          <div>Benedictio Dev</div>
          <div class="text-sm">Point Of Sales Application</div>
        </div>
      </div>
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
        Sign in to platform
      </h2>
      <form class="mt-8 space-y-6" action="{{ route('post_login') }}" method="POST">
        @csrf
        <div>
          <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
          <input type="email" name="email" id="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            placeholder="name@company.com" required="">
        </div>
        <div>
          <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
          <input type="password" name="password" id="password" placeholder="••••••••"
            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required="">
        </div>
        <div class="flex items-end">
          {{-- <div class="flex items-center h-5">
            <input id="remember" aria-describedby="remember" name="remember" type="checkbox"
              class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
              required="">
          </div>
          <div class="ml-3 text-sm">
            <label for="remember" class="font-medium text-gray-900 dark:text-white">Remember me</label>
          </div> --}}
          <a href="#" class="ml-auto text-sm text-primary-700 hover:underline dark:text-primary-500">Lost
            Password?</a>
        </div>
        <button type="submit"
          class="w-full px-5 py-3 text-base font-medium text-center text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Login
          to your account</button>
      </form>
    </div>
  </div>
@endsection
