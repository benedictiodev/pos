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
        <form action="{{ route('dashboard.company.setting.update', $data) }}" method="POST" id="form_edit_setting">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <label for="distance" class="mb-2 block text-sm font-medium text-gray-900">Jarak maksimal untuk kehadiran
                pengguna (Meter)</label>
              <input type="text" name="distance" id="distance"
                class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Jarak maksimal untuk kehadiran pengguna (Meter)"
                value="{{ old('distance', $data->distance) }}" readonly>
              @error('distance')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="location" class="mb-2 block text-sm font-medium text-gray-900">Lokasi</label>
              <p id="error-location-text"></p>
              <div id="maps" class="h-96"></div>
              <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $data->latitude) }}" hidden>
              <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $data->longitude) }}"
                hidden>
              <p class="mt-2 text-sm text-green-600 font-medium" id="help-text-map" hidden>
                Klik lokasi Anda di peta.
              </p>
              @error('latitude')
                <p class="mt-2 text-sm text-red-600"><span class="font-medium">{{ $message }}</span></p>
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
    let maps;
    let layerGroup;
    let grandted = false;
    const lat = document.querySelector('#latitude').value;
    const lng = document.querySelector('#longitude').value;

    const inputLatitude = document.getElementById('latitude');
    const inputLongitude = document.getElementById('longitude');

    navigator.permissions.query({
      name: "geolocation"
    }).then((result) => {
      if (result.state == "granted") {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
      } else if (result.state == "denied") {
        document.getElementById("error-location-text").innerText =
          "Anda memblokir akses lokasi, Anda tidak dapat mengatur kehadiran pengguna! Buka pengaturan browser untuk mengizinkan akses lokasi Anda.";
      } else if (result.state == "prompt") {
        document.getElementById("error-location-text").innerText =
          "Harap izinkan akses lokasi Anda ke kehadiran pengguna.";
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
          document.getElementById("location").innerText = "Geolokasi tidak didukung oleh browser ini.";
        }
      } else {
        document.getElementById("error-location-text").innerText = `Error ${result.state}`
      }
      // granted
      // denied
      // prompt
    })

    function showPosition(position) {
      $('#error-location-text').addClass('hidden');
      grandted = true;
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;
      if (lat && lng) {
        maps = L.map('maps').setView([lat, lng], 19);
      } else {
        maps = L.map('maps').setView([latitude, longitude], 19);
      }
      const googleStreet = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; <a href="http://www.maps.google.com/">Google Maps</a>'
      }).addTo(maps);
      layerGroup = L.layerGroup().addTo(maps);
      if (lat && lng) {
        L.marker({
          lat,
          lng
        }).addTo(layerGroup);
      }
    }

    function showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          $('#error-location-text').html('Pengguna menolak permintaan Geolokasi.');
          break;
        case error.POSITION_UNAVAILABLE:
          $('#error-location-text').html('Informasi lokasi tidak tersedia.');
          document.getElementById("location").innerHTML = "";
          break;
        case error.TIMEOUT:
          $('#error-location-text').html('Waktu permintaan untuk mendapatkan lokasi pengguna telah habis.');
          break;
        case error.UNKNOWN_ERROR:
          $('#error-location-text').html('Terjadi kesalahan yang tidak diketahui.');
          break;
      }
    }

    function edit_form() {
      if (grandted) {
        maps.on('click', function(e) {
          layerGroup.clearLayers();
          L.marker(e.latlng).addTo(layerGroup);

          inputLatitude.value = e?.latlng.lat
          inputLongitude.value = e?.latlng.lng
        });
      }
      document.querySelector('#help-text-map').hidden = false;
      document.querySelector('#distance').readOnly = false;
      document.querySelector('#distance').classList.remove("bg-gray-200");
      document.querySelector('#distance').classList.add("bg-gray-50");

      document.querySelector('#frame_button_before_edit').hidden = true;
      document.querySelector('#frame_button_after_edit').hidden = false;
    }

    function cancel_form() {
      if (grandted) {

        maps.off('click');
        layerGroup.clearLayers();
        layerGroup = L.layerGroup().addTo(maps);
        if (lat && lng) {
          L.marker({
            lat,
            lng
          }).addTo(layerGroup);
        }

        inputLatitude.value = ""
        inputLongitude.value = ""
      }

      document.querySelector('#help-text-map').hidden = true;
      document.querySelector('#distance').readOnly = true;
      document.querySelector('#distance').classList.add("bg-gray-200");
      document.querySelector('#distance').classList.remove("bg-gray-50");

      document.querySelector('#frame_button_before_edit').hidden = false;
      document.querySelector('#frame_button_after_edit').hidden = true;
    }
  </script>
@endpush
