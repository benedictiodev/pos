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
                <span class="ml-1 text-gray-400 dark:text-gray-300 md:ml-2" aria-current="page">Finance</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2" aria-current="page">
                  Funds</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Funds</h1>
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

    <div class="flex mx-[-0.25rem]">
      <div
        class="flex justify-between items-center bg-sky-200 border border-gray-200 rounded-lg shadow-lg p-4 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 mb-2 w-1/4 mx-1">
        <div class="flex items-center text-sm">
          <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-2" /> Total
          Funds :
        </div>
        <div class="font-bold text-sm"> {{ format_rupiah($total_fund) }}</div>
      </div>
      @foreach ($data as $item)
        <div
          class="flex justify-between items-center bg-white border border-gray-200 rounded-lg shadow-lg p-4 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 mb-2 w-1/4 mx-1">
          <div class="flex items-center text-sm">
            <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-2" />
            {{ $item->type }} :
          </div>
          <div class="font-bold text-sm"> {{ format_rupiah($item->fund) }}</div>
        </div>
      @endforeach
    </div>

    <div
      class="mt-4 p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 mb-4">
      <div class="block items-center justify-between dark:divide-gray-700 sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="#" method="GET">
            <label for="products-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="text" name="email" id="products-search"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-sm"
                placeholder="Search for diversion of fund allocation">
            </div>
          </form>
        </div>
        <a id="createProductButton"
          class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
          href="{{ route('dashboard.finance.funds.create') }}">
          Add new diversion of fund allocation
        </a>
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
              <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-700">
                  <tr>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Datetime
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      From Fund
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      To Fund
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Amount
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                  @forelse ($data_history as $item)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->datetime }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->from_type }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ $item->to_type }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ format_rupiah($item->amount) }}
                        </p>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td class="text-center text-base font-light p-4" colspan="4">Empty Data</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:flex sm:justify-between">
        {{ $data_history->links('vendor.pagination.tailwind') }}
      </div>
    </div>
  </div>
@endsection
