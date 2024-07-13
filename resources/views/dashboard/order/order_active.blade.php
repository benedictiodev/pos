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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                  Order
                </span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Order Aktif</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Order Aktif</h1>
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
          <form class="sm:pr-3" action="{{ route('dashboard.order.order_active') }}" method="GET">
            <label for="search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="text" name="search" id="search"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Cari Data Order" @if (isset($_GET['search'])) value="{{ $_GET['search'] }}" @endif>
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
        <a id="createProductButton"
          class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300"
          href="{{ route('dashboard.order.order_active.add_new_order') }}">
          Tambahkan Order Baru
        </a>
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
              <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th scope="col" class="p-4">
                      <div class="flex items-center">
                        <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox"
                          class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                      </div>
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-gray-500">
                      Order ID
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-gray-500">
                      Waktu
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-gray-500">
                      Nama Pelanggan
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-gray-500">
                      Keterangan
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-gray-500">
                      Tipe Order
                    </th>
                    <th scope="col" class="p-4 text-center text-base font-bold uppercase text-gray-500">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @forelse ($data as $item)
                    <tr class="hover:bg-gray-100">
                      <td class="w-4 p-4">
                        <div class="flex items-center">
                          <input id="checkbox-" aria-describedby="checkbox-1" type="checkbox"
                            class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300">
                          <label for="checkbox-" class="sr-only">checkbox</label>
                        </div>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->id_order }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->datetime }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->customer_name }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->remarks }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->order_type == 'dine_in' ? 'Dine In' : 'Take Away' }}
                        </p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        <a href="{{ route('dashboard.order.edit_order', ['id' => $item->id]) }}" id="updateProductButton"
                          data-drawer-target="drawer-update-product-default"
                          data-drawer-show="drawer-update-product-default" aria-controls="drawer-update-product-default"
                          data-drawer-placement="right"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
                          <x-fas-edit class="mr-2 h-4 w-4" />
                          Perbarui
                        </a>
                        <button type="button" id="deleteProductButton" data-drawer-target="drawer-delete-order"
                          data-drawer-show="drawer-delete-order" aria-controls="drawer-delete-order"
                          data-drawer-placement="right"
                          class="inline-flex items-center rounded-lg bg-red-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300"
                          data-id="{{ $item->id }}">
                          <x-fas-trash-alt class="mr-2 h-4 w-4" />
                          Hapus
                        </button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td class="text-center text-base font-light p-4" colspan="7">Data Kosong</td>
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
        {{ $data->links('vendor.pagination.tailwind') }}
      </div>
    </div>
  </div>

  <div id="drawer-delete-order"
    class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
    tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
    <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Hapus Data
    </h5>
    <button type="button" data-drawer-dismiss="drawer-delete-order" aria-controls="drawer-delete-order"
      class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
      <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
      <span class="sr-only">Tutup</span>
    </button>
    <form id="form-delete">
      @csrf
      @method('DELETE')
      <input type="text" id="delete-id" value="" hidden>
      <x-fas-circle-exclamation class="mb-4 mt-8 h-10 w-10 text-red-600" />
      <h3 class="mb-6 text-lg text-gray-500">Apakah anda yakin untuk menghapus data order ini?</h3>
      <button type="button" data-type="button-delete"
        class="mr-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300">
        Ya, Saya Yakin
      </button>
      <button type="button"
        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
        data-drawer-hide="drawer-delete-order">
        Tidak, Batalkan
      </button>
    </form>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    window.onload = () => {
      document.addEventListener('click', async (event) => {
        // DELETE DATA
        if (event.target.getAttribute('data-drawer-target') == "drawer-delete-order") {
          const id = event.target.getAttribute("data-id");
          document.querySelector("#delete-id").value = id;
          console.log(id);
        }
        if (event.target.getAttribute('data-type') == "button-delete") {
          const id = document.querySelector("#delete-id").value;
          document.querySelector("#form-delete").method = "POST";
          document.querySelector("#form-delete").action =
            `/dashboard/order/${id}`;
          document.querySelector("#form-delete").submit();
        }
      })
    }
  </script>
@endpush
