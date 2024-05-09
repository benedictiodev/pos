@extends('layouts.index')

@section('main')
  <div class="">
    <div class="mb-4">
      <nav class="mb-5 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
          <li class="inline-flex items-center">
            <a href="#"
              class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
              Dashboard
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 dark:text-gray-300 md:ml-2" aria-current="page">Change Password</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-4">Change Password</h1>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-gray-800 dark:text-green-400"
        role="alert">
        <span class="font-medium">{{ session('success') }}</span>
      </div>
    @endif
    @if (session('failed'))
      <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">{{ session('failed') }}</span>
      </div>
    @endif
    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
      <div class="mb-4">
        <form action="{{ route('dashboard.profile.post') }}" method="POST" id="form_edit_profile">
          @csrf
          <div class="space-y-6">
            <div>
              <label for="old_password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Old Password</label>
              <input type="password" name="old_password" id="old_password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Old Password" required>
            </div>
            <div>
              <label for="new_password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">New Password</label>
              <input type="password" name="new_password" id="new_password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="New Password" required>
            </div>
            <div>
              <label for="confirm_password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
              <input type="password" name="confirm_password" id="confirm_password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Confirm Password" required>
            </div>
            
            <button type="submit"
              class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    function edit_form() {
      const elements =  document.querySelectorAll('#form_edit_profile input, #form_edit_profile textarea');
      elements.forEach(element => {
        element.readOnly = false;
        element.classList.remove("bg-gray-200");
        element.classList.add("bg-gray-50");
      });
      document.querySelector('#frame_button_before_edit').hidden = true;
      document.querySelector('#frame_button_after_edit').hidden = false;
    }
    function cancel_form() {
      const name = "{{ Auth::user()->name }}";
      const email = "{{ Auth::user()->email }}";
      const phone_number = "{{ Auth::user()->phone_number }}";
      const address = "{{ Auth::user()->address }}";

      document.querySelector('#name').value = name;
      document.querySelector('#email').value = email;
      document.querySelector('#phone_number').value = phone_number;
      document.querySelector('#address').value = address;

      const elements =  document.querySelectorAll('#form_edit_profile input, #form_edit_profile textarea');
      elements.forEach(element => {
        element.readOnly = true;
        element.classList.add("bg-gray-200");
        element.classList.remove("bg-gray-50");
      });
      document.querySelector('#frame_button_before_edit').hidden = false;
      document.querySelector('#frame_button_after_edit').hidden = true;
    } 
  </script>
@endpush
