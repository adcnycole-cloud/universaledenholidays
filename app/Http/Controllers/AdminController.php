<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;
use App\Models\NewsFeature;
use App\Models\Product;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    private const FIXED_TRANSPORT_PRODUCTS = [
        [
            'name' => '41/44 Seaters Bus',
            'location' => 'Sabah, Malaysia',
            'summary' => 'Suitable for large groups, tours, and corporate travel across Sabah.',
            'description' => 'A comfortable 41/44 seater bus option for larger group movements, events, and holiday transport arrangements.',
            'duration' => 'Custom charter',
            'price_myr' => 0,
            'malaysia_adult_price_myr' => 0,
            'malaysia_child_price_myr' => 0,
            'international_adult_price_myr' => 0,
            'international_child_price_myr' => 0,
            'capacity' => 44,
            'image_url' => null,
            'gallery_images' => [],
            'is_featured' => true,
            'is_top_choice' => false,
            'is_active' => true,
        ],
        [
            'name' => '17 Seaters Van',
            'location' => 'Sabah, Malaysia',
            'summary' => 'A practical van for medium-sized families, small groups, and transfers.',
            'description' => 'A 17 seater van for private transfers, sightseeing routes, and flexible group transportation around Sabah.',
            'duration' => 'Custom charter',
            'price_myr' => 0,
            'malaysia_adult_price_myr' => 0,
            'malaysia_child_price_myr' => 0,
            'international_adult_price_myr' => 0,
            'international_child_price_myr' => 0,
            'capacity' => 17,
            'image_url' => null,
            'gallery_images' => [],
            'is_featured' => true,
            'is_top_choice' => false,
            'is_active' => true,
        ],
        [
            'name' => '9/14 Seaters Van',
            'location' => 'Sabah, Malaysia',
            'summary' => 'Ideal for smaller groups, airport rides, and flexible local transport.',
            'description' => 'A 9/14 seater van option for families, airport pickups, and smaller group movements within Sabah.',
            'duration' => 'Custom charter',
            'price_myr' => 0,
            'malaysia_adult_price_myr' => 0,
            'malaysia_child_price_myr' => 0,
            'international_adult_price_myr' => 0,
            'international_child_price_myr' => 0,
            'capacity' => 14,
            'image_url' => null,
            'gallery_images' => [],
            'is_featured' => true,
            'is_top_choice' => false,
            'is_active' => true,
        ],
    ];

    public function index(): View
    {
        return view('admin.dashboard', $this->sharedAdminData());
    }

    public function profile(): View
    {
        return view('admin.profile', $this->sharedAdminData());
    }

    public function promos(): View
    {
        return view('admin.promos', $this->sharedAdminData());
    }

    public function transport(): View
    {
        return view('admin.transport', $this->sharedAdminData());
    }

    public function packages(): View
    {
        return view('admin.packages', $this->sharedAdminData());
    }

    public function tours(): View
    {
        return view('admin.tours', $this->sharedAdminData());
    }

    public function testimonials(): View
    {
        return view('admin.testimonials', $this->sharedAdminData());
    }

    public function bookings(): View
    {
        $selectedMonth = request('month');
        $monthDate = $this->resolveReportMonth($selectedMonth);
        $data = $this->sharedAdminData();
        $reportBookings = Booking::with(['user', 'product'])
            ->whereBetween('created_at', [$monthDate->copy()->startOfMonth(), $monthDate->copy()->endOfMonth()])
            ->latest()
            ->get();

        return view('admin.bookings', $data + [
            'reportMonth' => $monthDate,
            'reportMonthOptions' => $this->bookingMonthOptions(),
            'monthlyReport' => $this->buildMonthlyReport($reportBookings, $monthDate),
        ]);
    }

    public function exportMonthlyBookings(Request $request): StreamedResponse
    {
        $monthDate = $this->resolveReportMonth($request->query('month'));
        $report = $this->buildMonthlyReport(
            Booking::with(['user', 'product'])
                ->whereBetween('created_at', [$monthDate->copy()->startOfMonth(), $monthDate->copy()->endOfMonth()])
                ->latest()
                ->get(),
            $monthDate,
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Monthly Report');

        $sheet->fromArray([
            ['Universal Eden Holidays'],
            ['Monthly Booking Report'],
            ['Month', $report['month_label']],
            ['Total Bookings', $report['totals']['bookings']],
            ['Confirmed', $report['totals']['confirmed']],
            ['Completed', $report['totals']['completed']],
            ['Pending', $report['totals']['pending']],
            ['Cancelled', $report['totals']['cancelled']],
            ['Guests', $report['totals']['guests']],
            ['Revenue (MYR)', $report['totals']['revenue_myr']],
            [],
            ['Reference', 'Invoice', 'Created', 'Confirmed', 'Customer', 'Service', 'Package', 'Destination', 'Guests', 'Status', 'Payment', 'Currency', 'Amount Display', 'Amount MYR'],
        ], null, 'A1');

        $row = 13;
        foreach ($report['bookings'] as $booking) {
            $sheet->fromArray([[
                $booking->booking_reference,
                $booking->invoice_number ?: '',
                optional($booking->created_at)->format('Y-m-d H:i'),
                optional($booking->confirmed_at)->format('Y-m-d H:i'),
                $booking->full_name,
                ucfirst($booking->service_type),
                $booking->package_name,
                $booking->destination,
                $booking->total_guests,
                ucfirst($booking->status),
                ucwords(str_replace('_', ' ', $booking->payment_status)),
                $booking->currency_code,
                (float) $booking->amount_display,
                (float) $booking->amount_myr,
            ]], null, 'A'.$row);
            $row++;
        }

        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'monthly-bookings-'.$monthDate->format('Y-m').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function invoicePdf(Booking $booking)
    {
        if (! in_array($booking->status, ['confirmed', 'completed'], true)) {
            abort(404);
        }

        if (! $booking->invoice_number) {
            $this->issueInvoiceForBooking($booking);
            $booking->refresh();
        }

        $pdf = Pdf::loadView('admin.bookings.invoice-pdf', [
            'booking' => $booking->fresh(['product', 'user']),
        ])->setPaper('a4');

        return $pdf->stream('invoice-'.$booking->invoice_number_or_reference.'.pdf');
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:transport,package,tour'],
            'location' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'string', 'max:12000'],
            'description' => ['required', 'string', 'max:1000'],
            'duration' => ['required', 'string', 'max:100'],
            'price_myr' => ['required', 'numeric', 'min:0'],
            'malaysia_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'malaysia_child_price_myr' => ['required', 'numeric', 'min:0'],
            'international_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'international_child_price_myr' => ['required', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
        ]);

        if ($validated['category'] === 'transport') {
            return back()->with('success', 'Transport products are fixed for now. Please edit the existing 41/44 bus, 17 seater van, or 9/14 seater van entries below.');
        }

        $galleryImages = collect(preg_split('/\r\n|\r|\n/', $validated['gallery_images'] ?? ''))
            ->map(fn ($image) => trim($image))
            ->filter()
            ->values();

        validator(
            ['gallery_images' => $galleryImages->all()],
            ['gallery_images.*' => ['url', 'max:2048']],
        )->validate();

        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->storeProductImage($request->file('image'));
        }

        Product::create($validated + [
            'gallery_images' => $galleryImages->all(),
            'capacity' => $validated['capacity'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_top_choice' => $request->boolean('is_top_choice'),
            'is_active' => true,
        ]);

        return back()->with('success', 'Product saved successfully.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'string', 'max:12000'],
            'description' => ['required', 'string', 'max:1000'],
            'duration' => ['required', 'string', 'max:100'],
            'price_myr' => ['required', 'numeric', 'min:0'],
            'malaysia_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'malaysia_child_price_myr' => ['required', 'numeric', 'min:0'],
            'international_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'international_child_price_myr' => ['required', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($product->category === 'transport') {
            $allowedTransportNames = collect(self::FIXED_TRANSPORT_PRODUCTS)->pluck('name')->all();
            if (!in_array($validated['name'], $allowedTransportNames, true)) {
                return back()->withErrors(['name' => 'Transport products must remain one of the fixed vehicle options.']);
            }
        }

        $galleryImages = collect(preg_split('/\r\n|\r|\n/', $validated['gallery_images'] ?? ''))
            ->map(fn ($image) => trim($image))
            ->filter()
            ->values();

        validator(
            ['gallery_images' => $galleryImages->all()],
            ['gallery_images.*' => ['url', 'max:2048']],
        )->validate();

        if ($request->hasFile('image')) {
            $this->deleteManagedProductImage($product->image_url);
            $validated['image_url'] = $this->storeProductImage($request->file('image'));
        }

        $product->update($validated + [
            'gallery_images' => $galleryImages->all(),
            'capacity' => $validated['capacity'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_top_choice' => $request->boolean('is_top_choice'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $this->deleteManagedProductImage($product->image_url);
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    private function storeProductImage($image): string
    {
        $path = $image->store('product-images', 'public');

        return Storage::url($path);
    }

    private function deleteManagedProductImage(?string $imageUrl): void
    {
        if (! is_string($imageUrl) || ! str_starts_with($imageUrl, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(substr($imageUrl, strlen('/storage/')));
    }

    public function storeNewsFeature(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'promo_label' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'poster' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $posterPath = $request->file('poster')->store('news-posters', 'public');

        NewsFeature::create([
            'promo_label' => $validated['promo_label'] ?? null,
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'poster_path' => $posterPath,
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Promo news saved successfully.');
    }

    public function updateNewsFeature(Request $request, NewsFeature $newsFeature): RedirectResponse
    {
        $validated = $request->validate([
            'promo_label' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $updates = [
            'promo_label' => $validated['promo_label'] ?? null,
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('poster')) {
            if ($newsFeature->poster_path) {
                Storage::disk('public')->delete($newsFeature->poster_path);
            }

            $updates['poster_path'] = $request->file('poster')->store('news-posters', 'public');
        }

        $newsFeature->update($updates);

        return back()->with('success', 'Promo news updated successfully.');
    }

    public function destroyNewsFeature(NewsFeature $newsFeature): RedirectResponse
    {
        if ($newsFeature->poster_path) {
            Storage::disk('public')->delete($newsFeature->poster_path);
        }

        $newsFeature->delete();

        return back()->with('success', 'Promo news deleted successfully.');
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'trip_name' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('testimonial-profiles', 'public');
        }

        unset($validated['profile_photo']);

        Testimonial::create([
            ...$validated,
            'profile_photo_path' => $profilePhotoPath,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return back()->with('success', 'Testimonial saved successfully.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'trip_name' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $updates = [
            'name' => $validated['name'],
            'location' => $validated['location'],
            'trip_name' => $validated['trip_name'],
            'quote' => $validated['quote'],
            'rating' => $validated['rating'],
            'is_featured' => $request->boolean('is_featured'),
        ];

        if ($request->hasFile('profile_photo')) {
            if ($testimonial->profile_photo_path) {
                Storage::disk('public')->delete($testimonial->profile_photo_path);
            }

            $updates['profile_photo_path'] = $request->file('profile_photo')->store('testimonial-profiles', 'public');
        }

        $testimonial->update($updates);

        return back()->with('success', 'Testimonial updated successfully.');
    }

    public function destroyTestimonial(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->profile_photo_path) {
            Storage::disk('public')->delete($testimonial->profile_photo_path);
        }

        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted successfully.');
    }

    public function updateBooking(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $updates = $validated;

        if (in_array($validated['status'], ['confirmed', 'completed'], true)) {
            $updates['confirmed_at'] = $booking->confirmed_at ?: now();
        }

        $booking->update($updates);

        if (in_array($validated['status'], ['confirmed', 'completed'], true) && ! $booking->invoice_number) {
            $this->issueInvoiceForBooking($booking);
        }

        return back()->with('success', 'Booking status updated.');
    }

    private function ensureFixedTransportProducts(): void
    {
        $legacyTransportNames = [
            'Kota Kinabalu Airport Transfer',
            'West Coast Shuttle Pass',
        ];

        $hasLegacyTransport = Product::where('category', 'transport')
            ->whereIn('name', $legacyTransportNames)
            ->exists();

        if (! $hasLegacyTransport) {
            return;
        }

        Product::where('category', 'transport')->delete();

        foreach (self::FIXED_TRANSPORT_PRODUCTS as $transportProduct) {
            Product::create($transportProduct + ['category' => 'transport']);
        }
    }

    private function sharedAdminData(): array
    {
        $this->ensureFixedTransportProducts();
        $products = Product::latest()->get();
        $fixedTransportNames = collect(self::FIXED_TRANSPORT_PRODUCTS)->pluck('name');

        return [
            'products' => $products,
            'transportProducts' => $fixedTransportNames
                ->map(fn ($name) => $products->first(fn ($product) => $product->category === 'transport' && $product->name === $name))
                ->filter()
                ->values(),
            'packageProducts' => $products->where('category', 'package')->values(),
            'tourProducts' => $products->where('category', 'tour')->values(),
            'newsFeatures' => NewsFeature::latest()->get(),
            'testimonials' => Testimonial::latest()->get(),
            'bookings' => Booking::with(['user', 'product'])->latest()->get(),
            'adminUser' => auth()->user(),
            'stats' => [
                'products' => Product::count(),
                'bookings' => Booking::count(),
                'pendingBookings' => Booking::where('status', 'pending')->count(),
                'customers' => \App\Models\User::where('role', 'customer')->count(),
                'promos' => NewsFeature::count(),
                'testimonials' => Testimonial::count(),
            ],
        ];
    }

    private function resolveReportMonth(?string $month): Carbon
    {
        if (is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month) === 1) {
            return Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        }

        return now()->startOfMonth();
    }

    private function bookingMonthOptions(): Collection
    {
        return Booking::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key")
            ->selectRaw('MAX(created_at) as month_date')
            ->groupBy('month_key')
            ->orderByDesc('month_key')
            ->get()
            ->map(fn ($row) => [
                'value' => $row->month_key,
                'label' => Carbon::parse($row->month_date)->format('F Y'),
            ]);
    }

    private function buildMonthlyReport(Collection $bookings, Carbon $monthDate): array
    {
        $confirmedCount = $bookings->where('status', 'confirmed')->count();
        $completedCount = $bookings->where('status', 'completed')->count();

        return [
            'month_value' => $monthDate->format('Y-m'),
            'month_label' => $monthDate->format('F Y'),
            'totals' => [
                'bookings' => $bookings->count(),
                'confirmed' => $confirmedCount,
                'completed' => $completedCount,
                'pending' => $bookings->where('status', 'pending')->count(),
                'cancelled' => $bookings->where('status', 'cancelled')->count(),
                'guests' => $bookings->sum(fn (Booking $booking) => $booking->total_guests),
                'revenue_myr' => round($bookings
                    ->filter(fn (Booking $booking) => in_array($booking->status, ['confirmed', 'completed'], true))
                    ->sum(fn (Booking $booking) => (float) $booking->amount_myr), 2),
            ],
            'bookings' => $bookings,
        ];
    }

    private function issueInvoiceForBooking(Booking $booking): void
    {
        if ($booking->invoice_number) {
            return;
        }

        $confirmedAt = $booking->confirmed_at ?: now();
        $invoiceNumber = 'UEH-INV-'.$confirmedAt->format('Ym').'-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT);

        $booking->update([
            'confirmed_at' => $confirmedAt,
            'invoice_number' => $invoiceNumber,
            'invoice_issued_at' => now(),
        ]);
    }
}
