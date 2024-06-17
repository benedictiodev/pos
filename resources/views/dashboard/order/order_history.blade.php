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
                <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2" aria-current="page">History</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Order History</h1>
      </div>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-gray-800 dark:text-green-400"
        role="alert">
        <span class="font-medium">{{ session('success') }}</span>
      </div>
    @endif
    @if (session('failed'))
      <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">{{ session('failed') }}</span>
      </div>
    @endif

    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 mb-4">
      <div class="block items-center justify-between dark:divide-gray-700 sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="#" method="GET" id="form-search">
            <label for="order-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-order sm:w-64 xl:w-96">
              <input type="date" name="periode" id="order-search"
                class="block w-full rounded-lg border border-gray-300 order-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-sm"
                placeholder="Search for daily cash flow"
                value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m-d') }}"
                onchange="change_search()">
            </div>
          </form>
          <div class="flex w-full items-center sm:justify-end">
            <div class="flex space-x-1 pl-2">
              <a href="#"
                class="inline-flex cursor-pointer justify-center rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <x-fas-trash-alt class="h-6 w-6" />
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
              <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="p-4">
                      <div class="flex items-center">
                        <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox"
                          class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                      </div>
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Order ID
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Datetime
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Cashier
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Payment Method
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Order Type
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Total
                    </th>
                    <th scope="col"
                      class="p-4 text-center text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                  @forelse ($data as $item)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                      <td class="w-4 p-4">
                        <div class="flex items-center">
                          <input id="checkbox-" aria-describedby="checkbox-1" type="checkbox"
                            class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600">
                          <label for="checkbox-" class="sr-only">checkbox</label>
                        </div>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->id_order }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->datetime }}
                        </p>
                      </td>
                      <td class="text-left whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->cashier_name }}
                        </p>
                      </td>
                      <td class="text-left whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->payment_method }}
                        </p>
                      </td>
                      <td class="text-left whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->order_type == 'dine_in' ? 'Dine In' : 'Take Away' }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white text-end">
                          {{ format_rupiah($item->total_payment) }}
                        </p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        <a href="{{ route('dashboard.order.order_detail', ['id' => $item->id]) }}"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                          <x-fas-file class="mr-2 h-4 w-4" />
                          View Order
                        </a>
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
                    <th scope="col" colspan="2s"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Grand Total
                    </th>
                    <th scope="col" colspan="5"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      {{ format_rupiah($total) }}
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:flex sm:justify-between">
        {{ $data->withQueryString()->links('vendor.pagination.tailwind') }}
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    function change_search() {
      let value = document.querySelector("#order-search").value;
      document.querySelector("#form-search").action = `/dashboard/order/history`;
      document.querySelector("#form-search").submit();
    }
  </script>
@endpush
