<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Example Stat Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-600 rounded-full p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7v4a1 1 0 001 1h3m10-5h3a1 1 0 011 1v4a1 1 0 01-1 1h-3m-4 4v4m0 0H7a2 2 0 01-2-2v-5a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2h-5z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Leads</h3>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">123</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 text-green-600 rounded-full p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17v-2a4 4 0 014-4h4m0 0V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10a4 4 0 004 4h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Campaigns</h3>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">8</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 text-yellow-600 rounded-full p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.21 0 4.304.534 6.121 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Users</h3>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">42</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mt-8">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Recent Activity</h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-2 flex justify-between items-center">
                        <span class="text-gray-700 dark:text-gray-300">Lead John Doe added to Campaign Alpha</span>
                        <span class="text-xs text-gray-500">2 hours ago</span>
                    </li>
                    <li class="py-2 flex justify-between items-center">
                        <span class="text-gray-700 dark:text-gray-300">Campaign Beta launched</span>
                        <span class="text-xs text-gray-500">5 hours ago</span>
                    </li>
                    <li class="py-2 flex justify-between items-center">
                        <span class="text-gray-700 dark:text-gray-300">User Jane Smith registered</span>
                        <span class="text-xs text-gray-500">1 day ago</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
