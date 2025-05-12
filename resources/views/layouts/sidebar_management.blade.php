<aside id="sidebar"
  class="fixed top-0 left-0 z-[10] flex flex-col flex-shrink-0 w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width hidden"
  aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white shadow-lg rounded-br-xl border-r border-gray-200">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200">
        <ul class="pb-2 space-y-2">
          <li>
            <a href="{{ route('management.dashboard') }}"
              class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group {{ Request::route()->getName() == 'management.dashboard' ? 'bg-gray-100' : '' }}">
              <x-fas-home class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" />
              <span class="ml-3" sidebar-toggle-item>Beranda</span>
            </a>
          </li>

          <li>
            <a href="{{ route('management.company.index') }}"
              class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group {{ str_contains(Request::route()->getName(), 'management.company') ? 'bg-gray-100' : '' }}">
              <x-fas-building class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 mr-1" />
              <span class="ml-3" sidebar-toggle-item>Mitra Perusahaan</span>
            </a>
          </li>
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
