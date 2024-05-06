<aside id="sidebar"
  class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width"
  aria-label="Sidebar">
  <div
    class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white shadow-lg rounded-br-xl border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
        <ul class="pb-2 space-y-2">
          <li>
            <a href="/"
              class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
              <x-fas-home class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
              <span class="ml-3" sidebar-toggle-item>Dashboard</span>
            </a>
          </li>
          <li>
            <button type="button"
              class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->is('dashboard/master-data*') ? 'bg-gray-100' : '' }}"
              aria-controls="dropdown-master-data" data-collapse-toggle="dropdown-master-data">
              <x-fas-database class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Master Data</span>
              <x-fas-chevron-down class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
            </button>
            <ul id="dropdown-master-data" class="hidden py-2 space-y-2">
              <li>
                <a href="/dashboard/master-data/category-product"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->is('dashboard/master-data/category-product*') ? 'bg-gray-100' : '' }}">Category
                  Products</a>
              </li>
              <li>
                {{-- <a href="/dashboard/master-data/product" --}}
                <a href="/dashboard/master-data/product"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->is('dashboard/master-data/product*') ? 'bg-gray-100' : '' }}">Products</a>
              </li>
            </ul>
          </li>
          <li>
            <button type="button"
              class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->is('dashboard/finance*') ? 'bg-gray-100' : '' }}"
              aria-controls="dropdown-finance" data-collapse-toggle="dropdown-finance">
              <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Finance</span>
              <x-fas-chevron-down class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
            </button>
            <ul id="dropdown-finance" class="hidden py-2 space-y-2">
              <li>
                <a href="/dashboard/finance/cash-in"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->is('dashboard/finance/cash-in*') ? 'bg-gray-100' : '' }}">Cash
                  In</a>
              </li>
              <li>
                <a href="/dashboard/finance/cash-out"
                  class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->is('dashboard/finance/cash-out*') ? 'bg-gray-100' : '' }}">Cash
                  Out</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

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
    const toggleSidebarMobileSearch = document.getElementById('toggleSidebarMobileSearch');

    toggleSidebarMobileSearch.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });

    toggleSidebarMobileEl.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });

    sidebarBackdrop.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });
  }
</script>
