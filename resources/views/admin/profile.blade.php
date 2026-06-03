<x-layouts.app title="Admin Profile | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <section class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm lg:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Admin Profile</p>
            <div class="mt-8 grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">
                <div class="space-y-6">
                    <div class="rounded-xl border border-stone-200 bg-gradient-to-br from-emerald-50 via-white to-sky-50 p-6">
                        <h1 class="text-2xl font-bold text-stone-900">{{ $adminUser->name }}</h1>
                        <p class="mt-2 text-sm text-stone-600">{{ $adminUser->email }}</p>
                        <div class="mt-6 space-y-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Role</p>
                                <p class="mt-2 text-lg font-semibold text-stone-900">Administrator</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Access Level</p>
                                <p class="mt-2 text-sm font-medium text-emerald-700">Full Admin Access</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-stone-200 bg-stone-50 p-6">
                        <h2 class="text-xl font-semibold text-stone-900">Quick Actions</h2>
                        <p class="mt-3 text-sm leading-6 text-stone-600">Navigate to key sections of your admin dashboard or view your live site.</p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                Dashboard
                            </a>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-xs font-semibold text-stone-700 transition hover:border-stone-400 hover:bg-stone-100">
                                View Site
                            </a>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <section class="rounded-xl border border-stone-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.24em] text-sky-600">Admin Users</p>
                                <h2 class="mt-2 text-2xl font-semibold text-stone-900">Add multiple admin accounts</h2>
                            </div>
                            <div class="rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                                {{ $adminUsers->count() }} admin{{ $adminUsers->count() === 1 ? '' : 's' }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 grid gap-4 md:grid-cols-2" data-form-persist="admin-users-create">
                            @csrf
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Full name</label>
                                <input name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Email</label>
                                <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Phone</label>
                                <input name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Password</label>
                                <input name="password" type="password" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-stone-700">Confirm password</label>
                                <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div class="md:col-span-2">
                                <button type="submit" class="rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-white transition hover:bg-emerald-700">
                                    Create Admin User
                                </button>
                            </div>
                        </form>
                    </section>

                    <section class="rounded-xl border border-stone-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-stone-900">Current Admin Accounts</h2>
                        <div class="mt-5 space-y-4">
                            @foreach ($adminUsers as $managedAdmin)
                                <details class="rounded-2xl border border-stone-200 bg-stone-50 p-4">
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-stone-900">{{ $managedAdmin->name }}</p>
                                            <p class="mt-1 text-sm text-stone-500">{{ $managedAdmin->email }}</p>
                                        </div>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $managedAdmin->id === $adminUser->id ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-200 text-stone-700' }}">
                                            {{ $managedAdmin->id === $adminUser->id ? 'Current Admin' : 'Admin' }}
                                        </span>
                                    </summary>

                                    <form method="POST" action="{{ route('admin.users.update', $managedAdmin) }}" class="mt-4 grid gap-3 md:grid-cols-2" data-form-persist="admin-users-update-{{ $managedAdmin->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-stone-700">Full name</label>
                                            <input name="name" type="text" value="{{ $managedAdmin->name }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-stone-700">Email</label>
                                            <input name="email" type="email" value="{{ $managedAdmin->email }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-stone-700">Phone</label>
                                            <input name="phone" type="text" value="{{ $managedAdmin->phone }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-stone-700">New password</label>
                                            <input name="password" type="password" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Leave blank to keep current password">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="mb-2 block text-sm font-medium text-stone-700">Confirm new password</label>
                                            <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800">
                                        </div>
                                        <div class="md:col-span-2">
                                            <button type="submit" class="rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-white transition hover:bg-sky-700">
                                                Save Admin
                                            </button>
                                        </div>
                                    </form>
                                    @if ($managedAdmin->id !== $adminUser->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $managedAdmin) }}" class="mt-3" onsubmit="return confirm('Remove this admin account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-rose-300 bg-white px-5 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-rose-600 transition hover:bg-rose-50">
                                                Remove Admin
                                            </button>
                                        </form>
                                    @endif
                                </details>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
