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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Pelaporan</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Pelaporan</h1>
      </div>
    </div>

    <div>
      <div class="block items-center justify-between sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="#" method="GET" id="form-search">
            <label for="order-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-order sm:w-64 xl:w-96">
              <input type="month" name="periode" id="search"
                class="block w-full rounded-lg border border-gray-300 order-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Search"
                value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}"
                onchange="change_search()"
                max="{{ Carbon\Carbon::now()->format('Y-m') }}">
            </div>
          </form>
        </div>
      </div>
      <div class="grid grid-col-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="flex justify-between p-3 h-24 rounded-lg bg-green-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-cart-shopping class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Total Order</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $total_order->total_order ? $total_order->total_order : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-yellow-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Penjualan Bulan Ini</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $total_order->total_payment ? format_rupiah($total_order->total_payment) : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-sky-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-cart-shopping class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Produk Terjual Bulan Ini</p>
          </div>
          <div class="flex justify-center items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $total_item_order->quantity ? $total_item_order->quantity : 0 }}</p>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <div class="overflow-x-auto flex justify-center p-3 rounded-lg bg-gray-50 shadow-md border">
          <canvas id="chart_penjualan"></canvas>
        </div>
        <div class="overflow-x-auto flex justify-center p-3 rounded-lg bg-gray-50 shadow-md border">
          <canvas id="chart_modal_order"></canvas>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-4 mb-4">
        <div class="overflow-x-auto flex justify-center p-3 rounded-lg bg-gray-50 shadow-md border">
          <div class="overflow-x-auto">
            <div class="inline-block align-middle">
              <div class="overflow-hidden shadow">
                <table class="w-full table-fixed divide-y divide-gray-200">
                  <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($result_order as $item)
                      <tr class="bg-gray-100">
                        <th colspan="3" class="p-4 text-base font-bold uppercase text-gray-500">{{ $item->category_name }}</th>
                      </tr>
                      <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-base font-bold uppercase text-gray-500">Nama Produk</th>
                        <th class="px-4 py-2 text-base font-bold uppercase text-gray-500">Produk Terjual</th>
                        <th class="px-4 py-2 text-base font-bold uppercase text-gray-500">Persentase Penjualan</th>
                      </tr>
                      @foreach ($item->product as $item_product)
                        <tr class="divide-y divide-gray-200 bg-white hover:bg-gray-100">
                          <td class="whitespace-nowrap text-center p-4 text-sm font-normal text-gray-500">{{ $item_product->product_name }}</td>
                          <td class="whitespace-nowrap text-center p-4 text-sm font-normal text-gray-500">{{ $item_product->sold }}</td>
                          <td class="whitespace-nowrap text-center p-4 text-sm font-normal text-gray-500">
                            {{ $item->category_total_quantity == 0 ? 0 : ($item_product->sold / $item->category_total_quantity * 100 ) }} %
                          </td>
                        </tr>
                      @endforeach
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
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script src="{{ asset('assets/plugins/chart_js/chart.js')}}"></script>
  <script>
    function change_search() {
      let value = document.querySelector("#search").value;
      document.querySelector("#form-search").action = `/dashboard/order/report`;
      document.querySelector("#form-search").submit();
    }

    const ctx_penjualan = document.getElementById('chart_penjualan');
    new Chart(ctx_penjualan, {
      type: 'bar',
      data: {
        labels: {!! json_encode($result_chart_order_label) !!},
        datasets: [{
          label: 'Total Pembayaran',
          data: {!! json_encode($result_chart_order_value) !!},
          borderWidth: 1
        }, {
          label: 'Rata Rata Pembayaran',
          data: {!! json_encode($result_chart_order_value_avg) !!},
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 50000
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: `Grafik penjualan periode {!! Carbon\Carbon::now()->format('Y-m') !!}`,
          }
        }
      }
    });

    const ctx_modal_order = document.getElementById('chart_modal_order');
    new Chart(ctx_modal_order, {
      type: 'bar',
      data: {
        labels: {!! json_encode($result_chart_order_label) !!},
        datasets: [{
          label: 'Order',
          data: {!! json_encode($result_chart_order_value_count) !!},
          borderWidth: 1
        }, {
          label: 'Item terjual',
          data: {!! json_encode($result_chart_order_item_value) !!},
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 10
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: `Grafik order periode {!! Carbon\Carbon::now()->format('Y-m') !!}`,
          }
        }
      }
    });
  </script>
@endpush