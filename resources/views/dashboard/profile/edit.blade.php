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
              <span class="ml-1 text-gray-400 dark:text-gray-300 md:ml-2" aria-current="page">Profile</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-4">Profile</h1>
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
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Fullname</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Fullname" value="{{ Auth::user()->name }}" required readonly>
            </div>
            <div>
              <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">E-Mail</label>
              <input type="email" name="email" id="email"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="E-Mail" value="{{ Auth::user()->email }}" required readonly>
            </div>
            <div>
              <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
              <input type="text" name="phone_number" id="phone_number"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Phone Number" value="{{ Auth::user()->phone_number }}" readonly>
            </div>
            <div>
              <label for="address" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Address</label>
              <textarea id="address" rows="4" name="address"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Address" readonly>{{ Auth::user()->address }}</textarea>
            </div>
            <div id="frame_button_before_edit">
              <button type="button" onclick="edit_form()"
                class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Edit
              </button>
            </div>
            <div id="frame_button_after_edit" hidden>
              <button type="submit"
                class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Submit
              </button>
              <button type="button" onclick="cancel_form()"
                class="w-fit justify-center rounded-lg bg-yellow-400 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                Cancel
              </button>
            </div>
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
