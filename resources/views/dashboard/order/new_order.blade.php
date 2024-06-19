@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-1 w-full">
      <div class="mb-4">
        <nav class="mb-5 flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
            <li class="inline-flex items-center">
              <a href="#"
                class="inline-flex items-center text-gray-700 hover:text-primary-600">
                Dashboard
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                  Order
                </span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Add New Order</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Add New Order</h1>
        <a href="{{ route('dashboard.order.order_active') }}"
          class="w-fit justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
          Back
        </a>
      </div>
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
      class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 sm:p-6 mb-4">
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="flex">
              <div class="w-2/5 border mr-3 rounded-xl max-h-[450px]">
                <div class="h-5/6 overflow-auto">
                  <div class="mx-2 px-2 py-3 flex border-b font-semibold">
                    <div class="w-3/6">Menu</div>
                    <div class="w-1/6 text-center">Qty</div>
                    <div class="w-2/6 text-right">Price</div>
                  </div>
                  <div id="body-order-item"></div>
                </div>
                <div class="h-1/6 bg-[#E5E7EB] rounded-b-lg flex flex-col justify-center items-center px-4">
                  <div class="flex justify-between w-full font-semibold text-sm mb-2">
                    <div>Total Price</div>
                    <div id="order-total-price"></div>
                  </div>
                  <button data-modal-target="modal-order" data-modal-toggle="modal-order"
                    onclick="open_modal_confirm_order()"
                    class="mx-4 w-full justify-center rounded-lg bg-primary-700 py-1.5 text-center text-xs font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                    Order Now
                  </button>
                  <button id="button-trigger-modal" data-modal-target="modal-add-to-cart"
                    data-modal-toggle="modal-add-to-cart" class="hidden"></button>
                </div>
              </div>
              <div class="w-3/5 border ml-3 rounded-xl max-h-[450px] overflow-auto">
                <div class="p-2 text-center text-lg font-semibold bg-[#E5E7EB] rounded-t-lg border-b-2 border-white">List
                  Menu</div>
                <div>
                  @foreach ($list_menu as $category)
                    <div>
                      <div class="bg-[#E5E7EB] py-1 px-2">Category : {{ $category->category_name }}</div>
                      <div class="grid grid-cols-4 gap-2 px-1 py-2">
                        @foreach ($category->products as $item)
                          <div class="mx-1 border-2 p-2 rounded-lg h-full relative pb-20">
                            <div class="border p-2 rounded-md flex items-center justify-center h-32 max-h-32">
                              <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="h-full">
                            </div>
                            <div class="mt-2 ">
                              <div class="font-medium">{{ $item->name }}</div>
                              <div class="font-thin text-sm">{{ $item->description }}</div>
                            </div>
                            <div class="absolute w-full bottom-[8px] pr-4">
                              <div class="text-right mt-2 text-sm font-bold">{{ format_rupiah($item->price) }}</div>
                              @if ($item->is_available)
                                <button data-modal-target="modal-add-to-cart" data-modal-toggle="modal-add-to-cart"
                                  onclick="add_new_order({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})"
                                  class="mt-2 w-full justify-center rounded-lg bg-primary-700 py-1.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                                  Add to cart
                                </button>
                              @else 
                                <button
                                  class="mt-2 w-full justify-center rounded-lg bg-primary-500 py-1.5 text-center text-sm font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-4 focus:ring-primary-200">
                                  Not Available
                                </button>
                              @endif
                            </div>
                            @if (!$item->is_available)
                              <div class="rounded-lg absolute top-0 bottom-0 right-0 left-0 bg-gray-400/70 flex justify-center items-center">
                                <div class="text-white font-bold text-center">
                                  <div class="text-lg">Oppss!!</div>
                                  <div class="text-sm">Not Available</div>
                                </div>
                              </div>
                            @endif
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <button id="trigger-drawer-confirm_order" data-drawer-target="drawer-confirm_order"
      data-drawer-show="drawer-confirm_order" aria-controls="drawer-confirm_order" data-drawer-placement="right"
      class="hidden">
    </button>
    <div id="drawer-confirm_order"
      class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
      tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
      <h5 id="drawer-label"
        class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Confirm Order
      </h5>
      <button type="button" data-drawer-dismiss="drawer-confirm_order" aria-controls="drawer-confirm_order"
        class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
        <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
        <span class="sr-only">Close menu</span>
      </button>

      <x-fas-circle-exclamation id="icon_fas-circle-exclamation" class="mb-4 mt-8 h-10 w-10 text-gray-400" />
      <h3 id="header-drawer" class="mb-3 text-lg text-gray-500">Are you sure you want to confirm this
        order?</h3>
      <button id="button-drawer-confirm" type="submit" data-type="button-confirm_order"
        class="mr-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300"
        data-drawer-hide="drawer-confirm_order" form="form-confirm_order">
        Yes, I'm sure
      </button>
      <button id="button-drawer-close" type="button"
        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
        data-drawer-hide="drawer-confirm_order">
        No, cancel
      </button>
    </div>

    <!-- modal add to cart -->
    <div id="modal-add-to-cart" tabindex="-1" aria-hidden="true"
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
            <h3 class="text-xl font-semibold text-gray-900">
              <span id="modal-add-to-cart-title">Add</span> To Cart
            </h3>
            <button type="button"
              class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
              data-modal-hide="modal-add-to-cart">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="p-4 md:p-5 space-y-4">
            <input type="text" id="order-sequence" value="" hidden>
            <input type="text" id="order-product_id" value="" hidden>
            <input type="text" id="order-product_price" value="" hidden>
            <div class="mb-3">
              <label for="order-product_name"
                class="mb-2 block text-sm font-medium text-gray-900">Product Name</label>
              <input type="text" name="order-product_name" id="order-product_name"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Product Name" readonly>
            </div>
            <div class="mb-3">
              <label for="order-qty" class="mb-2 block text-sm font-medium text-gray-900">Qty</label>
              <input type="number" min="1" name="order-qty" id="order-qty"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Qty" value="1" required>
            </div>
            <div>
              <label for="order-remarks"
                class="mb-2 block text-sm font-medium text-gray-900">Remarks</label>
              <textarea id="order-remarks" rows="4" name="order-remarks"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                placeholder="Enter event remarks here"></textarea>
            </div>
            <p class="text-base leading-relaxed text-gray-500">
              Are you sure you want to <span id="modal-add-to-cart-text">add</span> this order?
            </p>
          </div>
          <!-- Modal footer -->
          <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button onclick="set_order_to_cart()" data-modal-hide="modal-add-to-cart" type="button"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Yes,
              I'm suret</button>
            <button data-modal-hide="modal-add-to-cart" type="button"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">No,
              Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal order -->
    <div id="modal-order" tabindex="-1" aria-hidden="true"
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
            <h3 class="text-xl font-semibold text-gray-900">
              Confirm Order
            </h3>
            <button type="button"
              class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
              data-modal-hide="modal-order">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="p-4 md:p-5 space-y-4">
            <div id="false-confirm_order">
              <div class="text-center text-red-500 font-bold">Oppss !!!</div>
              <div class="text-center text-sm font-semi text-red-400">
                Somethink is wrong, please Add Product To Cart
              </div>
            </div>
            <form id="form-confirm_order" action="{{ route('dashboard.order.order_active.post_new_order') }}"
              method="POST" hidden>
              @csrf
              <input type="text" name="confirm_order-order" id="confirm_order-order" hidden>
              <div class="mb-3">
                <label for="confirm_order-customer_name"
                  class="mb-2 block text-sm font-medium text-gray-900">Customer Name</label>
                <input type="text" name="confirm_order-customer_name" id="confirm_order-customer_name"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                  placeholder="Customer Name" required>
              </div>
              <div class="mb-3">
                <label for="confirm_order-remarks"
                  class="mb-2 block text-sm font-medium text-gray-900">Remarks</label>
                <textarea id="confirm_order-remarks" rows="2" name="confirm_order-remarks"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                  placeholder="Enter event remarks here"></textarea>
              </div>
              <div class="mb-3">
                <label for="confirm_order-order_type"
                  class="mb-2 block text-sm font-medium text-gray-900">Order Type</label>
                <select id="confirm_order-order_type" name="confirm_order-order_type"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                  required>
                  <option disabled value="">Select Order Type</option>
                  <option value="dine_in">Dine In</option>
                  <option value="take_away">Take Away</option>
                </select>
              </div>
              <div class="flex items-center mb-3">
                <input name="confirm_order-pay_now" id="confirm_order-pay_now" type="checkbox" value="check"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                <label for="confirm_order-pay_now" class="ms-2 text-sm font-medium text-gray-900">Pay
                  Now</label>
              </div>
              <div id="payment_form" class="border pl-4 pr-2 py-4 rounded-lg relative" hidden>
                <div class="absolute top-[-11px] left-0 right-0 flex justify-center">
                  <div class="bg-white px-4 text-sm font-semibold">Payment Form</div>
                </div>
                <div class="mb-3">
                  <label for="confirm_order-payment_method"
                    class="mb-2 block text-sm font-medium text-gray-900">Type Payment</label>
                  <select id="confirm_order-payment_method" name="confirm_order-payment_method"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500">
                    <option disabled value="">Select Type Payment</option>
                    @foreach ($list_fund as $item_fund)
                      <option value="{{ $item_fund->type }}">{{ $item_fund->type }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="confirm_order-total_payment"
                    class="mb-2 block text-sm font-medium text-gray-900">Total Payment</label>
                  <input type="number" min="0" name="confirm_order-total_payment"
                    id="confirm_order-total_payment"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Total Payment" readonly>
                </div>
                <div class="mb-3">
                  <label for="confirm_order-payment"
                    class="mb-2 block text-sm font-medium text-gray-900">Payment</label>
                  <input type="number" min="0" name="confirm_order-payment" id="confirm_order-payment"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Payment" onkeyup="count_change_payment()">
                </div>
                <div class="">
                  <label for="confirm_order-change"
                    class="mb-2 block text-sm font-medium text-gray-900">Change</label>
                  <input type="number" min="0" name="confirm_order-change" id="confirm_order-change"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Change" readonly>
                </div>
              </div>
            </form>
          </div>
          <!-- Modal footer -->
          <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button onclick="submit_order()" id="button-confirm_order" hidden type="button"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Confirm
              Order</button>
            <button id="button-close_order" data-modal-hide="modal-order" type="button"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    let data_order = [],
      sequence = 0;

    $(document).ready(function() {
      draw_order_item();
    });

    const draw_order_item = () => {
      $('#body-order-item').html("");
      let total_price_order = 0;
      data_order.forEach((item, index) => {
        let price_item = item.qty * item.product_price;
        total_price_order += price_item;
        $('#body-order-item').append(`
          <div class="mx-2 px-2 py-2 flex border-b text-sm relative" onmouseover="hover_order_item(${item.sequence})" onmouseout="setout_order_item(${item.sequence})">
            <div class="w-3/6">
              <div>${index + 1}. ${item.product_name}</div>
              <div class="text-xs font-thin">catatan : ${item.remarks == '' ? '-' : item.remarks}</div>
            </div>
            <div class="w-1/6 text-center">${item.qty}</div>
            <div class="w-2/6 text-right">${format_rupiah(price_item)}</div>
            <div id="container-update-order-${item.sequence}" class="hidden rounded-lg absolute justify-center items-center top-0 bottom-0 left-0 right-0 bg-gray-200/60">
              <button
                
                onclick="update_order_from_chart(${item.sequence})"
                class="mr-2 inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-center text-xs text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300">
                <x-fas-edit class="mr-2 h-4 w-4" />
                Update
              </button>
              <button type="button"
                onclick="delete_order_from_chart(${item.sequence})"
                class="ml-2 inline-flex items-center rounded-lg bg-red-700 px-3 py-2 text-center text-xs text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300">
                <x-fas-trash-alt class="mr-2 h-4 w-4" />
                Delete
              </button>
            </div>
          </div>
        `);
      })

      $('#confirm_order-total_payment').val(total_price_order);
      $('#confirm_order-payment').val(0);
      $('#confirm_order-change').val(0);
      $('#order-total-price').html(format_rupiah(total_price_order));
    }

    const hover_order_item = (sequence_id) => {
      $(`#container-update-order-${sequence_id}`).removeClass('hidden').addClass('flex');
    }

    const setout_order_item = (sequence_id) => {
      $(`#container-update-order-${sequence_id}`).removeClass('flex').addClass('hidden');
    }

    const delete_order_from_chart = (sequence_id) => {
      data_order = data_order.filter(item => item.sequence != sequence_id);
      draw_order_item();
    }

    const update_order_from_chart = (sequence_id) => {
      $('#modal-add-to-cart-title').html('Update');
      $('#modal-add-to-cart-text').html('update');

      let item = data_order.find(data => data.sequence == sequence_id);
      $('#button-trigger-modal').trigger("click");

      $('#order-sequence').val(item.sequence);
      $('#order-product_id').val(item.product_id);
      $('#order-product_price').val(item.product_price);
      $('#order-product_name').val(item.product_name);
      $('#order-qty').val(item.qty);
      $('#order-remarks').val(item.remarks);
    }

    const add_new_order = (id, name, price) => {
      $('#modal-add-to-cart-title').html('Add');
      $('#modal-add-to-cart-text').html('add');

      $('#order-sequence').val('');
      $('#order-product_id').val(id);
      $('#order-product_price').val(price);
      $('#order-product_name').val(name);
      $('#order-qty').val(1);
      $('#order-remarks').val("");
    }

    const set_order_to_cart = () => {
      let data_sequence = $('#order-sequence').val();
      let product_id = $('#order-product_id').val();
      let product_price = $('#order-product_price').val();
      let product_name = $('#order-product_name').val();
      let qty = $('#order-qty').val();
      let remarks = $('#order-remarks').val();

      if (data_sequence !== '') {
        let index = data_order.findIndex(item => item.sequence == data_sequence);
        data_order[index] = {
          product_id,
          product_price,
          product_name,
          qty,
          remarks,
          sequence: data_sequence
        }
      } else {
        data_order.push({
          product_id,
          product_price,
          product_name,
          qty,
          remarks,
          sequence
        });

        sequence += 1;
      }
      draw_order_item();
    }

    const open_modal_confirm_order = () => {
      if (data_order.length > 0) {
        $('#button-confirm_order').attr('hidden', false);
        $('#form-confirm_order').attr('hidden', false);
        $('#false-confirm_order').attr('hidden', true);
      } else {
        $('#button-confirm_order').attr('hidden', true);
        $('#form-confirm_order').attr('hidden', true);
        $('#false-confirm_order').attr('hidden', false);
      }
    }

    $('#confirm_order-pay_now').on('click', function() {
      if (this.checked) {
        $('#payment_form').attr('hidden', false);
      } else {
        $('#payment_form').attr('hidden', true);
      }
    });

    const count_change_payment = () => {
      let total_payment = $('#confirm_order-total_payment').val();
      let payment = $('#confirm_order-payment').val();
      $('#confirm_order-change').val(Number(payment) - Number(total_payment));
    };

    const submit_order = () => {
      let customer_name = $('#confirm_order-customer_name').val();
      let pay_now = $('#confirm_order-pay_now').is(":checked");
      let condition_success = true,
        message = '';
      if (customer_name && customer_name != '') {
        if (pay_now) {
          let total_payment = Number($('#confirm_order-total_payment').val());
          let payment = Number($('#confirm_order-payment').val());
          if (total_payment > payment) {
            message = 'Opps error payment field!';
            condition_success = false;
          }
        }
      } else {
        message = 'Opps custommer name is required!';
        condition_success = false;
      }

      if (condition_success) {
        $('#confirm_order-order').val(JSON.stringify(data_order));
        $('#header-drawer').html('Are you sure you want to confirm this order?');
        $('#header-drawer').removeClass('text-red-500');
        $('#header-drawer').addClass('text-gray-500');
        $('#icon_fas-circle-exclamation').removeClass('text-red-500');
        $('#icon_fas-circle-exclamation').addClass('text-gray-500');
        $('#button-drawer-confirm').removeClass('hidden');
        $('button-drawer-close').html('No, cancel');
      } else {
        $('#header-drawer').html(message);
        $('#header-drawer').addClass('text-red-500');
        $('#header-drawer').removeClass('text-gray-500');
        $('#icon_fas-circle-exclamation').addClass('text-red-500');
        $('#icon_fas-circle-exclamation').removeClass('text-gray-500');
        $('#button-drawer-confirm').addClass('hidden');
        $('button-drawer-close').html('Close');
      }

      $('#button-close_order').trigger('click');
      $('#trigger-drawer-confirm_order').trigger('click');
    };
  </script>
@endpush
