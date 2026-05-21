<section class="mt-5 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-emerald-700">Enquiries</p>
            <h1 class="mt-2 text-3xl font-semibold text-stone-900">Customer enquiry inbox</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">Review customers who asked for more details before making a reservation or booking. Use this page to follow up and keep those enquiries separate from the booking queue.</p>
        </div>
        <a href="{{ route('admin.bookings') }}" class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
            View Booking Queue
        </a>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-[1.25rem] border border-stone-200 bg-stone-50 p-4">
            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Total enquiries</p>
            <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $enquiries->count() }}</p>
        </div>
        <div class="rounded-[1.25rem] border border-stone-200 bg-stone-50 p-4">
            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Pending follow-up</p>
            <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $enquiries->where('status', 'pending')->count() }}</p>
        </div>
        <div class="rounded-[1.25rem] border border-stone-200 bg-stone-50 p-4">
            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Package enquiries</p>
            <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $enquiries->where('service_type', 'package')->count() }}</p>
        </div>
        <div class="rounded-[1.25rem] border border-stone-200 bg-stone-50 p-4">
            <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Transport enquiries</p>
            <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $enquiries->where('service_type', 'transport')->count() }}</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-stone-200 bg-white">
        <div class="flex flex-col gap-4 border-b border-stone-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
            <p id="admin-enquiry-results" class="sr-only">Showing enquiry entries</p>
            <div class="flex w-full items-center gap-3">
                <label class="relative block min-w-0 flex-1">
                    <input
                        id="admin-enquiry-search"
                        type="search"
                        placeholder="Search by customer, email, product, reference, or note"
                        class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-emerald-400 focus:bg-white"
                    >
                </label>
                <div class="ml-auto flex shrink-0 items-center gap-2">
                    <button
                        id="admin-enquiry-prev"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-stone-700 transition hover:bg-stone-100"
                        aria-label="Previous enquiries page"
                    >
                        <span class="text-lg leading-none">&#8249;</span>
                    </button>
                    <button
                        id="admin-enquiry-next"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-stone-700 transition hover:bg-stone-100"
                        aria-label="Next enquiries page"
                    >
                        <span class="text-lg leading-none">&#8250;</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-hidden rounded-b-[1.75rem]">
            <table id="admin-enquiry-table" class="w-full table-fixed divide-y divide-stone-200 text-xs md:text-sm">
                <thead class="bg-stone-100/90">
                    <tr class="text-left font-semibold uppercase tracking-[0.18em] text-stone-600">
                        <th class="w-[28%] px-3 py-4 md:px-4">Customer</th>
                        <th class="w-[30%] px-3 py-4 md:px-4">Enquiry</th>
                        <th class="hidden w-[16%] px-3 py-4 md:px-4 lg:table-cell">Date</th>
                        <th class="hidden w-[12%] px-3 py-4 md:px-4 xl:table-cell">Status</th>
                        <th class="w-[30%] px-3 py-4 text-right md:px-4 lg:w-[14%]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse ($enquiries as $enquiry)
                        <tr data-admin-enquiry-item="true" class="align-top text-stone-700">
                            <td class="px-3 py-4 md:px-4">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900">{{ $enquiry->full_name }}</p>
                                    <p class="mt-0.5 truncate text-xs text-stone-500">{{ $enquiry->email }}</p>
                                </div>
                            </td>
                            <td class="px-3 py-4 md:px-4">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900">{{ $enquiry->package_name }}</p>
                                    <p class="mt-0.5 truncate text-xs text-stone-500">{{ $enquiry->destination }}</p>
                                    <p class="mt-0.5 truncate text-xs text-stone-500">{{ ucfirst($enquiry->service_type) }}</p>
                                </div>
                            </td>
                            <td class="hidden px-3 py-4 text-xs md:px-4 lg:table-cell">
                                <p class="font-semibold text-stone-900">{{ optional($enquiry->check_in_date)->format('d M Y') ?: 'N/A' }}</p>
                            </td>
                            <td class="hidden px-3 py-4 md:px-4 xl:table-cell">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $enquiry->status === 'pending' ? 'bg-amber-100 text-amber-700' : (strtolower((string) $enquiry->status) === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-700') }}">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-right md:px-4">
                                <details class="relative inline-block text-left">
                                    <summary class="ml-auto list-none cursor-pointer rounded-full border border-stone-300 bg-white p-2 transition hover:bg-stone-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-stone-600" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </summary>
                                    <div class="absolute right-0 top-full z-50 mt-2 w-44 space-y-2 rounded-xl border border-stone-200 bg-white p-3 shadow-lg">
                                        <a href="{{ route('admin.enquiries.show', $enquiry->id) }}" class="block rounded-lg border border-stone-200 px-3 py-2 text-xs font-medium text-stone-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                                            View Details
                                        </a>
                                        <form method="POST" action="{{ route('admin.enquiries.update', $enquiry->id) }}" class="space-y-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="w-full rounded-lg border border-stone-200 px-2 py-1.5 text-xs text-stone-800">
                                                @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                                    <option value="{{ $status }}" @selected($enquiry->status === $status)>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="w-full rounded-lg border border-stone-200 px-2 py-1.5 text-xs font-semibold text-stone-700 transition hover:bg-stone-100">
                                                Update Status
                                            </button>
                                        </form>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-stone-500">No enquiries found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
