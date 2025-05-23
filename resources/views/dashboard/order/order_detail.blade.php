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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Order</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Detil</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Order Detil</h1>
      </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6 mb-4">
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow rounded-t-lg">
              <div class="mb-4 space-y-12">
                <table class="table-fixed divide-y divide-gray-200 w-full">
                  <thead class="divide-y divide-gray-200">
                    <tr class="bg-sky-300">
                      <td colspan="4" class="p-4 text-center text-lg font-bold uppercase text-white"> ID Order
                        {{ $data->id_order }}</td>
                    </tr>
                    <tr>
                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Kasir</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->cashier_name }}</td>

                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Waktu</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->datetime }}</td>
                    </tr>
                    <tr>
                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Pelanggan</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->customer_name }}</td>

                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Tipe Order</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->order_type == 'dine_in' ? 'DINE IN' : 'TAKE AWAY' }}</td>
                    </tr>
                    <tr>
                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Keterangan</th>
                      <td class="p-4 text-sm font-normal text-gray-900" colspan="3">
                        {{ $data->remarks }}</td>
                    </tr>
                    <tr></tr>
                  </thead>
                </table>
              </div>
              <div class="overflow-hidden shadow rounded-lg">
                <table class="min-w-full table-fixed divide-y divide-gray-200">
                  <thead class="bg-sky-300">
                    <tr>
                      <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                        Produk
                      </th>
                      <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                        Keterangan
                      </th>
                      <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                        Qty
                      </th>
                      <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                        Harga
                      </th>
                      <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                        Total Harga
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($data->items as $item)
                      <tr>
                        <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                          <p class="text-sm font-normal text-gray-900">
                            {{ $item->product->name }}
                          </p>
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                          <p class="text-sm font-normal text-gray-900">
                            {{ $item->remarks }}
                          </p>
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                          <p class="text-sm font-normal text-gray-900">
                            {{ $item->quantity }}
                          </p>
                        </td>
                        <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                          <p class="text-sm font-normal text-gray-900">
                            {{ format_rupiah($item->product->price) }}
                          </p>
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                          <p class="text-sm font-normal text-gray-900 text-end">
                            {{ format_rupiah($item->amount) }}
                          </p>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td class="text-center text-base font-light p-4" colspan="7">Data Kosong</td>
                      </tr>
                    @endforelse
                  </tbody>
                  <tfoot class="bg-sky-300">
                    <tr>
                      <td class="text-start p-2 text-base font-bold uppercase text-white">
                        {{ count($data->items) }} item</td>
                      <td colspan="3" class="text-end p-2 text-base font-bold uppercase text-white">Total
                      </td>
                      <td class="text-end p-2 text-base font-bold uppercase text-white">
                        {{ format_rupiah($data->total_payment) }}</td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-end p-2 text-base font-bold uppercase text-white">
                        {{ $data->payment_method }}</td>
                      <td class="text-end p-2 text-base font-bold uppercase text-white">
                        {{ format_rupiah($data->payment) }}</td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-end p-2 text-base font-bold uppercase text-white">
                        Kembalian</td>
                      <td class="text-end p-2 text-base font-bold uppercase text-white">
                        {{ format_rupiah($data->change) }}</td>
                    </tr>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@push('script')
  <script type="text/javascript"></script>
@endpush
