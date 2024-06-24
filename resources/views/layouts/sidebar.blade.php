<aside id="sidebar"
  class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width hidden"
  aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white shadow-lg rounded-br-xl border-r border-gray-200">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200">
        <ul class="pb-2 space-y-2">
          <li>
            <a href="{{ route('dashboard') }}"
              class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group {{ Request::route()->getName() == 'dashboard' ? 'bg-gray-100' : '' }}">
              <x-fas-home class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
              <span class="ml-3" sidebar-toggle-item>Dashboard</span>
            </a>
          </li>

          {{-- FINANCE --}}
          @if (Auth::user()->id != 2)
            <li>
              <button type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? 'bg-gray-100' : '' }}"
                aria-controls="dropdown-finance" data-collapse-toggle="dropdown-finance">
                <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Finance</span>
                <x-fas-chevron-down
                  class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              </button>
              <ul id="dropdown-finance"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? '' : 'hidden' }} py-2 space-y-2">
                <li>
                  <a href="{{ route('dashboard.finance.funds') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.finance.funds') ? 'bg-gray-100' : '' }}">
                    Funds
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard.finance.cash-flow-daily') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ Request::route()->getName() == 'dashboard.finance.cash-flow-daily' ? 'bg-gray-100' : '' }}">
                    Daily Cash Flow
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard.finance.cash-flow-monthly') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ Request::route()->getName() == 'dashboard.finance.cash-flow-monthly' ? 'bg-gray-100' : '' }}">
                    Monthly Cash Flow
                  </a>
                </li>
              </ul>
            </li>
          @endif
          {{-- END FINANCE --}}

          {{-- ORDER --}}
          <li>
            <button type="button"
              class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.order.') ? 'bg-gray-100' : '' }}"
              aria-controls="dropdown-order" data-collapse-toggle="dropdown-order">
              <x-fas-shopping-basket
                class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Order</span>
              <x-fas-chevron-down class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
            </button>
            <ul id="dropdown-order"
              class="{{ str_contains(Request::route()->getName(), 'dashboard.order.') ? '' : 'hidden' }} py-2 space-y-2">
              <li>
                <a href="{{ route('dashboard.order.order_active') }}"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.order.order_active') ? 'bg-gray-100' : '' }}">
                  Order Active
                </a>
              </li>
              <li>
                <a href="{{ route('dashboard.order.order_history') }}"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.order.order_history') ? 'bg-gray-100' : '' }}">
                  Order History
                </a>
              </li>
            </ul>
          </li>
          {{-- END ORDER --}}

          {{-- COMPANY --}}
          @if (Auth::user()->id != 2)
            <li>
              <button type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.company.') ? 'bg-gray-100' : '' }}"
                aria-controls="dropdown-company" data-collapse-toggle="dropdown-company">
                <x-fas-building class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Company</span>
                <x-fas-chevron-down
                  class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              </button>
              <ul id="dropdown-company"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.company.') ? '' : 'hidden' }} py-2 space-y-2">
                <li>
                  <a href="{{ route('dashboard.company.profile') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.company.profile') ? 'bg-gray-100' : '' }}">
                    Profile
                  </a>
                </li>
              </ul>
            </li>
          @endif
          {{-- END COMPANY --}}

          {{-- MASTER DATA --}}
          @if (Auth::user()->id != 2)
            <li>
              <button type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.') ? 'bg-gray-100' : '' }}"
                aria-controls="dropdown-master-data" data-collapse-toggle="dropdown-master-data">
                <x-fas-database class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Master Data</span>
                <x-fas-chevron-down
                  class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              </button>
              <ul id="dropdown-master-data"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.master-data.') ? '' : 'hidden' }} py-2 space-y-2">
                <li>
                  <a href="{{ route('dashboard.master-data.category-product') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.category-product') ? 'bg-gray-100' : '' }}">
                    Category Products
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard.master-data.product') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.product') ? 'bg-gray-100' : '' }}">
                    Products
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard.master-data.funds') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.funds') ? 'bg-gray-100' : '' }}">
                    Funds
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard.master-data.remarks-cash-flow') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.remarks-cash-flow') ? 'bg-gray-100' : '' }}">
                    Remarks Cash Flow
                  </a>
                </li>
              </ul>
            </li>
          @endif
          {{-- END MASTER DATA --}}

          {{-- PRESENCE --}}
          <li>
            <button type="button"
              class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.presence.') ? 'bg-gray-100' : '' }}"
              aria-controls="dropdown-presence" data-collapse-toggle="dropdown-presence">
              <x-fas-user-check class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Presence</span>
              <x-fas-chevron-down class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
            </button>
            <ul id="dropdown-presence"
              class="{{ str_contains(Request::route()->getName(), 'dashboard.presence.') ? '' : 'hidden' }} py-2 space-y-2">
              <li>
                <a href="{{ route('dashboard.presence.index') }}"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.presence.index') ? 'bg-gray-100' : '' }}">
                  Presence
                </a>
              </li>
              <li>
                <a href="{{ route('dashboard.presence.presence_history') }}"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.presence.presence_history') ? 'bg-gray-100' : '' }}">
                  Presence History
                </a>
              </li>
            </ul>
          </li>
          {{-- END PRESENCE --}}
        </ul>
      </div>
    </div>
  </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50" id="sidebarBackdrop"></div>

<script>
  const sidebar = document.getElementById('sidebar');

  if (sidebar) {
    const toggleSidebarMobile = (sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose) => {
      sidebar.classList.toggle('hidden');
      sidebarBackdrop.classList.toggle('hidden');
      toggleSidebarMobileHamburger.classList.toggle('hidden');
      toggleSidebarMobileClose.classList.toggle('hidden');
    }

    const toggleSidebarMobileEl = document.getElementById('toggleSidebarMobile');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const toggleSidebarMobileHamburger = document.getElementById('toggleSidebarMobileHamburger');
    const toggleSidebarMobileClose = document.getElementById('toggleSidebarMobileClose');
    // const toggleSidebarMobileSearch = document.getElementById('toggleSidebarMobileSearch');

    // toggleSidebarMobileSearch.addEventListener('click', () => {
    //   toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    // });

    toggleSidebarMobileEl.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });

    sidebarBackdrop.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });
  }
</script>
