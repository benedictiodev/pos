@extends('layouts.index')

@section('main')
  <div class="">
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
                Cash Out</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-4">Create Cash Out</h1>
      <a href="{{ route('dashboard.finance.cash-flow-daily') }}"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300 dark:bg-slate-600 dark:hover:bg-slate-400 dark:focus:ring-slate-800">
        Back
      </a>
    </div>

    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
      <div class="mb-4">
        <form action="{{ route('dashboard.finance.cash-out.post') }}" method="POST">
          @csrf
          <div class="space-y-6">
            <div>
              <label for="fund" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nominal</label>
              <input type="number" name="fund" id="fund"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Nominal" required>
            </div>

            <div>
              <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
              <select id="type" name="type"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required>
                <option disabled value="" selected>~ Select Type ~</option>
                @foreach ($funds as $item)
                  <option value="{{ $item->type }}" @if (old('type') == $item->type) selected @endif>
                    {{ $item->type }}</option>
                @endforeach

              </select>
              @error('type')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="remarks_from_master"
                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Remark</label>
              <select id="remarks_from_master" name="remarks_from_master"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required onchange="extendRemark()">
                <option disabled value="" selected>~ Select Remark ~</option>
                @foreach ($remarks as $item)
                  <option value="{{ $item->name }}" @if (old('remarks_from_master') == $item->name) selected @endif>
                    {{ $item->name }}</option>
                @endforeach
              </select>
              @error('remarks_from_master')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div id="optional_remark" class="space-y-2 hidden">
              <div class="flex items-center">
                <input id="is_same" aria-describedby="checkbox-1" type="checkbox" name="is_same" value="1"
                  onchange="optionalRemark()"
                  class="focus:ring-3 h-4 w-4 border-gray-300 bg-gray-50 focus:ring-primary-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600">
                <label for="is_same" class="sr-only">checkbox</label>
                <p class="ml-2 text-sm font-medium text-gray-900 dark:text-white">Same the remark</p>
              </div>
              <textarea id="remark" rows="4" name="remark"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Describe your remark">{{ old('remark') }}</textarea>
              @error('remark')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="datetime" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Date</label>
              <input type="datetime-local" name="datetime" id="datetime"
                class="block w-fit rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Date" value="{{ old('datetime') }}" required
                min="{{ Carbon\Carbon::now()->hour(00)->minute(00)->second(00)->format('Y-m-d\TH:i') }}"
                max="{{ Carbon\Carbon::now()->hour(23)->minute(59)->second(59)->format('Y-m-d\TH:i') }}">
              @error('datetime')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <button type="submit"
              class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
              Add
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script>
    const extendRemark = () => {
      if ($('#remarks_from_master').val() != null) {
        $('#optional_remark').removeClass('hidden');
        $('#remark').attr('required', true);
      }
    }

    const optionalRemark = () => {
      if ($('#is_same').is(':checked')) {
        $('#remark').val($('#remarks_from_master').val());
      } else {
        $('#remark').val("");
      }
    }
  </script>
@endpush
