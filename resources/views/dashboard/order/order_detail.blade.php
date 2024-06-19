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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Order</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Detail</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Order Detail</h1>
      </div>
    </div>

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4">
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
              <div class="mb-4 space-y-12">
                <table class="table-fixed divide-y divide-gray-200 w-full">
                  <thead class="divide-y divide-gray-200">
                    <tr class="bg-gray-100">
                      <td colspan="4" class="p-4 text-center text-lg font-bold uppercase text-gray-500"> ID Order
                        {{ $data->id_order }}</td>
                    </tr>
                    <tr>
                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Cashier</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->cashier_name }}</td>

                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Date</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->datetime }}</td>
                    </tr>
                    <tr>
                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Customer</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->customer_name }}</td>

                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Order Type</th>
                      <td class="p-4 text-sm font-normal text-gray-900">
                        {{ $data->order_type == 'dine_in' ? 'DINE IN' : 'TAKE AWAY' }}</td>
                    </tr>
                    <tr>
                      <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Remark</th>
                      <td class="p-4 text-sm font-normal text-gray-900" colspan="3">
                        {{ $data->remarks }}</td>
                    </tr>
                    <tr></tr>
                  </thead>
                </table>

                <table class="min-w-full table-fixed divide-y divide-gray-200">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500">
                        Product
                      </th>
                      <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500">
                        Remark
                      </th>
                      <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500">
                        Quantity
                      </th>
                      <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500">
                        Price
                      </th>
                      <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500">
                        Amount
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
                        <td class="text-center text-base font-light p-4" colspan="7">Empty Data</td>
                      </tr>
                    @endforelse
                  </tbody>
                  <tfoot class="bg-gray-100">
                    <tr>
                      <td class="text-start p-2 text-base font-bold uppercase text-gray-500">
                        {{ count($data->items) }} items</td>
                      <td colspan="3" class="text-end p-2 text-base font-bold uppercase text-gray-500">Total
                      </td>
                      <td class="text-end p-2 text-base font-bold uppercase text-gray-500">
                        {{ format_rupiah($data->total_payment) }}</td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-end p-2 text-base font-bold uppercase text-gray-500">
                        {{ $data->payment_method }}</td>
                      <td class="text-end p-2 text-base font-bold uppercase text-gray-500">
                        {{ format_rupiah($data->payment) }}</td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-end p-2 text-base font-bold uppercase text-gray-500">
                        Change</td>
                      <td class="text-end p-2 text-base font-bold uppercase text-gray-500">
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
