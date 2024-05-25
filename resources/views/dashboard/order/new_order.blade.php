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
                <span class="ml-1 text-gray-400 dark:text-gray-300 md:ml-2" aria-current="page">
                  Order
                </span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2" aria-current="page">Add New Order</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-4">Add New Order</h1>
        <a href="{{ route('dashboard.order.order_active') }}"
            class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300 dark:bg-slate-600 dark:hover:bg-slate-400 dark:focus:ring-slate-800">
            Back
        </a>
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
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="flex">
              <div class="w-2/5 border mr-3 rounded-xl max-h-[450px]">
                <div class="h-5/6 overflow-auto">
                  <div class="mx-2 px-2 py-3 flex border-b font-semibold">
                    <div class="w-3/6">Menu</div>
                    <div class="w-1/6 text-center">Qty</div>
                    <div class="w-2/6 text-right">Price</div>
                  </div>
                  <div class="mx-2 px-2 py-2 flex border-b text-sm">
                    <div class="w-3/6">
                      <div>1. Nasi</div>
                      <div class="text-xs font-thin">catatan : -</div>
                    </div>
                    <div class="w-1/6 text-center">10</div>
                    <div class="w-2/6 text-right">Rp. 100.000</div>
                  </div>
                  <div class="mx-2 px-2 py-2 flex border-b text-sm">
                    <div class="w-3/6">
                      <div>2. Ayam</div>
                      <div class="text-xs font-thin">catatan : Paha 5, Dada 3</div>
                    </div>
                    <div class="w-1/6 text-center">8</div>
                    <div class="w-2/6 text-right">Rp. 40.000</div>
                  </div>
                  <div class="mx-2 px-2 py-2 flex border-b text-sm">
                    <div class="w-3/6">
                      <div>3. Baso</div>
                      <div class="text-xs font-thin">catatan : -</div>
                    </div>
                    <div class="w-1/6 text-center">3</div>
                    <div class="w-2/6 text-right">Rp. 36.000</div>
                  </div>
                  <div class="mx-2 px-2 py-2 flex border-b text-sm">
                    <div class="w-3/6">
                      <div>4. Kerupuk</div>
                      <div class="text-xs font-thin">catatan : kerupuk putih</div>
                    </div>
                    <div class="w-1/6 text-center">4</div>
                    <div class="w-2/6 text-right">Rp. 4.000</div>
                  </div>
                </div>
                <div class="h-1/6 bg-[#E5E7EB] rounded-b-lg flex flex-col justify-center items-center px-4">
                  <div class="flex justify-between w-full font-semibold text-sm mb-2">
                    <div>Total Price</div>
                    <div>Rp. 180.000</div>
                  </div>
                  <button
                  class="mx-4 w-full justify-center rounded-lg bg-primary-700 py-1.5 text-center text-xs font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                  >Order Now</button>
                </div>
              </div>
              <div class="w-3/5 border ml-3 rounded-xl max-h-[450px] overflow-auto">
                <div class="p-2 text-center text-lg font-semibold bg-[#E5E7EB] rounded-t-lg border-b-2 border-white">List Menu</div>
                <div>
                  @foreach ($list_menu as $category)  
                    <div>
                      <div class="bg-[#E5E7EB] py-1 px-2">Category : {{ $category->category_name }}</div>
                      <div class="grid grid-cols-4 gap-2 px-1 py-2">
                        @foreach ($category->products as $item) 
                          <div class="mx-1 border-2 p-2 rounded-lg h-full relative pb-20">
                            <div class="border p-2 rounded-md flex items-center justify-center h-32 max-h-32">
                              <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                            </div>
                            <div class="mt-2 ">
                              <div class="font-medium">{{ $item->name }}</div>
                              <div class="font-thin text-sm">{{ $item->description }}</div>
                            </div>
                            <div class="absolute w-full bottom-[8px] pr-4">
                              <div class="text-right mt-2 text-sm font-bold">{{ format_rupiah($item->price) }}</div>
                              <button
                                class="mt-2 w-full justify-center rounded-lg bg-primary-700 py-1.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Add to cart
                              </button>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
