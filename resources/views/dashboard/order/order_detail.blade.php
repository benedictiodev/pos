@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-1 w-full">
      <div class="mb-4">
        <nav class="mb-5 flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
            <li class="inline-flex items-center">
              <a href="#"
                class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                Dashboard
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 dark:text-gray-300 md:ml-2" aria-current="page">Order</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2" aria-current="page">Detail</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Order Detail</h1>
      </div>
    </div>

    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 mb-4">
      <div class="mb-4 space-y-5">
        <table class="table-fixed divide-y divide-gray-200 dark:divide-gray-600">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">ID Order</th>
              <td class="p-4 text-sm font-normal text-gray-900">:</td>
              <td class="p-4 text-sm font-normal text-gray-900">
                {{ $data->id_order }}</td>
            </tr>
            <tr>
              <th class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">Date</th>
              <td class="p-4 text-sm font-normal text-gray-900">:</td>
              <td class="p-4 text-sm font-normal text-gray-900">
                {{ $data->datetime }}</td>
            </tr>
            <tr>
              <th class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">Cashier</th>
              <td class="p-4 text-sm font-normal text-gray-900">:</td>
              <td class="p-4 text-sm font-normal text-gray-900">
                {{ $data->cashier_name }}</td>
            </tr>
            <tr>
              <th class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">Customer</th>
              <td class="p-4 text-sm font-normal text-gray-900">:</td>
              <td class="p-4 text-sm font-normal text-gray-900">
                {{ $data->customer_name }}</td>
            </tr>
            <tr>
              <th class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">Order Type</th>
              <td class="p-4 text-sm font-normal text-gray-900">:</td>
              <td class="p-4 text-sm font-normal text-gray-900">
                {{ $data->order_type == 'dine_in' ? 'DINE IN' : 'TAKE AWAY' }}</td>
            </tr>
            <tr>
              <th class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">Remark</th>
              <td class="p-4 text-sm font-normal text-gray-900">:</td>
              <td class="p-4 text-sm font-normal text-gray-900">
                {{ $data->remarks }}</td>
            </tr>
          </thead>
        </table>

        <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-600">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                Product
              </th>
              <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                Quantity
              </th>
              <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                Price
              </th>
              <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                Amount
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            @forelse ($data->items as $item)
              <tr>
                <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                  <p class="text-sm font-normal text-gray-900 dark:text-white">
                    {{ $item->product->name }}
                  </p>
                </td>
                <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                  <p class="text-sm font-normal text-gray-900 dark:text-white">
                    {{ $item->quantity }}
                  </p>
                </td>
                <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                  <p class="text-sm font-normal text-gray-900 dark:text-white">
                    {{ format_rupiah($item->product->price) }}
                  </p>
                </td>
                <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                  <p class="text-sm font-normal text-gray-900 dark:text-white text-end">
                    {{ format_rupiah($item->amount) }}
                  </p>
                </td>
              </tr>
            @empty
              <tr>
                <td class="text-center" colspan="7">Empty</td>
              </tr>
            @endforelse
          </tbody>
          <tfoot class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <td class="text-start p-4 text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                {{ count($data->items) }} items</td>
              <td colspan="2" class="text-end p-4 text-base font-bold uppercase text-gray-500 dark:text-gray-400">Total
              </td>
              <td class="text-end p-4 text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                {{ format_rupiah($data->total_payment) }}</td>
            </tr>
            <tr>
              <td colspan="3" class="text-end p-4 text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                {{ $data->payment_method }}</td>
              <td class="text-end p-4 text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                {{ format_rupiah($data->payment) }}</td>
            </tr>
            <tr>
              <td colspan="3" class="text-end p-4 text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                Change</td>
              <td class="text-end p-4 text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                {{ format_rupiah($data->change) }}</td>
            </tr>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

  </div>
@endsection

@push('script')
  <script type="text/javascript"></script>
@endpush
