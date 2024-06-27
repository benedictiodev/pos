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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Presence</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">User Presence</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">User Presence</h1>
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
          <span class="font-medium text-lg">Your attendance today has been recorded.</span>
          <span class="text-base">Attendance on {{ $user->presence[0]->created_at }}</span>
        </div>
      @else
        <div class="space-y-3">
          <p class="text-lg font-medium" id="text-permission"></p>
          <div id="presenceButton" class="hidden flex flex-col items-center justify-center gap-2">
            <p class="text-base font-light">You haven't done your attendance today?</p>
            <button type="button" data-drawer-target="drawer-presence-default" data-drawer-show="drawer-presence-default"
              aria-controls="drawer-presence-default" data-drawer-placement="right"
              class="w-fit inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
              <x-fas-user-pen class="mr-2 h-4 w-4" />
              Presence Now
            </button>
          </div>
        </div>
      @endif
    </div>

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4 mx-auto text-center">
      <div id="calender"></div>
    </div>
  </div>

  <!-- Presence Drawer -->
  <div id="drawer-presence-default"
    class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
    tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
    <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Presence
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
      <h3 class="mb-6 text-lg text-gray-500" id="text-drawer">Are you sure you want to presence ?</h3>
      <div id="button-drawer-submit">
        <button type="submit" data-type="button-presence"
          class="mr-2 inline-flex items-center rounded-lg bg-primary-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
          Yes, I'm sure
        </button>
      </div>
      <button type="button" id="button-drawer-cancel"
        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
        data-drawer-hide="drawer-presence-default">
        No, cancel
      </button>
    </form>
  </div>
@endsection

@push('script')
  <script>
    $(document).ready(function() {
      navigator.permissions.query({
        name: "geolocation"
      }).then((result) => {
        if (result.state == "granted") {
          navigator.geolocation.getCurrentPosition(showPosition, showError);
          document.getElementById("text-permission").classList.add("hidden")
        }
        if (result.state == "denied") {
          document.getElementById("text-permission").innerText =
            "You block location access, you cant attendace! Open setting browser to allow your location access.";
        }
        if (result.state == "prompt") {
          document.getElementById("text-permission").innerText =
            "Please allow your location access to attendance.";
          if (navigator.geolocation) {
            document.getElementById("text-permission").classList.add("hidden")
            navigator.geolocation.getCurrentPosition(showPosition, showError);
          } else {
            document.getElementById("location").innerText = "Geolocation is not supported by this browser.";
          }
        }
        // granted
        // denied
        // prompt
      })

      function showPosition(position) {
        $('#presenceButton').removeClass("hidden");
        $('#presenceButton').addClass("inline-flex");
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        const coord1 = {
          latitude: -7.0031951,
          longitude: 107.621522
        }; // Store cc
        const coord2 = {
          latitude: latitude,
          longitude: longitude
        }; // User

        const distance = haversineDistance(coord1, coord2);

        if (distance >= 15) {
          $('#text-drawer').html('The maximum distance from the shop is 15 meters.');
          $('#button-drawer-submit').attr('hidden', true);
          $('#button-drawer-cancel').html('Close');
        } else {
          $('#text-drawer').html('Are you sure you want to presence ?');
          $('#button-drawer-submit').attr('hidden', false);
          $('#button-drawer-cancel').html('No, cancel');
        }
      }

      function showError(error) {
        $('#button-drawer-submit').attr('hidden', true);
        $('#button-drawer-cancel').html('Close');
        switch (error.code) {
          case error.PERMISSION_DENIED:
            $('#text-drawer').html('User denied the request for Geolocation.');
            break;
          case error.POSITION_UNAVAILABLE:
            $('#text-drawer').html('Location information is unavailable.');
            document.getElementById("location").innerHTML = "";
            break;
          case error.TIMEOUT:
            $('#text-drawer').html('The request to get user location timed out.');
            break;
          case error.UNKNOWN_ERROR:
            $('#text-drawer').html('An unknown error occurred.');
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
