@extends('layouts.index')

@section('main')
  <div class="">
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
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Arus Kas Bulanan</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                Pemasukan Dana</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Pembaruan Pemasukan Dana</h1>
      <a href="{{ route('management.fund.monthly') }}"
        class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Kembali
      </a>
    </div>

    <div class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <form action="{{ route('management.fund.monthly.update_cash_in', ['id' => $data->id]) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <label for="fund" class="mb-2 block text-sm font-medium text-gray-900">Nominal Pemasukan</label>
              <input type="text" name="fund" id="fund" onkeyup="keyup_rupiah(this)"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nominal" required
                value="{{ str_replace('Rp ', '', format_rupiah(old('nominal', $data->fund))) }}">
            </div>

            <div>
              <label for="type" class="block mb-2 text-sm font-medium text-gray-900">Tipe Dana</label>
              <select id="type" name="type"
                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required>
                <option disabled value="" selected>~ Pilih Tipe Dana ~</option>
                @foreach ($funds as $item)
                  <option value="{{ $item->id }}" @if (old('type', $data->id) == $item->id) selected @endif>
                    {{ $item->name }}
                  </option>
                @endforeach

              </select>
              @error('type')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="remarks" class="mb-2 block text-sm font-medium text-gray-900">Keterangan</label>
              <input type="text" name="remarks" id="remarks"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Keterangan" required value="{{ $data->remarks }}">
              @error('remarks')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="datetime" class="mb-2 block text-sm font-medium text-gray-900">Waktu</label>
              <input type="datetime-local" name="datetime" id="datetime"
                class="block w-fit rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Date" value="{{ old('datetime', $data->datetime) }}" required
                min="{{ Carbon\Carbon::parse($data->datetime)->hour(00)->minute(00)->second(00)->format('Y-m-d\TH:i') }}"
                max="{{ Carbon\Carbon::parse($data->datetime)->hour(23)->minute(59)->second(59)->format('Y-m-d\TH:i') }}">
              @error('datetime')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <button type="submit"
              class="w-fit shadow-lg justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
              Perbarui
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script>
    $(document).ready(function() {
      extendRemark();
    });

    if (!$('#remarks_from_master').val()) {
      $('#optional_remark').addClass('hidden');
    } else {
      if ($('#remarks_from_master').val() == $('#remark').val()) {
        $('#is_same').prop('checked', true);
      }
    }

    const extendRemark = () => {
      if ($('#remarks_from_master').val() == 'Tanpa Kategori') {
        $('#optional_remark').addClass('hidden');
        $('#remark').val("");
        $('#is_same').prop('checked', false);
      } else {
        $('#optional_remark').removeClass('hidden');
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
