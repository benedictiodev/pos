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
                <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2" aria-current="page">Monthly Cash Flow</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Monthly Cash Flow</h1>
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
            <label for="cashflow-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="month" name="periode" id="cashflow-search"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-sm"
                placeholder="Search for daily cash flow" value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}" onchange="change_search()">
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
              <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-600 mb-10 border-b">
                <thead class="bg-gray-100 dark:bg-gray-700">
                  <tr>
                    <th scope="col"
                      colspan="6"
                      class="p-4 text-Center text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Detail Finance For This Month
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                  @if  ($type_closing_cycle == 'found')
                    <tr>
                      <td scope="col"
                        class="p-4 text-left text-base font-normal uppercase text-black dark:text-gray-400">
                        Equite :
                      </td>
                      <td scope="col"
                        class="p-4 text-right text-base font-semibold uppercase text-black dark:text-gray-400">
                        {{ format_rupiah($closing_cycle->equity) }}
                      </td>
                      <td scope="col"
                        class="p-4 text-left text-base font-normal uppercase text-black dark:text-gray-400">
                        Target :
                      </td>
                      <td scope="col"
                        class="p-4 text-right text-base font-semibold uppercase text-black dark:text-gray-400">
                        {{ format_rupiah($closing_cycle->target) }}
                      </td>
                      <td scope="col"
                        class="p-4 text-left text-base font-normal uppercase text-black dark:text-gray-400">
                        Target (%) :
                      </td>
                      <td scope="col"
                        class="p-4 text-right text-base font-semibold uppercase text-black dark:text-gray-400">
                        {{ $closing_cycle->is_done == 1 ? $closing_cycle->profit / $closing_cycle->target * 100 : '-' }}
                      </td>
                    </tr>
                    @if ($closing_cycle->is_done == 0)
                      <tr>
                        <td scope="col"
                          colspan="6"
                          class="p-4 text-center text-base font-semibold uppercase text-black dark:text-gray-400">
                            <button
                              data-modal-target="modal-closing-cycle" data-modal-toggle="modal-closing-cycle"
                              class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                              Closing Cycle For This Month
                            </button>
                        </td>
                      </tr>
                    @else
                      <tr>
                        <td scope="col"
                          class="p-4 text-left text-base font-normal uppercase text-black dark:text-gray-400">
                          Income :
                        </td>
                        <td scope="col"
                          class="p-4 text-right text-base font-semibold uppercase text-black dark:text-gray-400">
                          {{ format_rupiah($closing_cycle->income) }}
                        </td>
                        <td scope="col"
                          class="p-4 text-left text-base font-normal uppercase text-black dark:text-gray-400">
                          Expenditure :
                        </td>
                        <td scope="col"
                          class="p-4 text-right text-base font-semibold uppercase text-black dark:text-gray-400">
                          {{ format_rupiah($closing_cycle->expenditure) }}
                        </td>
                        <td scope="col"
                          class="p-4 text-left text-base font-normal uppercase text-black dark:text-gray-400">
                          Profit :
                        </td>
                        <td scope="col"
                          class="p-4 text-right text-base font-semibold uppercase text-black dark:text-gray-400">
                          {{ format_rupiah($closing_cycle->profit) }}
                        </td>
                      </tr>
                    @endif
                  @elseif ($type_closing_cycle == 'clear' || $type_closing_cycle == 'add_equite')
                    <tr>
                      <td scope="col"
                      colspan="6"
                        class="p-4 text-center text-base font-normal uppercase text-black dark:text-gray-400">
                        <button
                          data-modal-target="modal-add-equite" data-modal-toggle="modal-add-equite"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                          Add Equite For This Month
                        </button>
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>

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
                      Date
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Debit
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Kredit
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Amount
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Total Amount
                    </th>
                    <th scope="col"
                      class="p-4 text-center text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                  @foreach ($data as $item)
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
                          {{ $item->datetime }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ format_rupiah($item->debit) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ format_rupiah($item->kredit) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ format_rupiah($item->amount) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">
                          {{ format_rupiah($item->total_amount) }}
                        </p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        <a href="{{ route('dashboard.finance.cash-flow-daily', ['periode' => $item->datetime]) }}"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                          <x-fas-info class="mr-2 h-4 w-4" />
                          Detail
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot class="bg-gray-100 dark:bg-gray-700">
                  <tr>
                    <th scope="col"
                      class="p-4 text-center text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      Grand Total
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      {{ format_rupiah($total_cash_out) }}
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      {{ format_rupiah($total_cash_in) }}
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                      {{ format_rupiah($total_amount) }}
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500 dark:text-gray-400">
                    </th>
                    <th scope="col" colspan="2"
                      class="p-4 text-center text-base font-medium uppercase text-gray-500 dark:text-gray-400">
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-add-equite" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Add Equite
          </h3>
          <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-add-equite">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <form action="{{ route('dashboard.finance.equite.post') }}" method="POST" id="form-add_equite">
            @csrf
            <input type="text" name="periode" value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}" hidden>
            <div id="payment_form" class="border pl-4 pr-2 pt-4 pb-2 rounded-lg relative mb-3">
              <div class="absolute top-[-11px] left-0 right-0 flex justify-center">
                <div class="bg-white px-4 text-sm font-semibold">Equite Of Fund</div>
              </div>
              @foreach ($fund as $item_fund)  
              <label for="equite" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Equite - {{ $item_fund->type }}</label>
                <input type="number" name="equite[{{ $item_fund->type }}]"
                  onkeyup="equite_change()"
                  class="option-equite mb-2 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                  placeholder="Equite - {{ $item_fund->type }}">
              @endforeach
            </div>
            <div class="mb-3">
              <label for="equite" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Total Equite</label>
              <input type="number" name="equite_total" id="equite"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Total Equite" value="0" readonly>
            </div>
            <div class="mb-3">
              <label for="target" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Target</label>
              <input type="number" name="target" id="target"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Target" required>
            </div>
          </form>
          <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
            Are you sure you want to add this equite?
          </p>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button type="submit" form="form-add_equite" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Yes, I'm suret</button>
          <button data-modal-hide="modal-add-equite" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-closing-cycle" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Closing Cycle
          </h3>
          <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-closing-cycle">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <form action="{{ route('dashboard.finance.equite.post') }}" method="POST" id="form-closing_cycle">
            @csrf
            <input type="text" name="periode" value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}" hidden>
            <div class="mb-3">
              <label for="income" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Income</label>
              <input type="number" name="income" id="income"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Income" value="{{ $total_cash_in }}" readonly>
            </div>
            <div class="mb-3">
              <label for="expenditure" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">expenditure</label>
              <input type="number" name="expenditure" id="expenditure"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Expenditure" value="{{ $total_cash_out }}" readonly>
            </div>
          </form>
          <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
            Are you sure you want to closing cycle?
          </p>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button type="submit" form="form-add_equite" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Yes, I'm suret</button>
          <button data-modal-hide="modal-closing-cycle" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, Cancel</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    function change_search() {
      let value = document.querySelector("#cashflow-search").value;
      document.querySelector("#form-search").action = `/dashboard/finance/cash-flow-monthly/`;
      document.querySelector("#form-search").submit();
    }

    const equite_change = (e) => {
      let equite = 0;
      $('.option-equite').each(function() {
        equite += Number(this.value ? this.value : 0);
      });
      $('#equite').val(equite);
    }
  </script>
@endpush
