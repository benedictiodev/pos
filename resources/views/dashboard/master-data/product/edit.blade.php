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
                Produk</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Pembaruan Produk</h1>
      <a href="{{ route('dashboard.master-data.product') }}"
        class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Kembali
      </a>
    </div>

    <div
      class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6 mb-4">
      <div>
        <form action="{{ route('dashboard.master-data.product.update', ['id' => $data->id]) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Nama
                Produk</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nama Produk" required value="{{ old('name', $data->name) }}">
            </div>

            <div>
              <label for="price" class="mb-2 block text-sm font-medium text-gray-900">Harga
                Produk</label>
              <input type="text" name="price" id="price"
                onkeyup="keyup_rupiah(this)"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Harga Produk" required value="{{ old('price', $data->price) }}">
            </div>

            <div>
              <label for="category-create" class="mb-2 block text-sm font-medium text-gray-900">Kategori
                Produk</label>
              <select id="category-update" name="category_id"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                required>
                <option disabled value="">Pilih Kategori Produk</option>
                @foreach ($lists as $item)
                  <option value="{{ $item->id }}"
                    {{ old('category_id', $item->category_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="description"
                class="mb-2 block text-sm font-medium text-gray-900">Deskripsi</label>
              <textarea id="description" rows="4" name="description"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                placeholder="Deskripsi">{{ old('description', $data->description) }}</textarea>
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-900" for="create-image">Unggah Foto Produk</label>
              <input type="text" value="{{ $data->image }}" name="old_image" hidden>
              @if ($data->image)
                <img id="preview-image" src="{{ asset(env('APP_ENV', 'production') == 'local' ? 'storage/' . $data->image : $data->image) }}" alt="{{ asset($data->image) }}"
                  class="h-64 mb-2 block">
              @endif
              <input
                class="block w-full cursor-pointer rounded-lg border border-gray-300 text-sm text-gray-900 focus:outline-none"
                id="create-image" type="file" accept="image/*" onchange="onChange()" name="image">
            </div>

            <div class="flex items-center">
              <input id="is_available" aria-describedby="checkbox-1" type="checkbox" name="is_available" value="1"
                {{ $data->is_available == 1 ? 'checked' : '' }}
                class="focus:ring-3 h-4 w-4 rounded border-gray-300 focus:ring-primary-300">
              <label for="is_available" class="sr-only">checkbox</label>
              <p class="ml-2 text-sm font-medium text-gray-900">Produk Tersedia
              </p>
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
  <script type="text/javascript">
    const onChange = () => {
      const image = document.querySelector('#create-image');
      const previewImage = document.querySelector("#preview-image")
      console.log(image.files);
      const blob = URL.createObjectURL(image.files[0]);
      previewImage.src = blob;
    }
  </script>
@endpush
