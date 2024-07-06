@extends('layouts.index')

@section('main')
  <div class="">
    <div class="mb-4">
      <nav class="mb-5 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-semibold md:space-x-2">
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
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Edit Role</h1>
      <a href="{{ route('dashboard.management-user.role.index') }}"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Back
      </a>
    </div>

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2">
      <div class="mb-4">
        <form action="{{ route('dashboard.management-user.role.update', $role) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="space-y-3">
            <div>
              <label for="name" class="mb-2 block text-base font-semibold text-gray-900">Name*</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Name" value="{{ old('name', $role->name) }}" required readonly disabled>
              @error('name')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="checkbos-all" class="mb-2 block text-base font-semibold text-gray-900">Permission</label>
              <div class="space-y-3">
                <div>
                  <label for="master-data" class="mb-1 block text-base font-semibold text-gray-900">Master Data</label>
                  <div class="flex flex-row gap-3 items-center">
                    @foreach ($permission as $value)
                      @if (str_contains($value->name, 'master data'))
                        <label class="capitalize"><input type="checkbox" name="permission[]" value="{{ $value->name }}"
                            @if ($role->hasPermissionTo($value->name)) checked @endif>
                          {{ $value->name }}</label>
                      @endif
                    @endforeach
                  </div>
                </div>

                <div>
                  <label for="finance" class="mb-1 block text-base font-semibold text-gray-900">Finance</label>
                  <div class="flex flex-row gap-3 items-center">
                    @foreach ($permission as $value)
                      @if (str_contains($value->name, 'finance'))
                        <label class="capitalize"><input type="checkbox" name="permission[]" value="{{ $value->name }}"
                            @if ($role->hasPermissionTo($value->name)) checked @endif>
                          {{ $value->name }}</label>
                      @endif
                    @endforeach
                  </div>
                </div>

                <div>
                  <label for="company" class="mb-1 block text-base font-semibold text-gray-900">Company</label>
                  <div class="flex flex-row gap-3 items-center">
                    @foreach ($permission as $value)
                      @if (str_contains($value->name, 'company'))
                        <label class="capitalize"><input type="checkbox" name="permission[]" value="{{ $value->name }}"
                            @if ($role->hasPermissionTo($value->name)) checked @endif>
                          {{ $value->name }}</label>
                      @endif
                    @endforeach
                  </div>
                </div>

                <div>
                  <label for="order-history" class="mb-1 block text-base font-semibold text-gray-900">Order
                    History</label>
                  <div class="flex flex-row gap-3 items-center">
                    @foreach ($permission as $value)
                      @if (str_contains($value->name, 'order history'))
                        <label class="capitalize"><input type="checkbox" name="permission[]" value="{{ $value->name }}"
                            @if ($role->hasPermissionTo($value->name)) checked @endif>
                          {{ $value->name }}</label>
                      @endif
                    @endforeach
                  </div>
                </div>

                <div>
                  <label for="order-active" class="mb-1 block text-base font-semibold text-gray-900">Order
                    Active</label>
                  <div class="flex flex-row gap-3 items-center">
                    @foreach ($permission as $value)
                      @if (str_contains($value->name, 'order active'))
                        <label class="capitalize"><input type="checkbox" name="permission[]" value="{{ $value->name }}"
                            @if ($role->hasPermissionTo($value->name)) checked @endif>
                          {{ $value->name }}</label>
                      @endif
                    @endforeach
                  </div>
                </div>

                <div>
                  <label for="presence-user" class="mb-1 block text-base font-semibold text-gray-900">Presence
                    User</label>
                  <div class="flex flex-row gap-3 items-center">
                    @foreach ($permission as $value)
                      @if (str_contains($value->name, 'presence user'))
                        <label class="capitalize"><input type="checkbox" name="permission[]" value="{{ $value->name }}"
                            @if ($role->hasPermissionTo($value->name)) checked @endif>
                          {{ $value->name }}</label>
                      @endif
                    @endforeach
                  </div>
                </div>

                <div>
                  <label for="management-user" class="mb-1 block text-base font-semibold text-gray-900">Management
                    User</label>
                  <div class="flex flex-row gap-3 items-center">
                    @foreach ($permission as $value)
                      @if (str_contains($value->name, 'management user'))
                        <label class="capitalize"><input type="checkbox" name="permission[]" value="{{ $value->name }}"
                            @if ($role->hasPermissionTo($value->name)) checked @endif>
                          {{ $value->name }}</label>
                      @endif
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <div>
              <button type="submit"
                class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-semibold text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                Edit
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
