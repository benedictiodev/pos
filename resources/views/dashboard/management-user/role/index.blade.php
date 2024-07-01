@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-1 w-full">
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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Roles</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Role</h1>
      </div>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800" role="alert">
        <span class="font-medium">{{ session('success') }}</span>
      </div>
    @endif
    @if (session('failed'))
      <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
        <span class="font-medium">{{ session('failed') }}</span>
      </div>
    @endif

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4">
      <div class="block items-center justify-between sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="{{ route('dashboard.management-user.role.index') }}" method="GET">
            <label for="roke-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="text" name="search" id="roke-search"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Search for role" @if (isset($_GET['search'])) value="{{ $_GET['search'] }}" @endif>
            </div>
          </form>
          {{-- <div class="flex w-full items-center sm:justify-end">
            <div class="flex space-x-1 pl-2">
              <a href="#"
                class="inline-flex cursor-pointer justify-center rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                <x-fas-trash-alt class="h-6 w-6" />
              </a>
            </div>
          </div> --}}
        </div>
        <a id="createRoleButton" href="{{ route('dashboard.management-user.role.create') }}"
          class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
          Add new Role
        </a>
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
              <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    {{-- <th scope="col" class="p-4">
                      <div class="flex items-center">
                        <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox"
                          class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                      </div>
                    </th> --}}
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500">
                      Name
                    </th>
                    <th scope="col" class="p-4 text-center text-base font-bold uppercase text-gray-500">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @forelse ($data as $item)
                    <tr class="hover:bg-gray-100">
                      {{-- <td class="w-4 p-4">
                        <div class="flex items-center">
                          <input id="checkbox-" aria-describedby="checkbox-1" type="checkbox"
                            class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300">
                          <label for="checkbox-" class="sr-only">checkbox</label>
                        </div>
                      </td> --}}
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900 capitalize">{{ $item->name }}</p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        <a href="{{ route('dashboard.management-user.role.edit', $item) }}"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
                          <x-fas-edit class="mr-2 h-4 w-4" />
                          Update
                        </a>

                        <button type="button" id="presenceButton" data-drawer-target="drawer-user-default"
                          data-drawer-show="drawer-user-default" aria-controls="drawer-user-default"
                          data-drawer-placement="right"
                          class="inline-flex items-center rounded-lg bg-red-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-primary-300
                          "
                          data-id="{{ $item->id }}">
                          <x-fas-trash class="mr-2 h-4 w-4" />
                          Delete
                        </button>

                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td class="text-center text-base font-light p-4" colspan="3">Empty Data</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 sm:flex sm:justify-between">
        {{ $data->withQueryString()->links('vendor.pagination.tailwind') }}
      </div>
    </div>
  </div>

  <!-- User Drawer -->
  <div id="drawer-user-default"
    class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
    tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
    <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">User
    </h5>
    <button type="button" data-drawer-dismiss="drawer-user-default" aria-controls="drawer-user-default"
      class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
      <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
      <span class="sr-only">Close menu</span>
    </button>
    <form id="form-user" method="POST">
      @csrf
      @method('DELETE')
      <input type="text" id="user-id" name="user_id" hidden>
      <input type="text" value="{{ Auth::user()->id }}" name="company_id" hidden>
      <x-fas-circle-exclamation class="mb-4 mt-8 h-10 w-10 text-red-600" />
      <h3 class="mb-6 text-lg text-gray-500">Are you sure you want to delete this user?</h3>
      <button type="button" data-type="button-delete"
        class="mr-2 inline-flex items-center rounded-lg bg-primary-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
        Yes, I'm sure
      </button>
      <button type="button"
        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
        data-drawer-hide="drawer-user-default">
        No, cancel
      </button>
    </form>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    window.onload = () => {
      document.addEventListener('click', async (event) => {
        // PRESENCE
        if (event.target.getAttribute('data-drawer-target') == "drawer-user-default") {
          const id = event.target.getAttribute("data-id");
          document.querySelector("#user-id").value = id;
          console.log(id);
        }
        if (event.target.getAttribute('data-type') == "button-delete") {
          const id = document.querySelector("#user-id").value;
          document.querySelector("#form-user").action = '/dashboard/management-user/users/' + id;
          console.log(id, document.querySelector("#form-user"));
          document.querySelector("#form-user").submit();
        }
      })
    }
  </script>
@endpush
