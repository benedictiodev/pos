@extends('layouts.index')

@section('main')
  <div class="">
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
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Mitra Perusahaan</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Perbarui Mitra Perusahaan</h1>
      <a href="{{ route('management.company.index') }}"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Kembali
      </a>
    </div>

    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <form action="{{ route('management.company.update', ["id" => $data->id]) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="space-y-4">
            <div>
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Nama Mitra Perusahaan</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nama Mitra Perusahaan" required
                value="{{ old('name', $data->name) }}">
              @error('name')
                <p class="text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="address" class="mb-2 block text-sm font-medium text-gray-900">Alamat</label>
              <input type="text" name="address" id="address"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Alamat" required
                value="{{ old('address', $data->address) }}">
              @error('address')
                <p class="text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900">No Telfon</label>
              <input type="text" name="phone_number" id="phone_number"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="No Telfon" required
                value="{{ old('phone_number', $data->phone_number) }}">
              @error('phone_number')
                <p class="text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="type_subscription" class="block mb-2 text-sm font-medium text-gray-900">Tipe Langganan</label>
              <select id="type_subscription" name="type_subscription"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required>
                <option disabled value="">~ Pilih Tipe Langganan ~</option>
                <option value="basic" {{ $data->type_subscription == 'basic' ? 'selected' : '' }}>Basic</option>
                <option value="pro" {{ $data->type_subscription == 'pro' ? 'selected' : '' }}>Pro</option>
              </select>
              @error('type_subscription')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="subscription_fee" class="mb-2 block text-sm font-medium text-gray-900">Harga Langganan</label>
              <input type="text" name="subscription_fee" id="subscription_fee"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Harga Langganan" required
                value="{{ old('subscription_fee', $data->subscription_fee) }}">
              @error('subscription_fee')
                <p class="text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="expired_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Berakhir Langganan</label>
              <input type="date" name="expired_date" id="expired_date"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Tanggal Berakhir Langganan"
                value="{{ old('expired_date', Carbon\Carbon::parse($data->expired_date)->format('Y-m-d')) }}"
                min="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
            </div>
            <div>
              <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900">Role Perusahaan</label>
              <select id="role_id" name="role_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required>
                <option disabled value="">~ Role Perusahaan ~</option>
                @foreach ($roles as $item)
                  <option value="{{ $item->id }}" @if (old('role_id', $data->role_id) == $item->id) selected @endif>
                    {{ $item->name }}</option>
                @endforeach
              </select>
              @error('role_id')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>

            <button type="submit"
              class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
              Perbarui
            </button>
        </form>
      </div>
    </div>
  </div>
@endsection
