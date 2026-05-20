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

    <div class="mt-6 grid gap-4 md:grid-cols-4">
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
        <div class="overflow-x-auto">
            <table id="admin-enquiry-table" class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-100/90">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-stone-600">
                        <th class="px-4 py-4">Customer</th>
                        <th class="px-4 py-4">Enquiry</th>
                        <th class="px-4 py-4">Preference</th>
                        <th class="px-4 py-4">Message</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse ($enquiries as $enquiry)
                        <tr data-admin-enquiry-item="true" class="align-top text-stone-700">
                            <td class="px-4 py-4">
                                <div class="min-w-[14rem]">
                                    <p class="font-semibold text-stone-900">{{ $enquiry->full_name }}</p>
                                    <p class="mt-1 text-xs text-stone-500">{{ $enquiry->email }}</p>
                                    <p class="mt-1 text-xs text-stone-500">{{ $enquiry->phone }}</p>
                                    <p class="mt-2 text-[11px] uppercase tracking-[0.14em] text-stone-400">Submitted {{ optional($enquiry->created_at)->format('d M Y, h:i A') }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[16rem] space-y-1">
                                    <p class="font-semibold text-stone-900">{{ $enquiry->package_name }}</p>
                                    <p class="text-xs text-stone-500">{{ $enquiry->destination }}</p>
                                    <p class="text-xs text-stone-500">{{ ucfirst($enquiry->service_type) }} enquiry</p>
                                    <p class="text-xs text-stone-500">Ref {{ $enquiry->booking_reference ?: 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[12rem] space-y-1 text-xs text-stone-600">
                                    <p>Preferred date: {{ optional($enquiry->check_in_date)->format('d M Y') ?: 'Not shared' }}</p>
                                    <p>Estimated guests: {{ $enquiry->guest_count ?: 'Not shared' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[20rem] max-w-[28rem] whitespace-pre-line text-sm leading-6 text-stone-700">
                                    {{ $enquiry->special_requests ?: 'No message provided.' }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[9rem] space-y-2">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]
                                        {{ $enquiry->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                        {{ $enquiry->status === 'completed' ? 'bg-sky-50 text-sky-700' : '' }}
                                        {{ $enquiry->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                        {{ $enquiry->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : '' }}">
                                        {{ ucfirst($enquiry->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[12rem]">
                                    <form method="POST" action="{{ route('admin.bookings.update', $enquiry) }}" class="space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="w-full rounded-2xl border border-stone-300 px-4 py-2 text-sm text-stone-800">
                                            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}" @selected($enquiry->status === $status)>{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="w-full rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-stone-500">No enquiries found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
