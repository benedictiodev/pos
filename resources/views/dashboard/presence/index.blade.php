@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-1 w-full">
      <div class="mb-4">
        <nav class="mb-5 flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
            <li class="inline-flex items-center">
              <a href="#" class="inline-flex items-center text-gray-700 hover:text-primary-600">
                Beranda
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Presensi</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Presensi Pengguna</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Presensi Pengguna</h1>
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
          <form class="sm:pr-3" action="{{ route('dashboard.presence.index') }}" method="GET" id="form-search">
            <label for="presence-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="date" name="periode" id="presence-search" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Cari data presensi"
                value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m-d') }}"
                onchange="change_search()"
                max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
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
        {{-- <a id="createProductButton" href="{{ route('dashboard.master-data.category-product.create') }}"
          class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
          Add new product
        </a> --}}
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
                      Nama
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500">
                      Email
                    </th>
                    <th scope="col" class="p-4 text-center text-base font-bold uppercase text-gray-500">
                      Presensi
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
                        <p class="text-sm font-normal text-gray-900">{{ $item->name }}</p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->email }}</p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        {{-- <a href="{{ route('dashboard.master-data.category-product.edit', ['id' => $item->id]) }}"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
                          <x-fas-edit class="mr-2 h-4 w-4" />
                          Update
                        </a> --}}

                        @if (isset($item->presence[0]))
                          <p class="text-sm font-normal text-gray-900">{{ $item->presence[0]->created_at }}</p>
                        @else
                          @if (isset($_GET['periode']))
                            @if (Carbon\Carbon::create($_GET['periode'])->diffInDays(Carbon\Carbon::now()) > 1)
                              <p class="text-sm font-normal text-gray-900">-</p>
                            @endif
                          @endif
                        @endif
                        <button type="button" id="presenceButton" data-drawer-target="drawer-presence-default"
                          data-drawer-show="drawer-presence-default" aria-controls="drawer-presence-default"
                          data-drawer-placement="right"
                          class="items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
                          @if (isset($item->presence[0])) hidden 
                          @else 
                            @if (isset($_GET['periode']))
                              @if (Carbon\Carbon::create($_GET['periode'])->diffInDays(Carbon\Carbon::now()) > 1) hidden @endif
                            @endif 
                            inline-flex
                          @endif
                          "
                          data-id="{{ $item->id }}">
                          <x-fas-user-pen class="mr-2 h-4 w-4" />
                          Presensi
                        </button>

                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td class="text-center text-base font-light p-4" colspan="3">Data Kosong</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- <div
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 sm:flex sm:justify-between">
        {{ $data->withQueryString()->links('vendor.pagination.tailwind') }}
      </div> --}}
    </div>
  </div>

  <!-- Presence Drawer -->
  <div id="drawer-presence-default"
    class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
    tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
    <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Presensi
    </h5>
    <button type="button" data-drawer-dismiss="drawer-presence-default" aria-controls="drawer-presence-default"
      class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
      <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
      <span class="sr-only">Tutup</span>
    </button>
    <form id="form-presence" method="POST" action="{{ route('dashboard.presence.store') }}">
      @csrf
      <input type="text" id="presence-id" name="user_id" hidden>
      <input type="text" value="{{ Auth::user()->id }}" name="company_id" hidden>
      <x-fas-circle-exclamation class="mb-4 mt-8 h-10 w-10 text-red-600" />
      <h3 class="mb-6 text-lg text-gray-500">Apakah anda yakin untuk melakukan presensi pengguna ini?</h3>
      <button type="button" data-type="button-presence"
        class="mr-2 inline-flex items-center rounded-lg bg-primary-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
        Ya, Saya Yakin
      </button>
      <button type="button"
        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
        data-drawer-hide="drawer-presence-default">
        Tidak, Batalkan
      </button>
    </form>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    function change_search() {
      let value = document.querySelector("#presence-search").value;
      document.querySelector("#form-search").submit();
    }

    window.onload = () => {
      document.addEventListener('click', async (event) => {
        // PRESENCE
        console.log(event.target);
        if (event.target.getAttribute('data-drawer-target') == "drawer-presence-default") {
          const id = event.target.getAttribute("data-id");
          console.log(id);
          document.querySelector("#presence-id").value = id;
        }
        if (event.target.getAttribute('data-type') == "button-presence") {
          document.querySelector("#form-presence").submit();
        }
      })
    }
  </script>
@endpush
