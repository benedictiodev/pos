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
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Toko</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Profil</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Profil</h1>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800"
        role="alert">
        <span class="font-medium">{{ session('success') }}</span>
      </div>
    @endif
    @if (session('failed'))
      <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
        <span class="font-medium">{{ session('failed') }}</span>
      </div>
    @endif

    <div
      class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <form action="{{ route('dashboard.company.profile.update', ['id' => $data->id]) }}" method="POST"
          id="form_edit_profile" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Nama Toko</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nama Toko" value="{{ old('name', $data->name) }}" required readonly>
            </div>
            <div>
              <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900">Nomor Telfon</label>
              <input type="text" name="phone_number" id="phone_number"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nomor Telfon" value="{{ old('phone_number', $data->phone_number) }}" readonly>
            </div>
            <div>
              <label for="address" class="mb-2 block text-sm font-medium text-gray-900">Alamat</label>
              <textarea id="address" rows="4" name="address"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                placeholder="Alamat" readonly>{{ old('address', $data->address) }}</textarea>
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-900" for="image">Logo</label>
              <input type="text" value="{{ $data->image }}" name="old_image" hidden>
              <img id="preview-image"
                @if ($data->image) src="{{ asset(env('APP_ENV', 'production') == 'local' ? 'storage/' . $data->image :  $data->image) }}" alt="{{ asset($data->image) }}" @endif
                class="h-64 mb-2 block {{ $data->image ? '' : 'hidden' }}">
              <input
                class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-200 text-sm text-gray-900 focus:outline-none"
                id="image" type="file" accept="image/*" onchange="onChange()" name="image" disabled>
            </div>
            @can('toko-profil-perbarui')
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
                  class="w-fit justify-center rounded-lg bg-yellow-400 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-3000">
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
    const onChange = () => {
      const image = document.querySelector('#image');
      const previewImage = document.querySelector("#preview-image")
      console.log(image.files);
      const blob = URL.createObjectURL(image.files[0]);
      previewImage.src = blob;
      previewImage.classList.add("h-64")
      previewImage.classList.add("mb-2")
      previewImage.style.display = "block"
    }

    function edit_form() {
      const elements = document.querySelectorAll('#form_edit_profile input, #form_edit_profile textarea');
      elements.forEach(element => {
        console.log(element);
        element.readOnly = false;
        element.classList.remove("bg-gray-200");
        if (element.classList.contains("cursor-pointer")) {
          element.removeAttribute("disabled")
        }
      });
      document.querySelector('#frame_button_before_edit').hidden = true;
      document.querySelector('#frame_button_after_edit').hidden = false;
    }

    function cancel_form() {
      const name = "{{ $data->name }}";
      const phone_number = "{{ $data->phone_number }}";
      const address = "{{ $data->address }}";
      const image = "{{ $data->image }}";

      document.querySelector('#name').value = name;
      document.querySelector('#phone_number').value = phone_number;
      document.querySelector('#address').value = address;
      document.querySelector('#image').value = image;

      const elements = document.querySelectorAll('#form_edit_profile input, #form_edit_profile textarea');
      elements.forEach(element => {
        element.readOnly = true;
        element.classList.add("bg-gray-200");
        if (element.classList.contains("cursor-pointer")) {
          element.setAttribute("disabled", true)
          const previewImage = document.querySelector("#preview-image")
          previewImage.src = image;
          previewImage.classList.remove("h-64")
          previewImage.classList.remove("mb-2")
          previewImage.style.display = ""
        }
      });
      document.querySelector('#frame_button_before_edit').hidden = false;
      document.querySelector('#frame_button_after_edit').hidden = true;
    }
  </script>
@endpush
