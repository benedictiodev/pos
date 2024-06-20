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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Finance</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Monthly Cash Flow</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Monthly Cash Flow</h1>
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

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4">
      <div class="block items-center justify-between sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="#" method="GET" id="form-search">
            <label for="cashflow-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="month" name="periode" id="cashflow-search"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Search for daily cash flow"
                value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}"
                onchange="change_search()">
            </div>
          </form>
          {{-- <div class="flex w-full items-center sm:justify-end">
            <div class="flex space-x-1 pl-2">
              <a href="#"
                class="inline-flex cursor-pointer justify-center rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                <x-fas-trash-alt class="h-6 w-6" />
              </a>
            </div>
          </div> --}}
        </div>
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
              <table class="min-w-full table-fixed divide-y divide-gray-200 mb-10 border-b">
                <thead class="bg-gray-100">
                  <tr>
                    <th scope="col" colspan="6" class="p-4 text-Center text-base font-bold uppercase text-gray-500">
                      Detail Finance For This Month
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @if ($type_closing_cycle == 'found')
                    <tr>
                      <td scope="col" class="p-4 text-left text-base font-normal uppercase text-black">
                        Equite :
                      </td>
                      <td scope="col" class="p-4 text-right text-base font-semibold uppercase text-black">
                        {{ format_rupiah($closing_cycle->equity) }}
                      </td>
                      <td scope="col" class="p-4 text-left text-base font-normal uppercase text-black">
                        Target :
                      </td>
                      <td scope="col" class="p-4 text-right text-base font-semibold uppercase text-black">
                        {{ format_rupiah($closing_cycle->target) }}
                      </td>
                      <td scope="col" class="p-4 text-left text-base font-normal uppercase text-black">
                        Target (%) :
                      </td>
                      <td scope="col" class="p-4 text-right text-base font-semibold uppercase text-black">
                        {{ $closing_cycle->is_done == 1 ? ($closing_cycle->profit / $closing_cycle->target) * 100 . '%' : '-' }}
                      </td>
                    </tr>
                    @if ($closing_cycle->is_done == 0)
                      @if (
                          (Request::get('periode') &&
                              ((Request::get('periode') == Date::now()->format('Y-m') &&
                                  Date::now()->format('d') == Date::now()->endOfMonth()->format('d')) ||
                                  Request::get('periode') < Date::now()->format('Y-m'))) ||
                              (!Request::get('periode') && Date::now()->format('d') == Date::now()->endOfMonth()->format('d')))
                        <tr>
                          <td scope="col" colspan="6"
                            class="p-4 text-center text-base font-semibold uppercase text-black">
                            <button data-modal-target="modal-closing-cycle" data-modal-toggle="modal-closing-cycle"
                              class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
                              Closing Cycle For This Month
                            </button>
                          </td>
                        </tr>
                      @endif
                    @else
                      <tr>
                        <td scope="col" class="p-4 text-left text-base font-normal uppercase text-black">
                          Income :
                        </td>
                        <td scope="col" class="p-4 text-right text-base font-semibold uppercase text-black">
                          {{ format_rupiah($closing_cycle->income) }}
                        </td>
                        <td scope="col" class="p-4 text-left text-base font-normal uppercase text-black">
                          Expenditure :
                        </td>
                        <td scope="col" class="p-4 text-right text-base font-semibold uppercase text-black">
                          {{ format_rupiah($closing_cycle->expenditure) }}
                        </td>
                        <td scope="col" class="p-4 text-left text-base font-normal uppercase text-black">
                          Profit :
                        </td>
                        <td scope="col" class="p-4 text-right text-base font-semibold uppercase text-black">
                          {{ format_rupiah($closing_cycle->profit) }}
                        </td>
                      </tr>
                    @endif
                  @elseif ($type_closing_cycle == 'clear' || $type_closing_cycle == 'add_equite')
                    <tr>
                      <td scope="col" colspan="6"
                        class="p-4 text-center text-base font-normal uppercase text-black">
                        <button data-modal-target="modal-add-equite" data-modal-toggle="modal-add-equite"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
                          Add Equite For This Month
                        </button>
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>

              <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th scope="col" class="p-4">
                      <div class="flex items-center">
                        <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox" ">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                      </div>
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-gray-500">
                      Date
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500">
                      Debit
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500">
                      Kredit
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500">
                      Amount
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-gray-500">
                      Total Amount
                    </th>
                    <th scope="col"
                      class="p-4 text-center text-base font-bold uppercase text-gray-500">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @forelse ($data as $item)
                    <tr class="hover:bg-gray-100">
                      <td class="w-4 p-4">
                        <div class="flex items-center">
                          <input id="checkbox-" aria-describedby="checkbox-1" type="checkbox" ">
                          <label for="checkbox-" class="sr-only">checkbox</label>
                        </div>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->datetime }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->debit) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->kredit) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->amount) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->total_amount) }}
                        </p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        <a href="{{ route('dashboard.finance.cash-flow-daily', ['periode' => $item->datetime]) }}"
                          class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
                          <x-fas-info class="mr-2 h-4 w-4" />
                          Detail
                        </a>
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
                    <th scope="col" class="p-4 text-center text-base font-bold uppercase text-gray-500">
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-gray-500">
                      Grand Total
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500">
                      {{ format_rupiah($total_cash_out) }}
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500">
                      {{ format_rupiah($total_cash_in) }}
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500">
                      {{ format_rupiah($total_amount) }}
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-gray-500">
                    </th>
                    <th scope="col" colspan="2"
                      class="p-4 text-center text-base font-medium uppercase text-gray-500">
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

  <div id="modal-add-equite" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            Add Equite
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            data-modal-hide="modal-add-equite">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <form action="{{ route('dashboard.finance.equite.post') }}" method="POST" id="form-add_equite">
            @csrf
            <input type="text" name="periode"
              value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}" hidden>
            <div id="payment_form" class="border pl-4 pr-2 pt-4 pb-2 rounded-lg relative mb-3">
              <div class="absolute top-[-11px] left-0 right-0 flex justify-center">
                <div class="bg-white px-4 text-sm font-semibold">Equite Of Fund</div>
              </div>
              @foreach ($fund as $item_fund)
                <label for="equite" class="mb-2 block text-sm font-medium text-gray-900">Equite -
                  {{ $item_fund->type }}</label>
                <input type="text" name="equite[{{ $item_fund->type }}]" onkeyup="equite_change()"
                  class="option-equite mb-2 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="Equite - {{ $item_fund->type }}">
              @endforeach
            </div>
            <div class="mb-3">
              <label for="equite" class="mb-2 block text-sm font-medium text-gray-900">Total Equite</label>
              <input type="text" name="equite_total" id="equite"
                onkeyup="keyup_rupiah(this)"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Total Equite" value="0" readonly>
            </div>
            <div class="mb-3">
              <label for="target" class="mb-2 block text-sm font-medium text-gray-900">Target</label>
              <input type="text" name="target" id="target"
                onkeyup="keyup_rupiah(this)"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Target" required>
            </div>
          </form>
          <p class="text-base leading-relaxed text-gray-500">
            Are you sure you want to add this equite?
          </p>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="submit" form="form-add_equite"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Yes,
            I'm sure</button>
          <button data-modal-hide="modal-add-equite" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">No,
            Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-closing-cycle" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            Closing Cycle
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            data-modal-hide="modal-closing-cycle">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <form action="{{ route('dashboard.finance.equite.closing') }}" method="POST" id="form-closing_cycle">
            @csrf
            <input type="text" name="periode"
              value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}" hidden>
            <div class="mb-3">
              <div class="flex">
                <div class="w-1/3">
                  <label for="income" class="mb-2 block text-sm font-medium text-gray-900">Income</label>
                  <input type="text" name="income" id="income" onkeyup="keyup_rupiah(this)"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Income" value="{{ str_replace('Rp ', '', format_rupiah($total_cash_in)) }}" readonly>
                </div>
                <div class="w-1/3 mx-2">
                  <label for="expenditure" class="mb-2 block text-sm font-medium text-gray-900">Expenditure</label>
                  <input type="text" name="expenditure" id="expenditure" onkeyup="keyup_rupiah(this)"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Expenditure" value="{{ str_replace('Rp ', '', format_rupiah($total_cash_out)) }}" readonly>
                </div>
                <div class="w-1/3">
                  <label for="amount" class="mb-2 block text-sm font-medium text-gray-900">Amount</label>
                  <input type="text" name="amount" id="amount" onkeyup="keyup_rupiah(this)"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Amount" value="{{ str_replace('Rp ', '', format_rupiah($total_amount)) }}" readonly>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="flex">
                <div class="w-1/3">
                  <label for="target" class="mb-2 block text-sm font-medium text-gray-900">Target</label>
                  <input type="text" name="target" id="target" onkeyup="keyup_rupiah(this)"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Target" value="{{ $closing_cycle ? str_replace('Rp ', '', format_rupiah($closing_cycle->target)) : 0 }}" readonly>
                </div>
                <div class="w-1/3 mx-2">
                  <label for="equity" class="mb-2 block text-sm font-medium text-gray-900">Equity</label>
                  <input type="text" name="equity" id="equity" onkeyup="keyup_rupiah(this)"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Equity" value="{{ $closing_cycle ? str_replace('Rp ', '', format_rupiah($closing_cycle->equity)) : 0 }}" readonly>
                </div>
                <div class="w-1/3">
                  <label for="profit" class="mb-2 block text-sm font-medium text-gray-900">Profit</label>
                  <input type="text" name="profit" id="profit" onkeyup="keyup_rupiah(this)"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Profit" value="{{ str_replace('Rp ', '', format_rupiah($total_amount - ($closing_cycle ? $closing_cycle->equity : 0))) }}"
                    readonly>
                </div>
              </div>
            </div>
            <div class="flex items-center mb-3">
              <input name="set_equity" id="set_equity" type="checkbox" value="check"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
              <label for="set_equity" class="ms-2 text-sm font-medium text-gray-900">Set Equity For Next Month</label>
            </div>
            <div class="mb-3" id="next_equite_form" hidden>
              <input type="text" name="next_periode"
                value="{{ Request::get('periode') ? Date::parse(Request::get('periode'))->addMonths(1)->format('Y-m') : Date::now()->addMonths(1)->format('Y-m') }}"
                hidden>
              <div id="payment_form" class="border pl-4 pr-2 pt-4 pb-2 rounded-lg relative mb-3">
                <div class="absolute top-[-11px] left-0 right-0 flex justify-center">
                  <div class="bg-white px-4 text-sm font-semibold">Equite Of Fund</div>
                </div>
                @foreach ($fund as $item_fund)
                  <label for="next_equite" class="mb-2 block text-sm font-medium text-gray-900">Equite -
                    {{ $item_fund->type }}</label>
                  <input type="text" name="next_equite[{{ $item_fund->type }}]" onkeyup="equite_change('next_')"
                    class="next_option-equite mb-2 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Equite - {{ $item_fund->type }}">
                @endforeach
              </div>
              <div class="mb-3">
                <label for="next_equite" class="mb-2 block text-sm font-medium text-gray-900">Total Equite</label>
                <input type="text" name="next_equite_total" id="next_equite" onkeyup="keyup_rupiah(this)"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="Total Equite" value="0" readonly>
              </div>
              <div class="mb-3">
                <label for="next_target" class="mb-2 block text-sm font-medium text-gray-900">Target</label>
                <input type="text" name="next_target" id="next_target" onkeyup="keyup_rupiah(this)"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="Target">
              </div>
            </div>
          </form>
          <p class="text-base leading-relaxed text-gray-500">
            Are you sure you want to closing cycle?
          </p>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="submit" form="form-closing_cycle"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Yes,
            I'm sure</button>
          <button data-modal-hide="modal-closing-cycle" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">No,
            Cancel</button>
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

    const equite_change = (type = '') => {
      let equite = 0;
      $(`.${type}option-equite`).each(function() {
        equite += parseInt((this.value ? this.value : '0').replaceAll('.', ''));
        this.value = update_to_format_rupiah(this.value);
      });
      $(`#${type}equite`).val(update_to_format_rupiah(equite));
    }

    $('#set_equity').on('click', function() {
      if (this.checked) {
        $('#next_equite_form').attr('hidden', false);
      } else {
        $('#next_equite_form').attr('hidden', true);
      }
    });
  </script>
@endpush
