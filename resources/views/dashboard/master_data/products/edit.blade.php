@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-4">
      <nav class="mb-5 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
          <li class="inline-flex items-center">
            <a href="#"
              class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
              Dashboard
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 dark:text-gray-300 md:ml-2" aria-current="page">Master
                Data</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2" aria-current="page">
                Products</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-4">Update Product</h1>
      <a href="{{ route('dashboard.master-data.products') }}" type="button"
        class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300 dark:bg-slate-600 dark:hover:bg-slate-400 dark:focus:ring-slate-800">
        Back
      </a>
    </div>

    <div
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 mb-4">
      <div>
        <form action="{{ route('dashboard.master-data.products.update', ['id' => $data->id]) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Name
                Product</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Name Product" required value="{{ old('name', $data->name) }}">
            </div>

            <div>
              <label for="price" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Price
                Product</label>
              <input type="number" name="price" id="price"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Price Product" required value="{{ old('price', $data->price) }}">
            </div>

            <div>
              <label for="category-create" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Category
                Product</label>
              <select id="category-update" name="category_id"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                required>
                <option disabled value="">Select Category Product</option>
                @foreach ($lists as $item)
                  <option value="{{ $item->id }}"
                    {{ old('category_id', $item->category_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="description"
                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Description</label>
              <textarea id="description" rows="4" name="description"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder="Enter event description here" required>{{ old('description', $data->description) }}</textarea>
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="create-image">Upload
                file</label>
              <input type="text" value="{{ $data->image }}" name="old_image" hidden>
              <img id="preview-image" src="{{ asset('storage/' . $data->image) }}" alt="{{ asset($data->image) }}"
                class="h-64 mb-2 block">
              <input
                class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
                id="create-image" type="file" accept="image/*" onchange="onChange()" name="image">
            </div>

            <div class="flex items-center">
              <input id="is_available" aria-describedby="checkbox-1" type="checkbox" name="is_available" value="1"
                {{ $data->is_available == 1 ? 'checked' : '' }}
                class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600">
              <label for="is_available" class="sr-only">checkbox</label>
              <p class="ml-2 text-sm font-medium text-gray-900 dark:text-white">Is Available
              </p>
            </div>

            <button type="submit"
              class="w-fit justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
              Update
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
