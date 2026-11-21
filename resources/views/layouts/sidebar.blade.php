<aside
      x-data="{ open: $store.sidebar.open }"
      x-bind:class="open ? 'w-64' : 'w-16'"                                                                                                   
      class="fixed top-0 left-0 h-full bg-gray-900 text-white flex flex-col transition-all duration-300 ease-in-out z-30"                     
  >                                                                                                                                           
      {{-- Logo / Brand --}}
      <div class="flex items-center gap-3 px-4 h-16 border-b border-gray-700 shrink-0 overflow-hidden">
          <div class="shrink-0 w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center font-bold text-sm">
              S
          </div>
          <span x-show="open" x-transition.opacity class="font-semibold text-sm whitespace-nowrap">
              StepUp Admin
          </span>
      </div>

      {{-- Navigation --}}
      <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden">

          {{-- Divider label --}}
          <p x-show="open" x-transition.opacity class="px-4 pb-1 text-xs font-semibold uppercase tracking-widest text-gray-500">
              Menu
          </p>

          {{-- Dashboard --}}
          <a href="{{ route('dashboard') }}"
             class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-md mx-2 transition-colors
                    {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"> 
              <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
              </svg>
              <span x-show="open" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
          </a>

          {{-- Divider --}}
          <p x-show="open" x-transition.opacity class="px-4 pt-4 pb-1 text-xs font-semibold uppercase tracking-widest text-gray-500">
              Master Data
          </p>

          {{-- Rewards --}}
          <a href="{{ route('admin.rewards.index') }}"
             class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-md mx-2 transition-colors
  {{ request()->routeIs('admin.rewards.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
              <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v13m0-13V6a4 4 0 00-4-4H6a2 2 0 00-2 2v2m8 0h8m-8 0H4m8 0v13M4 8h16" />
              </svg>
              <span x-show="open" x-transition.opacity class="whitespace-nowrap">Rewards</span>
          </a>

          {{-- Collections --}}
          <a href="{{ route('admin.collections.index') }}"
             class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-md mx-2 transition-colors
                    {{ request()->routeIs('admin.collections.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
              <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0  
  012 2v2M7 7h10" />
              </svg>
              <span x-show="open" x-transition.opacity class="whitespace-nowrap">Collections</span>
          </a>

          {{-- Badges --}}
          <a href="{{ route('admin.badges.index') }}"
             class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-md mx-2 transition-colors
                    {{ request()->routeIs('admin.badges.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
              <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0   
  00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42
  3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0        
  013.138-3.138z" />
              </svg>
              <span x-show="open" x-transition.opacity class="whitespace-nowrap">Badges</span>
          </a>

          {{-- Challenges --}}
          <a href="{{ route('admin.challenges.index') }}"
             class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-md mx-2 transition-colors
                    {{ request()->routeIs('admin.challenges.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
              <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <span x-show="open" x-transition.opacity class="whitespace-nowrap">Challenges</span>
          </a>

      </nav>

      {{-- Toggle collapse button --}}
      <div class="border-t border-gray-700 p-3 shrink-0">
          <button @click="$store.sidebar.toggle()"
  class="w-full flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
              <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
              </svg>
              <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
              </svg>
          </button>
      </div>
  </aside>