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
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Finance</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                Diversion of fund allocation</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Diversion of fund allocation</h1>
      <a href="{{ route('dashboard.finance.funds') }}"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Back
      </a>
    </div>

    @if (session('failed'))
      <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
        <span class="font-medium">{{ session('failed') }}</span>
      </div>
    @endif

    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <form action="{{ route('dashboard.finance.funds.post') }}" method="POST" id="form-submit">
          @csrf
          <div class="space-y-6">
            <div>
              <label for="amount" class="mb-2 block text-sm font-medium text-gray-900">Amount</label>
              <input type="text" name="amount" id="amount"
                onkeyup="keyup_rupiah(this)"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Amount" value="{{ old('amount') }}" required>
              @error('amount')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="from_type" class="block mb-2 text-sm font-medium text-gray-900">From Type</label>
              <select id="from_type" name="from_type"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required>
                <option disabled value="" selected>~ Select Type ~</option>
                @foreach ($funds as $item)
                  <option value="{{ $item->type }}" @if (old('from_type') == $item->type) selected @endif>
                    {{ $item->type }}</option>
                @endforeach

              </select>
              @error('from_type')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="to_type" class="block mb-2 text-sm font-medium text-gray-900">To Type</label>
              <select id="to_type" name="to_type"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required>
                <option disabled value="" selected>~ Select Type ~</option>
                @foreach ($funds as $item)
                  <option value="{{ $item->type }}" @if (old('to_type') == $item->type) selected @endif>
                    {{ $item->type }}</option>
                @endforeach

              </select>
              @error('to_type')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="datetime" class="mb-2 block text-sm font-medium text-gray-900">Date</label>
              <input type="datetime-local" name="datetime" id="datetime"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Date" value="{{ old('datetime') }}" required>
              @error('datetime')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <button type="button" data-drawer-target="drawer-add"
              data-drawer-show="drawer-add" aria-controls="drawer-add"
              data-drawer-placement="right"
              class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
              Add
            </button>
          </div>
        </form>
      </div>
    </div>

    <div id="drawer-add"
      class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
      tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
      <h5 id="drawer-label"
        class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Diversion of fund allocation
      </h5>
      <button type="button" data-drawer-dismiss="drawer-add"
        aria-controls="drawer-add"
        class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
        <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
        <span class="sr-only">Close menu</span>
      </button>
        <x-fas-circle-exclamation class="mb-4 mt-8 h-10 w-10 text-red-600" />
        <h3 class="mb-6 text-lg text-gray-500" id="info-text-drawer"></h3>
        <div id="button-div-control" hidden>
          <button type="button" data-type="button-submit"
            class="mr-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300">
            Yes, I'm sure
          </button>
          <button type="button"
            class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
            data-drawer-hide="drawer-add">
            No, cancel
          </button>
        </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    window.onload = () => {
      document.addEventListener('click', async (event) => {
        // DELETE DATA
        if (event.target.getAttribute('data-drawer-target') == "drawer-add") {
          const amount = document.querySelector("#amount").value;
          const from_type = document.querySelector("#from_type").value;
          const to_type = document.querySelector("#to_type").value;
          const datetime = document.querySelector("#datetime").value;

          if (amount && from_type && to_type && datetime) {
            if (from_type == to_type) {
              document.querySelector('#button-div-control').hidden = true;
              document.querySelector('#info-text-drawer').innerHTML = "From type to type must be different!";
            } else {
              document.querySelector('#button-div-control').hidden = false;
              document.querySelector('#info-text-drawer').innerHTML = "Are you sure you want to add this diversion of fund allocation?";
            }
          } else {
            document.querySelector('#button-div-control').hidden = true;
            document.querySelector('#info-text-drawer').innerHTML = "Please fill out field!";
          }
        }
        if (event.target.getAttribute('data-type') == "button-submit") {
          document.querySelector("#form-submit").submit();
        }
      })
    }
  </script>
@endpush
