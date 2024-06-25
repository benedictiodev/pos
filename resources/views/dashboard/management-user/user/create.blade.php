@extends('layouts.index')

@section('main')
  <div class="">
    <div class="mb-4">
      <nav class="mb-5 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
          <li class="inline-flex items-center">
            <a href="#" class="inline-flex items-center text-gray-700 hover:text-primary-600">
              Dashboard
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Management User</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                User</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Create User</h1>
      <a href="{{ route('dashboard.management-user.user.index') }}"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Back
      </a>
    </div>

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2">
      <div class="mb-4">
        <form action="{{ route('dashboard.management-user.user.store') }}" method="POST">
          @csrf
          <div class="space-y-6">
            <div>
              <label for="username" class="mb-2 block text-sm font-medium text-gray-900">Username*</label>
              <input type="text" name="username" id="username"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Username" value="{{ old('username') }}" required>
              @error('username')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Name*</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Name" value="{{ old('name') }}" required>
              @error('name')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="email" class="mb-2 block text-sm font-medium text-gray-900">Email*</label>
              <input type="email" name="email" id="email"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Email" value="{{ old('email') }}" required>
              @error('email')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900">Phone Number</label>
              <input type="tel" name="phone_number" id="phone_number"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Phone Number" value="{{ old('phone_number') }}">
              @error('phone_number')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="address" class="mb-2 block text-sm font-medium text-gray-900">Address</label>
              <textarea type="tel" name="address" id="address"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Address" value="{{ old('address') }}"></textarea>
              @error('address')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="password" class="mb-2 block text-sm font-medium text-gray-900">Password*</label>
              <input type="password" name="password" id="password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Password" value="{{ old('password') }}" required>
              @error('password')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-900">Password
                Confirmation*</label>
              <input type="password" name="password_confirmation" id="password_confirmation"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Password Confirmation" value="{{ old('password_confirmation') }}" required>
              @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            {{-- <div>
              <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
              <select id="role" name="role"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required>
                <option disabled value="" selected>~ Select Role ~</option>
                @foreach ($roles as $item)
                  <option value="{{ $item->type }}" @if (old('type') == $item->type) selected @endif>
                    {{ $item->type }}</option>
                @endforeach
              </select>
              @error('type')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div> --}}

            <button type="submit"
              class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
              Add
            </button>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script>
    const extendRemark = () => {
      if ($('#remarks_from_master').val() == 'No Categories') {
        $('#optional_remark').addClass('hidden');
        $('#remark').val("");
        $('#is_same').prop('checked', false);
      } else {
        $('#optional_remark').removeClass('hidden');
      }
    }

    const optionalRemark = () => {
      if ($('#is_same').is(':checked')) {
        $('#remark').val($('#remarks_from_master').val());
      } else {
        $('#remark').val("");
      }
    }
  </script>
@endpush
