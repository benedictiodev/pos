@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-1 w-full">
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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Presensi</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Presensi Pengguna</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Presensi Pengguna</h1>
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

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4 mx-auto text-center">
      @if ($user->presence->count() != 0)
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 flex flex-col gap-1" role="alert">
          <span class="font-medium text-lg">Kehadiran Anda hari ini telah dicatat.</span>
          <span class="text-base">Kehadiran pada {{ $user->presence[0]->created_at }}</span>
        </div>
      @else
        <div class="space-y-3">
          <p class="text-lg font-medium" id="text-permission"></p>
          <div id="presenceButton" class="hidden flex flex-col items-center justify-center gap-2">
            <p class="text-base font-light">Anda belum melakukan presensi hari ini?</p>
            <button type="button" data-drawer-target="drawer-presence-default" data-drawer-show="drawer-presence-default"
              aria-controls="drawer-presence-default" data-drawer-placement="right"
              class="w-fit inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
              <x-fas-user-pen class="mr-2 h-4 w-4" />
              Presensi Sekarang
            </button>
          </div>
          <div id="maps" class="h-96 mt-3"></div>
        </div>
      @endif
    </div>

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4 mx-auto text-center">
      <div id="calender"></div>
    </div>
  </div>

  <!-- Presence Drawer -->
  <div id="drawer-presence-default"
    class="fixed right-0 top-0 z-[99999] h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
    tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
    <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Presensi
    </h5>
    <button type="button" data-drawer-dismiss="drawer-presence-default" aria-controls="drawer-presence-default"
      class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
      <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
      <span class="sr-only">Close menu</span>
    </button>
    <form id="form-presence" method="POST" action="{{ route('dashboard.presence.presence_user_store') }}">
      @csrf
      <input type="text" id="presence-id" name="id" value="{{ Auth::user()->id }}" hidden>
      <x-fas-circle-exclamation class="mb-4 mt-8 h-10 w-10 text-red-600" />
      <h3 class="mb-6 text-lg text-gray-500" id="text-drawer">Apakah anda yakin untuk melakukan presensi ?</h3>
      <div id="button-drawer-submit">
        <button type="submit" data-type="button-presence"
          class="mr-2 inline-flex items-center rounded-lg bg-primary-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
          Ya, Saya Yakin
        </button>
      </div>
      <button type="button" id="button-drawer-cancel"
        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
        data-drawer-hide="drawer-presence-default">
        Tidak, Batalkan
      </button>
    </form>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    $(document).ready(function() {
      const setting_distance = {!! json_encode($setting->distance) !!}
      const setting_latitude = {!! json_encode($setting->latitude) !!}
      const setting_longitude = {!! json_encode($setting->longitude) !!}
      navigator.permissions.query({
        name: "geolocation"
      }).then((result) => {
        if (result.state == "granted") {
          navigator.geolocation.watchPosition(showPosition, showError, {
            enableHighAccuracy: true
          });
        } else if (result.state == "denied") {
          document.getElementById("text-permission").innerText =
            "Anda memblokir akses lokasi, Anda tidak dapat hadir! Buka pengaturan browser untuk mengizinkan akses lokasi Anda.";
        } else if (result.state == "prompt") {
          document.getElementById("text-permission").innerText =
            "Harap izinkan akses lokasi Anda untuk kehadiran.";
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
          } else {
            document.getElementById("location").innerText = "Geolokasi tidak didukung oleh browser ini.";
          }
        } else {
          document.getElementById("text-permission").innerText = `Error ${result.state}`
        }
        // granted
        // denied
        // prompt
      })

      function showPosition(position) {
        document.getElementById("text-permission").classList.add("hidden")
        $('#presenceButton').removeClass("hidden");
        $('#presenceButton').addClass("inline-flex");
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        const coord1 = {
          latitude: setting_latitude,
          longitude: setting_longitude
        }; // Store cc
        const coord2 = {
          latitude: latitude,
          longitude: longitude
        }; // User

        const maps = L.map('maps').setView([latitude, longitude], 19);
        const googleStreet = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
          maxZoom: 20,
          subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
          attribution: '&copy; <a href="http://www.maps.google.com/">Google Maps</a>'
        }).addTo(maps);
        layerGroup = L.layerGroup().addTo(maps);

        L.marker({
          lat: latitude,
          lng: longitude
        }).bindTooltip("User", {
          permanent: true,
          direction: 'top'
        }).addTo(layerGroup);

        L.marker({
          lat: setting_latitude,
          lng: setting_longitude
        }).bindTooltip("Workplace", {
          permanent: true,
          direction: 'top'
        }).addTo(layerGroup);

        L.circle([setting_latitude, setting_longitude], setting_distance).addTo(layerGroup);

        const distance = haversineDistance(coord1, coord2);

        if (distance >= setting_distance) {
          $('#text-drawer').html(`Jarak maksimum dari toko adalah ${setting_distance} meter.`);
          $('#button-drawer-submit').attr('hidden', true);
          $('#button-drawer-cancel').html('Close');
        } else {
          $('#text-drawer').html('Apakah anda yakin untuk melakukan presensi ?');
          $('#button-drawer-submit').attr('hidden', false);
          $('#button-drawer-cancel').html('Tidak, Batalkan');
        }
      }

      function showError(error) {
        $('#button-drawer-submit').attr('hidden', true);
        $('#button-drawer-cancel').html('Close');
        switch (error.code) {
          case error.PERMISSION_DENIED:
            $('#text-drawer').html('Pengguna menolak permintaan Geolokasi.');
            break;
          case error.POSITION_UNAVAILABLE:
            $('#text-drawer').html('Informasi lokasi tidak tersedia.');
            document.getElementById("location").innerHTML = "";
            break;
          case error.TIMEOUT:
            $('#text-drawer').html('Waktu permintaan untuk mendapatkan lokasi pengguna telah habis.');
            break;
          case error.UNKNOWN_ERROR:
            $('#text-drawer').html('Terjadi kesalahan yang tidak diketahui.');
            break;
        }
      }

      const history = {!! json_encode($history) !!}

      let events = [];
      history?.presence?.forEach(item => {
        events.push({
          id: item?.created_at,
          start: item?.created_at,
          color: "#bfdbfe",
          className: ["bg-primary-200", "hover:bg-primary-100", "overflow-x-auto", ],
        });
      });

      const calenderElement = document.getElementById('calender');
      const calendar = new FullCalendar.Calendar(calenderElement, {
        headerToolbar: {
          start: 'today', // will normally be on the left. if RTL, will be on the right
          center: 'title',
          end: 'prev,next' // will normally be on the right. if RTL, will be on the left
        },
        initialView: 'dayGridMonth',
        allDaySlot: false,
        height: 600,
        events,
        // displayEventTime: false,
        eventTimeFormat: { // like '14:30:00'
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          meridiem: false,
          hour12: false
        },
      });
      calendar.render();
    });

    function toRadians(degrees) {
      return degrees * (Math.PI / 180);
    }

    function haversineDistance(coord1, coord2) {
      const R = 6371; // Radius bumi dalam kilometer
      const lat1 = toRadians(coord1.latitude);
      const lon1 = toRadians(coord1.longitude);
      const lat2 = toRadians(coord2.latitude);
      const lon2 = toRadians(coord2.longitude);

      const dLat = lat2 - lat1;
      const dLon = lon2 - lon1;

      const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1) * Math.cos(lat2) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

      const distance = (R * c) * 1000;
      return distance;
    }
  </script>
@endpush
