<x-app-layout>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce

  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Edit Lokasi') }}
    </h2>
  </x-slot>

  <div class="py-4 sm:py-6 md:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <div class="p-4 sm:p-6 lg:p-8">
          <form action="{{ route('admin.location-settings.update', $locationSetting->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="w-full">
              <x-label for="name">Nama Lokasi</x-label>
              <x-input name="name" id="name" class="mt-1 block w-full" type="text"
                :value="old('name', $locationSetting->name)" placeholder="Kantor Pusat" required />
              @error('name')
                <x-input-error for="name" class="mt-2" message="{{ $message }}" />
              @enderror
            </div>

            <div class="mt-4 flex gap-3">
              <div class="w-full">
                <x-label for="radius">Radius Valid Absen (meter)</x-label>
                <x-input name="radius" id="radius" class="mt-1 block w-full" type="number"
                  :value="old('radius', $locationSetting->radius)" placeholder="50" min="1" required />
                @error('radius')
                  <x-input-error for="radius" class="mt-2" message="{{ $message }}" />
                @enderror
              </div>
              <div class="w-full flex items-end">
                <label class="flex items-center">
                  <input type="checkbox" name="is_active" value="1" {{ old('is_active', $locationSetting->is_active) ? 'checked' : '' }} class="mr-2">
                  <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                </label>
              </div>
            </div>

            <div class="mt-5">
              <h3 class="text-lg font-semibold dark:text-gray-400">{{ __('Koordinat') }}</h3>

              <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="w-full">
                  <x-label for="latitude">Latitude</x-label>
                  <x-input name="latitude" id="latitude" class="mt-1 block w-full" type="text"
                    :value="old('latitude', $locationSetting->latitude)" placeholder="cth: -6.12345" required />
                  @error('latitude')
                    <x-input-error for="latitude" class="mt-2" message="{{ $message }}" />
                  @enderror
                </div>
                <div class="w-full">
                  <x-label for="longitude">Longitude</x-label>
                  <x-input name="longitude" id="longitude" class="mt-1 block w-full" type="text"
                    :value="old('longitude', $locationSetting->longitude)" placeholder="cth: 106.12345" required />
                  @error('longitude')
                    <x-input-error for="longitude" class="mt-2" message="{{ $message }}" />
                  @enderror
                </div>
              </div>

              <div class="mt-4">
                <x-button type="button" onclick="toggleMap()" class="text-nowrap">
                  <x-heroicon-s-map-pin class="mr-2 h-5 w-5" /> Tampilkan/Sembunyikan Peta
                </x-button>
              </div>

              <div id="map" class="my-6 h-72 w-full md:h-96" style="display: none;"></div>

              <div class="mb-3 mt-4 flex items-center justify-end">
                <x-secondary-button type="button" onclick="window.history.back()" class="me-2">
                  {{ __('Cancel') }}
                </x-secondary-button>
                <x-button class="ms-4">
                  {{ __('Save') }}
                </x-button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      let mapInstance = null;
      let mapElement = document.getElementById('map');
      let isMapInitialized = false;

      const defaultLocation = @if (old('latitude') && old('longitude'))
        [Number({{ old('latitude') }}), Number({{ old('longitude') }})]
      @else
        [Number({{ $locationSetting->latitude }}), Number({{ $locationSetting->longitude }})]
      @endif;

      function initializeMapIfNeeded() {
        if (!isMapInitialized && mapElement.style.display !== 'none') {
          mapInstance = L.map('map').setView(defaultLocation, 13);

          L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 21,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(mapInstance);

          let marker = L.marker(defaultLocation, {
            draggable: true,
          }).addTo(mapInstance);

          marker.on('dragend', function (event) {
            let position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
          });

          mapInstance.on('click', function (e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
          });

          isMapInitialized = true;
        }
      }

      function toggleMap() {
        const isHidden = mapElement.style.display === "none";
        mapElement.style.display = isHidden ? "block" : "none";
        
        if (isHidden) {
          setTimeout(() => {
            initializeMapIfNeeded();
            if (mapInstance) {
              mapInstance.invalidateSize();
            }
          }, 100);
        }
      }

      // Initialize map on load since we have coordinates
      window.addEventListener("load", function() {
        setTimeout(() => {
          mapElement.style.display = 'block';
          initializeMapIfNeeded();
        }, 500);
      });
    </script>
  @endPushOnce
</x-app-layout>

