<x-layouts.app title="Admin Staff | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <section class="mt-5 space-y-8">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-600">About Us</p>
                        <h1 class="mt-2 text-2xl font-semibold text-stone-900">Staff management</h1>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">
                            Add, update, and remove the team members shown on the public About Us page. Each profile can include a name, designation, contact, email, and photo.
                        </p>
                    </div>
                    <div class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">
                        {{ ($staffMembers ?? collect())->count() }} staff member{{ ($staffMembers ?? collect())->count() === 1 ? '' : 's' }}
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.about-us') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-300 hover:bg-stone-50">
                        About Us navigation
                    </a>
                    <a href="{{ route('admin.staff') }}" class="rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:bg-sky-100">
                        Staff management profile
                    </a>
                    <a href="{{ route('admin.about-us.certifications') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-300 hover:bg-stone-50">
                        Certification
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-4 xl:grid-cols-2" data-form-persist="admin-staff-create">
                    @csrf
                    <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-stone-500">Add Staff Member</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Name</label>
                                <input name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Designation</label>
                                <input name="designation" type="text" value="{{ old('designation') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" placeholder="Example: Tour Consultant" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Contact</label>
                                <input name="contact" type="text" value="{{ old('contact') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" placeholder="Example: +60 16-812 2921">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Email</label>
                                <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" placeholder="Example: hello@example.com">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Display Order</label>
                                <input name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', 0) }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Photo</label>
                                <input name="photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700" required>
                            </div>
                        </div>
                        <button type="submit" class="mt-5 inline-flex rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">Add Staff</button>
                    </div>

                    <div class="rounded-[1.5rem] border border-stone-200 bg-[linear-gradient(135deg,_#f8fafc,_#ffffff_62%)] p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-stone-500">About Us Preview</p>
                        <div class="mt-4 rounded-[1.4rem] border border-stone-200 bg-white p-4 shadow-sm">
                            <div class="grid gap-2 pb-1" style="grid-template-columns: repeat(5, minmax(0, 1fr));">
                                @forelse (($staffMembers ?? collect()) as $member)
                                    <div class="overflow-hidden rounded-[1.2rem] border border-stone-200 bg-stone-50" style="min-height: 20rem;">
                                        <div class="overflow-hidden bg-stone-200" style="width: 100%; height: 16rem;">
                                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                        </div>
                                        <div class="space-y-1 px-3 py-3">
                                            <p class="text-sm font-semibold leading-5 text-stone-900">{{ $member->name }}</p>
                                            <p class="text-sm leading-5 text-stone-600">{{ $member->designation }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="sm:col-span-2 rounded-[1.2rem] border border-dashed border-stone-300 bg-stone-50 px-4 py-8 text-center text-sm text-stone-500">
                                        No staff profiles saved yet. Add the first team member to populate the About Us page.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Saved Profiles</p>
                        <h2 class="mt-2 text-2xl font-semibold text-stone-900">Current staff list</h2>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-stone-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-stone-200 text-left text-sm">
                            <thead class="bg-stone-100 text-stone-700">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">Photo</th>
                                    <th class="px-5 py-4 font-semibold">Name</th>
                                    <th class="px-5 py-4 font-semibold">Designation</th>
                                    <th class="px-5 py-4 font-semibold">Contact</th>
                                    <th class="px-5 py-4 font-semibold">Email</th>
                                    <th class="px-5 py-4 font-semibold">Order</th>
                                    <th class="px-5 py-4 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-200 bg-white">
                                @forelse (($staffMembers ?? collect()) as $member)
                                    <tr>
                                        <td class="px-5 py-4 align-top">
                                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="h-16 w-16 rounded-2xl object-cover border border-stone-200">
                                        </td>
                                        <td class="px-5 py-4 align-top font-semibold text-stone-900">{{ $member->name }}</td>
                                        <td class="px-5 py-4 align-top text-stone-700">{{ $member->designation }}</td>
                                        <td class="px-5 py-4 align-top text-stone-700">{{ $member->contact ?: '-' }}</td>
                                        <td class="px-5 py-4 align-top text-stone-700">{{ $member->email ?: '-' }}</td>
                                        <td class="px-5 py-4 align-top text-stone-700">{{ $member->sort_order }}</td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex flex-wrap gap-2">
                                                <details class="group min-w-[16rem]">
                                                    <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                                        <span class="group-open:hidden">Edit</span>
                                                        <span class="hidden group-open:inline">Close</span>
                                                    </summary>
                                                    <form method="POST" action="{{ route('admin.staff.update', $member) }}" enctype="multipart/form-data" class="mt-4 grid gap-4 rounded-2xl border border-stone-200 bg-stone-50 p-4 md:grid-cols-2" data-form-persist="admin-staff-update-{{ $member->id }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div>
                                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Name</label>
                                                            <input name="name" type="text" value="{{ $member->name }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>
                                                        </div>
                                                        <div>
                                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Designation</label>
                                                            <input name="designation" type="text" value="{{ $member->designation }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>
                                                        </div>
                                                        <div>
                                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Contact</label>
                                                            <input name="contact" type="text" value="{{ $member->contact }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                                        </div>
                                                        <div>
                                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Email</label>
                                                            <input name="email" type="email" value="{{ $member->email }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                                        </div>
                                                        <div>
                                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Display Order</label>
                                                            <input name="sort_order" type="number" min="0" max="9999" value="{{ $member->sort_order }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                                        </div>
                                                        <div>
                                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Replace Photo</label>
                                                            <input name="photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700">
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <button type="submit" class="inline-flex rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-emerald-700">Save Staff Profile</button>
                                                        </div>
                                                    </form>
                                                </details>
                                                <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" onsubmit="return confirm('Delete this staff profile?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-8 text-center text-sm text-stone-600">No staff profiles saved yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </section>
    </main>
</x-layouts.app>
