<x-layouts.app title="Customer Login | Universal Eden Holidays">
    <main class="mx-auto max-w-6xl px-6 py-12 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-2">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Customer Login</p>
                <h1 class="mt-3 text-4xl font-semibold text-stone-900">Log in to manage your Sabah travel plans</h1>
                <p class="mt-4 text-sm leading-7 text-stone-600">Customers can review bookings and edit profile preferences here. Admins have a separate login page for dashboard access.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        Please check your details and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-stone-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-stone-700">Password</label>
                        <input id="password" name="password" type="password" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-stone-600">
                        <input type="checkbox" name="remember" value="1" class="rounded border-stone-300">
                        Remember me
                    </label>
                    <button type="submit" class="w-full rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-sky-700">Log In</button>
                </form>

                <div class="mt-6 rounded-3xl bg-stone-50 p-5 text-sm leading-7 text-stone-600">
                    <p class="font-semibold text-stone-900">Default admin account</p>
                    <p>Email: <span class="font-medium">admin@universaledenholiday.com</span></p>
                    <p>Password: <span class="font-medium">Admin123!</span></p>
                </div>
            </section>

            <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Register</p>
                <h2 class="mt-3 text-4xl font-semibold text-stone-900">Create a customer profile</h2>

                <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-stone-700">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="register_email" class="mb-2 block text-sm font-medium text-stone-700">Email</label>
                        <input id="register_email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-medium text-stone-700">Phone</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="register_password" class="mb-2 block text-sm font-medium text-stone-700">Password</label>
                            <input id="register_password" name="password" type="password" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-stone-700">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                        </div>
                    </div>
                    <button type="submit" class="w-full rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-stone-800 transition hover:bg-stone-100">Create Account</button>
                </form>
            </section>
        </div>
    </main>
</x-layouts.app>
