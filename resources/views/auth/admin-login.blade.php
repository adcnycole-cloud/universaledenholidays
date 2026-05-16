<x-layouts.app title="Admin Login | Universal Eden Holidays">
    <main class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.18),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.16),_transparent_30%),linear-gradient(180deg,_#f8fffc,_#f8fafc_55%,_#eef6ff)]"></div>
        <div class="absolute left-[-6rem] top-20 -z-10 h-56 w-56 rounded-full bg-emerald-200/40 blur-3xl"></div>
        <div class="absolute right-[-5rem] top-10 -z-10 h-72 w-72 rounded-full bg-sky-200/40 blur-3xl"></div>

        <div class="mx-auto flex min-h-screen max-w-3xl items-center px-6 py-12 lg:px-10">
            <section class="w-full rounded-[2.25rem] border border-stone-200/80 bg-white p-8 shadow-[0_32px_90px_-40px_rgba(15,23,42,0.4)] xl:p-10">
                <h2 class="text-3xl font-semibold text-stone-900">Admin Login</h2>

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        Please check your credentials and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-stone-700">Admin email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-800 transition focus:border-emerald-400 focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-stone-700">Password</label>
                        <input id="password" name="password" type="password" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-800 transition focus:border-emerald-400 focus:bg-white focus:outline-none">
                    </div>
                    <button type="submit" class="w-full rounded-full bg-[linear-gradient(135deg,_#0f766e,_#059669)] px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:brightness-105">Admin Sign In</button>
                </form>

                <div class="mt-8 rounded-[1.75rem] border border-dashed border-stone-300 bg-stone-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-stone-500">Quick Access</p>
                    <div class="mt-4 space-y-3 text-sm text-stone-600">
                        <p><span class="font-semibold text-stone-900">Default admin email:</span> admin@universaledenholiday.com</p>
                        <p><span class="font-semibold text-stone-900">Default admin password:</span> Admin123!</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
