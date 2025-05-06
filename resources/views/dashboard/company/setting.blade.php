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
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Toko</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Pengaturan</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Pengaturan</h1>
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
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <form action="{{ route('dashboard.company.setting.update', ['id' => Auth::user()->company_id]) }}" method="POST" id="form_edit_setting">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <div id="payment_form" class="border pl-4 pr-2 pt-4 pb-2 rounded-lg relative mb-3">
                <div class="absolute top-[-11px] left-0 right-0 flex justify-center">
                  <div class="bg-white px-4 text-sm font-semibold">Pengaturan Tampilan Struk Pembayaran</div>
                </div>
                
                <label for="setting_printer-store_name_value" class="mb-2 block text-sm font-medium text-gray-900">Nama Toko</label>
                <div class="flex items-center mb-2">
                  <input id="setting_printer-store_name_show" aria-describedby="checkbox-1" type="checkbox" name="setting_printer-store_name_show" value="1"
                    class="focus:ring-3 h-4 w-4 border-gray-300 bg-gray-50 focus:ring-primary-300" {{ $setting_printer->store_name->show ? 'checked' : '' }} disabled>
                  <label for="setting_printer-store_name_show" class="sr-only">checkbox</label>
                  <p class="ml-2 text-sm font-medium text-gray-900">Tampilkan Nama Toko Pada Struk Pembayaran</p>
                </div>
                <input type="text" name="setting_printer-store_name_value" id="setting_printer-store_name_value"
                  class="mb-3 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="Nama Toko"
                  value="{{ old('setting_printer-store_name_value', $setting_printer->store_name->value) }}" readonly>

                <label for="setting_printer-store_address_value" class="mb-2 block text-sm font-medium text-gray-900">Alamat Toko</label>
                <div class="flex items-center mb-2">
                  <input id="setting_printer-store_address_show" aria-describedby="checkbox-1" type="checkbox" name="setting_printer-store_address_show" value="1"
                    class="focus:ring-3 h-4 w-4 border-gray-300 bg-gray-50 focus:ring-primary-300" {{ $setting_printer->address->show ? 'checked' : '' }} disabled>
                  <label for="setting_printer-store_address_show" class="sr-only">checkbox</label>
                  <p class="ml-2 text-sm font-medium text-gray-900">Tampilkan Alamat Toko Pada Struk Pembayaran</p>
                </div>
                <input type="text" name="setting_printer-store_address_value" id="setting_printer-store_address_value"
                  class="mb-3 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="Alamat Toko"
                  value="{{ old('setting_printer-store_address_value', $setting_printer->address->value) }}" readonly>

                <label for="setting_printer-store_whatsapp_value" class="mb-2 block text-sm font-medium text-gray-900">No WhatsApp Toko</label>
                <div class="flex items-center mb-2">
                  <input id="setting_printer-store_whatsapp_show" aria-describedby="checkbox-1" type="checkbox" name="setting_printer-store_whatsapp_show" value="1"
                    class="focus:ring-3 h-4 w-4 border-gray-300 bg-gray-50 focus:ring-primary-300" {{ $setting_printer->wa->show ? 'checked' : '' }} disabled>
                  <label for="setting_printer-store_whatsapp_show" class="sr-only">checkbox</label>
                  <p class="ml-2 text-sm font-medium text-gray-900">Tampilkan No WhatsApp Toko Pada Struk Pembayaran</p>
                </div>
                <input type="text" name="setting_printer-store_whatsapp_value" id="setting_printer-store_whatsapp_value"
                  class="mb-3 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="No WhatsApp Toko"
                  value="{{ old('setting_printer-store_whatsapp_value', $setting_printer->wa->value) }}" readonly>

                <label for="setting_printer-store_ig_value" class="mb-2 block text-sm font-medium text-gray-900">Instagram Toko</label>
                <div class="flex items-center mb-2">
                  <input id="setting_printer-store_ig_show" aria-describedby="checkbox-1" type="checkbox" name="setting_printer-store_ig_show" value="1"
                    class="focus:ring-3 h-4 w-4 border-gray-300 bg-gray-50 focus:ring-primary-300" {{ $setting_printer->ig->show ? 'checked' : '' }} disabled>
                  <label for="setting_printer-store_ig_show" class="sr-only">checkbox</label>
                  <p class="ml-2 text-sm font-medium text-gray-900">Tampilkan Instagram Toko Pada Struk Pembayaran</p>
                </div>
                <input type="text" name="setting_printer-store_ig_value" id="setting_printer-store_ig_value"
                  class="mb-3 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="Instagram Toko"
                  value="{{ old('setting_printer-store_ig_value', $setting_printer->ig->value) }}" readonly>

                <label for="setting_printer-footer_value" class="mb-2 block text-sm font-medium text-gray-900">Footer</label>
                <div class="flex items-center mb-2">
                  <input id="setting_printer-footer_show" aria-describedby="checkbox-1" type="checkbox" name="setting_printer-footer_show" value="1"
                    class="focus:ring-3 h-4 w-4 border-gray-300 bg-gray-50 focus:ring-primary-300" {{ $setting_printer->ig->show ? 'checked' : '' }} disabled>
                  <label for="setting_printer-footer_show" class="sr-only">checkbox</label>
                  <p class="ml-2 text-sm font-medium text-gray-900">Tampilkan Footer Pada Struk Pembayaran</p>
                </div>
                <div id="body_footer">
                  @foreach ($setting_printer->footer->value as $index => $item) 
                    <div class="flex" id="setting_printer-footer_value-{{$index}}">
                      <input type="text" name="setting_printer-footer_value[]"
                        class="setting_printer-footer_value mb-2 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                        placeholder="Footer"
                        value="{{ old('setting_printer-footer_value', $item) }}" readonly>
                      <button type="button" onclick="delete_seetings_printer_footer({{$index}})" hidden
                        class="button_footer mb-2 w-fit justify-center rounded-lg bg-yellow-400 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300">
                        Hapus
                      </button>
                    </div>
                  @endforeach
                </div>
                <button type="button" onclick="add_settings_printer_footer({{count($setting_printer->footer->value)}})" hidden
                  class="button_footer mb-2 w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                  Tambahkan Baris Footer
                </button>
              </div>

              </div>
              @error('distance')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>
            @can('toko-pengaturan-perbarui') 
              <div id="frame_button_before_edit">
                <button type="button" onclick="edit_form()"
                  class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                  Perbarui
                </button>
              </div>
              <div id="frame_button_after_edit" hidden>
                <button type="submit"
                  class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                  Kirim
                </button>
                <button type="button" onclick="cancel_form()"
                  class="w-fit justify-center rounded-lg bg-yellow-400 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300">
                  Batalkan
                </button>
              </div>
            @endcan
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">

    function edit_form() {
      document.querySelector('#setting_printer-store_name_value').readOnly = false;
      document.querySelector('#setting_printer-store_name_show').disabled = false;
      document.querySelector('#setting_printer-store_name_value').classList.remove("bg-gray-200");
      document.querySelector('#setting_printer-store_name_value').classList.add("bg-gray-50");
      
      document.querySelector('#setting_printer-store_address_value').readOnly = false;
      document.querySelector('#setting_printer-store_address_show').disabled = false;
      document.querySelector('#setting_printer-store_address_value').classList.remove("bg-gray-200");
      document.querySelector('#setting_printer-store_address_value').classList.add("bg-gray-50");

      document.querySelector('#setting_printer-store_whatsapp_value').readOnly = false;
      document.querySelector('#setting_printer-store_whatsapp_show').disabled = false;
      document.querySelector('#setting_printer-store_whatsapp_value').classList.remove("bg-gray-200");
      document.querySelector('#setting_printer-store_whatsapp_value').classList.add("bg-gray-50");

      document.querySelector('#setting_printer-store_ig_value').readOnly = false;
      document.querySelector('#setting_printer-store_ig_show').disabled = false;
      document.querySelector('#setting_printer-store_ig_value').classList.remove("bg-gray-200");
      document.querySelector('#setting_printer-store_ig_value').classList.add("bg-gray-50");

      document.querySelectorAll('.setting_printer-footer_value').forEach(function(item) {
        item.readOnly = false;
        item.classList.remove("bg-gray-200");
        item.classList.add("bg-gray-50");
        item.classList.add("mr-2");
      });
      document.querySelectorAll('.button_footer').forEach(function(item) {
        item.hidden = false;
      });
      document.querySelector('#setting_printer-footer_show').disabled = false;

      document.querySelector('#frame_button_before_edit').hidden = true;
      document.querySelector('#frame_button_after_edit').hidden = false;
    }

    function cancel_form() {
      document.querySelector('#setting_printer-store_name_value').readOnly = true;
      document.querySelector('#setting_printer-store_name_show').disabled = true;
      document.querySelector('#setting_printer-store_name_value').classList.add("bg-gray-200");
      document.querySelector('#setting_printer-store_name_value').classList.remove("bg-gray-50");

      document.querySelector('#setting_printer-store_address_value').readOnly = true;
      document.querySelector('#setting_printer-store_address_show').disabled = true;
      document.querySelector('#setting_printer-store_address_value').classList.add("bg-gray-200");
      document.querySelector('#setting_printer-store_address_value').classList.remove("bg-gray-50");

      document.querySelector('#setting_printer-store_whatsapp_value').readOnly = true;
      document.querySelector('#setting_printer-store_whatsapp_show').disabled = true;
      document.querySelector('#setting_printer-store_whatsapp_value').classList.add("bg-gray-200");
      document.querySelector('#setting_printer-store_whatsapp_value').classList.remove("bg-gray-50");

      document.querySelector('#setting_printer-store_ig_value').readOnly = true;
      document.querySelector('#setting_printer-store_ig_show').disabled = true;
      document.querySelector('#setting_printer-store_ig_value').classList.add("bg-gray-200");
      document.querySelector('#setting_printer-store_ig_value').classList.remove("bg-gray-50");

      document.querySelectorAll('.setting_printer-footer_value').forEach(function(item) {
        item.readOnly = true;
        item.classList.add("bg-gray-200");
        item.classList.remove("bg-gray-50");
        item.classList.remove("mr-2");
      });
      document.querySelectorAll('.button_footer').forEach(function(item) {
        item.hidden = true;
      });
      document.querySelector('#setting_printer-footer_show').disabled = true;

      document.querySelector('#frame_button_before_edit').hidden = false;
      document.querySelector('#frame_button_after_edit').hidden = true;
    }

    let index_footer = null;
    function add_settings_printer_footer(intial_index) {
      if (!index_footer) {
        index_footer = intial_index;
      }
      
      $('#body_footer').append(`
        <div class="flex" id="setting_printer-footer_value-${index_footer}">
          <input type="text" name="setting_printer-footer_value[]"
            class="setting_printer-footer_value mb-2 mr-2 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
            placeholder="Footer"
            value="">
          <button type="button" onclick="delete_seetings_printer_footer(${index_footer})" 
            class="button_footer mb-2 w-fit justify-center rounded-lg bg-yellow-400 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300">
            Hapus
          </button>
        </div>
      `);

      index_footer += 1;
    }

    function delete_seetings_printer_footer(index) {
      $(`#setting_printer-footer_value-${index}`).remove();
    }
  </script>
@endpush
