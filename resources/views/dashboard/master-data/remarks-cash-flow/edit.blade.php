@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-4">
      <nav class="mb-5 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
          <li class="inline-flex items-center">
            <a href="#"
              class="inline-flex items-center text-gray-700 hover:text-primary-600">
              Beranda
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Master
                Data</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                Keterangan Arus Kas</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Pembaruan Keterangan Arus Kas</h1>
      <a href="{{ route('dashboard.master-data.remarks-cash-flow') }}"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Kembali
      </a>
    </div>

    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4">
      <div>
        <form action="{{ route('dashboard.master-data.remarks-cash-flow.update', ['id' => $data->id]) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Nama Keterangan</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nama Keterangan" required value="{{ old('name', $data->name) }}">
            </div>

            <div>
              <label for="type" class="mb-2 block text-sm font-medium text-gray-900">Tipe</label>
              <select id="type" name="type"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                required>
                <option disabled value="">Pilih Tipe</option>
                <option {{ old('type', $data->type) == 'cash_in' ? 'selected' : '' }} value="cash_in">Pemasukkan Dana</option>
                <option {{ old('type', $data->type) == 'cash_out' ? 'selected' : '' }} value="cash_out">Pengeluaran Dana</option>
              </select>
            </div>

            <button type="submit"
              class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
              Perbarui
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
