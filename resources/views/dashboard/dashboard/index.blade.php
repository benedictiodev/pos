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
        <div class="flex justify-between p-3 h-24 rounded-lg bg-green-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"></path>
            </svg>
            <p class="text-base text-gray-500">Total Category Product</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $category ? $category : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-yellow-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"></path>
            </svg>
            <p class="text-base text-gray-500">Total Product</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $product ? $product : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-sky-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6"></path>
            </svg>
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
            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"></path>
            </svg>
            <p class="text-base text-gray-500">Today's Sales</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $order_date_now ? format_rupiah($order_date_now) : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-yellow-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"></path>
            </svg>
            <p class="text-base text-gray-500">Sales This Month</p>
          </div>
          <div class="flex justify-end items-center">
            <p class="text-2xl font-bold text-gray-500">{{ $order_month_now ? format_rupiah($order_month_now) : 0 }}</p>
          </div>
        </div>
        <div class="flex justify-between p-3 h-24 rounded-lg bg-sky-200 shadow-md border">
          <div class="flex flex-col justify-center items-center">
            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6"></path>
            </svg>
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