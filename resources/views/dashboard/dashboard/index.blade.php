@extends('layouts.index')

@section('main')
<div class="">
    <div class="mb-4">
      <nav class="mb-5 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
          <li class="inline-flex items-center">
            <a href="#"
              class="inline-flex items-center text-gray-700 hover:text-primary-600">
              Dashboard
            </a>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Dashboard</h1>
    </div>
  </div>

  @if (Auth::user()->id == 1)
    <div>
      <div class="grid grid-col-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="flex justify-between p-3 h-24 rounded-lg bg-orange-300 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-database class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Total Category Product</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $category ? $category : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-gray-300 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-database class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Total Product</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $product ? $product : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-green-300 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Master Fund</p>
          </div>
          <div class="flex justify-center items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $fund_master ? $fund_master : 0 }}</p>
          </div>
        </div>
      </div>
      <div class="grid grid-col-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="flex justify-between p-3 h-24 rounded-lg bg-green-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-cart-shopping class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Today's Sales</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $order_date_now ? format_rupiah($order_date_now) : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-yellow-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Sales This Month</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $order_month_now ? format_rupiah($order_month_now) : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-sky-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <x-fas-cart-shopping class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
            <p class="text-base text-gray-500">Products Sold This Month</p>
          </div>
          <div class="flex justify-center items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $order_item ? $order_item : 0 }}</p>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <div class="overflow-x-auto flex justify-center p-3 rounded-lg bg-gray-50 shadow-md border">
          <canvas id="chart_penjualan"></canvas>
        </div>
        <div class="overflow-x-auto flex justify-center p-3 rounded-lg bg-gray-50 shadow-md border">
          <canvas id="chart_modal_keuntungan"></canvas>
        </div>
      </div>
    </div>
  @endif
@endsection

@push('script')
  <script src="{{ asset('assets/plugins/chart_js/chart.js')}}"></script>
  <script>
    const ctx_penjualan = document.getElementById('chart_penjualan');
    new Chart(ctx_penjualan, {
      type: 'bar',
      data: {
        labels: {!! json_encode($result_chart_order_label) !!},
        datasets: [{
          label: 'Sales',
          data: {!! json_encode($result_chart_order_value) !!},
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
            text: `Sales Graph For The Period {!! Carbon\Carbon::now()->format('Y-m') !!}`,
          }
        }
      }
    });
    const ctx_modal_keuntungan = document.getElementById('chart_modal_keuntungan');
    new Chart(ctx_modal_keuntungan, {
      type: 'bar',
      data: {
        labels: {!! json_encode($result_closing_cycle_label) !!},
        datasets: [{
          label: 'Equite',
          data: {!! json_encode($result_closing_cycle_value_equite) !!},
          borderWidth: 1
        }, {
          label: 'Profit',
          data: {!! json_encode($result_closing_cycle_value_profit) !!},
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 100000
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: `Capital And Profit Graph {!! Carbon\Carbon::now()->format('Y') !!}`,
          }
        }
      }
    });
  </script>
@endpush