<section class="mt-3 flex flex-wrap gap-4">
    <div class="flex min-w-[11rem] flex-1 flex-col justify-between rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-stone-500">Products</p>
        <p class="mt-2 text-3xl font-semibold text-stone-900">{{ $stats['products'] }}</p>
    </div>
    <div class="flex min-w-[11rem] flex-1 flex-col justify-between rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-stone-500">Bookings</p>
        <p class="mt-2 text-3xl font-semibold text-stone-900">{{ $stats['bookings'] }}</p>
    </div>
    <div class="flex min-w-[11rem] flex-1 flex-col justify-between rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-stone-500">Pending</p>
        <p class="mt-2 text-3xl font-semibold text-stone-900">{{ $stats['pendingBookings'] }}</p>
    </div>
    <div class="flex min-w-[11rem] flex-1 flex-col justify-between rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-stone-500">Customers</p>
        <p class="mt-2 text-3xl font-semibold text-stone-900">{{ $stats['customers'] }}</p>
    </div>
    <div class="flex min-w-[11rem] flex-1 flex-col justify-between rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-stone-500">Promos</p>
        <p class="mt-2 text-3xl font-semibold text-stone-900">{{ $stats['promos'] }}</p>
    </div>
    <div class="flex min-w-[11rem] flex-1 flex-col justify-between rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-stone-500">Testimonials</p>
        <p class="mt-2 text-3xl font-semibold text-stone-900">{{ $stats['testimonials'] }}</p>
    </div>
</section>
