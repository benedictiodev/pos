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
                Role</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Create Role</h1>
      <a href="{{ route('dashboard.management-user.role.index') }}"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Back
      </a>
    </div>

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2">
      <div class="mb-4">
        <form action="{{ route('dashboard.management-user.role.store') }}" method="POST">
          @csrf
          <div class="space-y-6">
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
              <label for="checkbos-all" class="mb-2 block text-sm font-medium text-gray-900">Permission</label>
              <div>
                <label><input type="checkbox" id="checkbox-all" class="name mb-2">
                  Check All</label>
              </div>
              <div id="checkboxes">
                @foreach ($permission as $value)
                  <label><input type="checkbox" name="permission[]" value="{{ $value->name }}" class="name">
                    {{ $value->name }}</label>
                @endforeach
              </div>
            </div>
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
    $(document).ready(function() {
      $('#checkbox-all').click(function() {
        const checked = $(this).prop('checked');
        $('#checkboxes').find('input:checkbox').prop('checked', checked);
      });
    });
  </script>
@endpush
