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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Keuangan</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                  Dana
                </span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Dana</h1>
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

    <div class="grid grid-col-3 sm:grid-cols-3 lg:grid-cols-3 gap-4 mx-[-0.25rem]">
      <div
        class="flex justify-between items-center bg-sky-300 text-white rounded-lg shadow-lg p-4 2xl:col-span-2 sm:p-6 mb-1 lg:mb-2 mx-1">
        <div class="flex items-center text-sm">
          <x-fas-money-bill class="w-6 h-6 text-white transition duration-75 group-hover:text-gray-900 mr-2" /> Total
          Dana :
        </div>
        <div class="font-bold text-sm"> 
          {{ format_rupiah($total_fund) }}
        </div>
      </div>
      @foreach ($data as $item)
        <div
          class="flex justify-between items-center bg-white rounded-lg shadow-lg p-4 2xl:col-span-2 sm:p-6 mb-1 lg:mb-2 mx-1">
          <div class="flex items-center text-sm">
            <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-2" />
            {{ $item->name }} :
          </div>
          <div class="font-bold text-sm"> {{ format_rupiah($item->fund) }}</div>
        </div>
      @endforeach
    </div>

    <div class="mt-4 p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6 mb-4">
      <div class="block items-center justify-between sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="{{ route('dashboard.finance.funds') }}" method="GET">
            <label for="products-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="text" name="search" id="products-search"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Pencarian pengalihan alokasi dana"
                @if (isset($_GET['search'])) value="{{ $_GET['search'] }}" @endif>
            </div>
          </form>
        </div>
        @can('keuangan-dana-tambah pengalihan baru alokasi dana')
          <a id="createProductButton"
            class="rounded-lg bg-primary-700 px-5 py-2.5 shadow-md text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300"
            href="{{ route('management.fund.create_allowance_fund') }}">
            Tambahkan pengalihan baru alokasi dana
          </a>
        @endcan
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow rounded-t-lg">
              <table class="min-w-full table-fixed divide-y divide-sky-100">
                <thead class="bg-sky-300">
                  <tr>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                      Waktu
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                      Dari Tipe Dana
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                      Tujuan Tipe Dana
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      Total Dana Yang Dipindahkan
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @forelse ($allocation as $item)
                    <tr class="hover:bg-gray-100">
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->datetime }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->from_type->name }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->to_type->name }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
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
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 sm:flex sm:justify-between">
        {{ $allocation->links('vendor.pagination.tailwind') }}
      </div>
    </div>
  </div>
@endsection
