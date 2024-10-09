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
              <label for="checkbos-all" class="mb-2 block text-base font-semibold text-gray-900">Permission</label>
              <div class="space-y-3">

                <div id="accordion-role-menu" data-accordion="open">
                  @foreach ($permission as $key_menu => $value_menu)
                    <h2 id="accordion-role-menu-heading-{{ $key_menu }}">
                      <button type="button" class="flex items-center justify-between w-full px-3 py-2.5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 hover:bg-gray-100 gap-3" data-accordion-target="#accordion-role-menu-body-{{ $key_menu }}" aria-expanded="false" aria-controls="accordion-role-menu-body-{{ $key_menu }}">
                        <span class="capitalize">{{ $value_menu->menu }}</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                        </svg>
                      </button>
                    </h2>
                    <div id="accordion-role-menu-body-{{ $key_menu }}" class="hidden" aria-labelledby="accordion-role-menu-heading-{{ $key_menu }}">
                      <div class="p-3 pb-0 border border-t-0 border-gray-200 dark:border-gray-700">
                        @foreach ($value_menu->sub_menu as $value_sub_menu)
                          <div class="mb-2">
                            <h3 class="mb-1 font-semibold text-gray-900 dark:text-white text-sm capitalize">{{ $value_sub_menu->sub_menu }}</h3>
                            <div class="grid grid-cols-4 max-w-full mt-1">
                              @foreach ($value_sub_menu->permission as $item)
                                <div class="mb-1">
                                  <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="permission[]" value="{{ $value_menu->menu . '-' . $value_sub_menu->sub_menu . '-' . $item }}" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-xs font-medium text-gray-900 dark:text-gray-300 capitalize">{{ $item }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div>
              <button type="submit"
                class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                Add
              </button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection
