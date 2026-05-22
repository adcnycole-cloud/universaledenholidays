<?php

namespace App\Http\Controllers;

use App\Mail\BookingInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;
use App\Models\NewsFeature;
use App\Models\Product;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'summary' => 'Suitable for large groups, holiday transfers, and corporate travel across Sabah.',
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
            'is_discounted' => false,
            'discount_percentage' => null,
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
            'is_discounted' => false,
            'discount_percentage' => null,
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
            'is_discounted' => false,
            'discount_percentage' => null,
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

    public function testimonials(): View
    {
        return view('admin.testimonials', $this->sharedAdminData());
    }

    public function bookings(): View
    {
        $searchTerm = trim((string) request('q', ''));
        $reportType = request('report_type', 'monthly');
        $reportPeriod = $this->resolveReportPeriod($reportType, request('period'));
        $data = $this->sharedAdminData();
        $bookingsQuery = Booking::activeBookings()->with(['user', 'product']);

        if ($searchTerm !== '') {
            $bookingsQuery->where(function ($query) use ($searchTerm) {
                $query->where('full_name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('email', 'like', '%'.$searchTerm.'%')
                    ->orWhere('booking_reference', 'like', '%'.$searchTerm.'%')
                    ->orWhere('package_name', 'like', '%'.$searchTerm.'%');
            });
        }

        $bookings = $bookingsQuery
            ->latest()
            ->paginate(7)
            ->withQueryString();

        $bookingSearchSuggestions = Booking::activeBookings()
            ->select('full_name')
            ->whereNotNull('full_name')
            ->where('full_name', '!=', '')
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where('full_name', 'like', $searchTerm.'%');
            })
            ->distinct()
            ->orderBy('full_name')
            ->limit(8)
            ->pluck('full_name');

        $reportBookings = Booking::activeBookings()
            ->with(['user', 'product'])
            ->whereBetween('created_at', [$reportPeriod['start'], $reportPeriod['end']])
            ->latest()
            ->get();

        return view('admin.bookings', array_merge($data, [
            'bookings' => $bookings,
            'bookingSearchSuggestions' => $bookingSearchSuggestions,
            'reportType' => $reportType,
            'reportPeriodValue' => $reportPeriod['value'],
            'reportPeriodOptions' => $reportType === 'yearly'
                ? $this->bookingYearOptions()
                : $this->bookingMonthOptions(),
            'bookingReport' => $this->buildBookingReport($reportBookings, $reportPeriod),
        ]));
    }

    public function enquiries(): View
    {
        return view('admin.enquiries', $this->sharedAdminData());
    }

    public function exportMonthlyBookings(Request $request): StreamedResponse
    {
        $reportType = $request->query('report_type', 'monthly');
        $reportPeriod = $this->resolveReportPeriod($reportType, $request->query('period'));
        $report = $this->buildBookingReport(
            Booking::activeBookings()
                ->with(['user', 'product'])
                ->whereBetween('created_at', [$reportPeriod['start'], $reportPeriod['end']])
                ->latest()
                ->get(),
            $reportPeriod,
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($reportType === 'yearly' ? 'Yearly Report' : 'Monthly Report');

        $sheet->fromArray([
            ['Universal Eden Holidays'],
            [$reportType === 'yearly' ? 'Yearly Booking Report' : 'Monthly Booking Report'],
            [$reportType === 'yearly' ? 'Year' : 'Month', $report['period_label']],
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
        }, ($reportType === 'yearly' ? 'yearly-bookings-' : 'monthly-bookings-').$report['period_value'].'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function showBooking(Booking $booking): RedirectResponse
    {
        return redirect()->route('bookings.track.show', $booking->booking_reference);
    }

    public function editBooking(Booking $booking): RedirectResponse
    {
        return redirect()->route('bookings.track.show', $booking->booking_reference);
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

        $pdf = $this->buildInvoicePdf($booking->fresh(['product', 'user']));

        return $pdf->stream('invoice-'.$booking->invoice_number_or_reference.'.pdf');
    }

    public function emailInvoice(Booking $booking): RedirectResponse
    {
        if (! in_array($booking->status, ['confirmed', 'completed'], true)) {
            return back()->withErrors([
                'invoice_email' => 'Only confirmed or completed bookings can receive an invoice email.',
            ]);
        }

        if (! $this->invoiceEmailIsConfigured()) {
            return back()->withErrors([
                'invoice_email' => 'Invoice email is not configured for real delivery yet. Set MAIL_MAILER=smtp and add valid SMTP credentials in .env first.',
            ]);
        }

        if (! $booking->invoice_number) {
            $this->issueInvoiceForBooking($booking);
            $booking->refresh();
        }

        $booking = $booking->fresh(['product', 'user']);
        $pdfContent = $this->buildInvoicePdf($booking)->output();

        try {
            Mail::to($booking->email)->send(new BookingInvoiceMail($booking, $pdfContent));
        } catch (\Throwable $exception) {
            Log::error('Failed to send booking invoice email.', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'email' => $booking->email,
                'message' => $exception->getMessage(),
            ]);

            return back()->withErrors([
                'invoice_email' => $this->resolveInvoiceEmailErrorMessage($exception),
            ]);
        }

        return back()->with('success', 'Invoice PDF sent to '.$booking->email.'.');
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:transport,package'],
            'location' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_image_files' => ['nullable', 'array', 'max:20'],
            'gallery_image_files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'description' => ['required', 'string', 'max:1000'],
            'duration' => ['required', 'string', 'max:100'],
            'malaysia_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'malaysia_child_price_myr' => ['required', 'numeric', 'min:0'],
            'international_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'international_child_price_myr' => ['required', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
            'is_discounted' => ['nullable', 'boolean'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($validated['category'] === 'transport') {
            return back()->with('success', 'Transport products are fixed for now. Please edit the existing 41/44 bus, 17 seater van, or 9/14 seater van entries below.');
        }

        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->storeProductImage($request->file('image'));
        }

        $galleryImages = $request->hasFile('gallery_image_files')
            ? $this->storeProductGalleryImages($request->file('gallery_image_files'))
            : [];

        Product::create($validated + [
            'price_myr' => $validated['malaysia_adult_price_myr'],
            'gallery_images' => $galleryImages,
            'capacity' => $validated['capacity'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_top_choice' => $request->boolean('is_top_choice'),
            'is_discounted' => $request->boolean('is_discounted'),
            'discount_percentage' => $request->boolean('is_discounted')
                ? ($validated['discount_percentage'] ?? 0)
                : null,
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
            'existing_gallery_images' => ['nullable', 'array', 'max:20'],
            'existing_gallery_images.*' => ['string', 'max:2048'],
            'gallery_image_files' => ['nullable', 'array', 'max:20'],
            'gallery_image_files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'description' => ['required', 'string', 'max:1000'],
            'duration' => ['required', 'string', 'max:100'],
            'malaysia_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'malaysia_child_price_myr' => ['required', 'numeric', 'min:0'],
            'international_adult_price_myr' => ['required', 'numeric', 'min:0'],
            'international_child_price_myr' => ['required', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
            'is_discounted' => ['nullable', 'boolean'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($product->category === 'transport') {
            $allowedTransportNames = collect(self::FIXED_TRANSPORT_PRODUCTS)->pluck('name')->all();
            if (!in_array($validated['name'], $allowedTransportNames, true)) {
                return back()->withErrors(['name' => 'Transport products must remain one of the fixed vehicle options.']);
            }
        }

        if ($request->hasFile('image')) {
            $this->deleteManagedProductImage($product->image_url);
            $validated['image_url'] = $this->storeProductImage($request->file('image'));
        }

        $existingGalleryImages = collect($validated['existing_gallery_images'] ?? [])
            ->filter(fn ($imageUrl) => is_string($imageUrl) && filled($imageUrl))
            ->values()
            ->all();

        $originalGalleryImages = $product->gallery_images ?? [];
        $removedGalleryImages = array_values(array_diff($originalGalleryImages, $existingGalleryImages));
        $this->deleteManagedProductImages($removedGalleryImages);

        $galleryImages = $existingGalleryImages;
        if ($request->hasFile('gallery_image_files')) {
            $galleryImages = array_merge(
                $galleryImages,
                $this->storeProductGalleryImages($request->file('gallery_image_files')),
            );
        }

        $product->update($validated + [
            'price_myr' => $validated['malaysia_adult_price_myr'],
            'gallery_images' => $galleryImages,
            'capacity' => $validated['capacity'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_top_choice' => $request->boolean('is_top_choice'),
            'is_discounted' => $request->boolean('is_discounted'),
            'discount_percentage' => $request->boolean('is_discounted')
                ? ($validated['discount_percentage'] ?? 0)
                : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $this->deleteManagedProductImage($product->image_url);
        $this->deleteManagedProductImages($product->gallery_images ?? []);
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    private function storeProductImage($image): string
    {
        $path = $image->store('product-images', 'public');

        return Storage::url($path);
    }

    private function storeProductGalleryImages(array $images): array
    {
        return collect($images)
            ->map(function ($image) {
                $path = $image->store('product-galleries', 'public');

                return Storage::url($path);
            })
            ->values()
            ->all();
    }

    private function deleteManagedProductImage(?string $imageUrl): void
    {
        if (! is_string($imageUrl) || ! str_starts_with($imageUrl, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(substr($imageUrl, strlen('/storage/')));
    }

    private function deleteManagedProductImages(array $imageUrls): void
    {
        collect($imageUrls)
            ->filter(fn ($imageUrl) => is_string($imageUrl) && str_starts_with($imageUrl, '/storage/'))
            ->each(fn ($imageUrl) => Storage::disk('public')->delete(substr($imageUrl, strlen('/storage/'))));
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
            'display_location' => ['required', 'in:landing,package'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        if (($validated['display_location'] ?? null) === 'package') {
            $package = Product::where('id', $validated['product_id'] ?? null)
                ->where('category', 'package')
                ->first();

            if (! $package) {
                return back()->withErrors([
                    'product_id' => 'Please choose a valid package for this testimonial.',
                ])->withInput();
            }
        }

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('testimonial-profiles', 'public');
        }

        unset($validated['profile_photo']);

        Testimonial::create([
            ...$validated,
            'profile_photo_path' => $profilePhotoPath,
            'is_featured' => ($validated['display_location'] ?? 'landing') === 'landing',
            'product_id' => ($validated['display_location'] ?? 'landing') === 'package'
                ? $validated['product_id']
                : null,
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
            'display_location' => ['required', 'in:landing,package'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        if (($validated['display_location'] ?? null) === 'package') {
            $package = Product::where('id', $validated['product_id'] ?? null)
                ->where('category', 'package')
                ->first();

            if (! $package) {
                return back()->withErrors([
                    'product_id' => 'Please choose a valid package for this testimonial.',
                ])->withInput();
            }
        }

        $updates = [
            'name' => $validated['name'],
            'location' => $validated['location'],
            'trip_name' => $validated['trip_name'],
            'quote' => $validated['quote'],
            'rating' => $validated['rating'],
            'display_location' => $validated['display_location'],
            'product_id' => $validated['display_location'] === 'package'
                ? $validated['product_id']
                : null,
            'is_featured' => $validated['display_location'] === 'landing',
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
            'newsFeatures' => NewsFeature::latest()->get(),
            'testimonials' => Testimonial::with('product')->latest()->get(),
            'bookings' => Booking::activeBookings()->with(['user', 'product'])->latest()->get(),
            'enquiries' => Booking::enquiries()->with(['user', 'product'])->latest()->get(),
            'adminUser' => auth()->user(),
            'stats' => [
                'products' => Product::count(),
                'bookings' => Booking::activeBookings()->count(),
                'pendingBookings' => Booking::activeBookings()->where('status', 'pending')->count(),
                'enquiries' => Booking::enquiries()->count(),
                'customers' => \App\Models\User::where('role', 'customer')->count(),
                'promos' => NewsFeature::count(),
                'testimonials' => Testimonial::count(),
            ],
        ];
    }

    private function resolveReportPeriod(?string $reportType, ?string $period): array
    {
        if ($reportType === 'yearly') {
            if (is_string($period) && preg_match('/^\d{4}$/', $period) === 1) {
                $yearDate = Carbon::createFromFormat('Y', $period)->startOfYear();
            } else {
                $yearDate = now()->startOfYear();
            }

            return [
                'type' => 'yearly',
                'value' => $yearDate->format('Y'),
                'label' => $yearDate->format('Y'),
                'start' => $yearDate->copy()->startOfYear(),
                'end' => $yearDate->copy()->endOfYear(),
            ];
        }

        if (is_string($period) && preg_match('/^\d{4}-\d{2}$/', $period) === 1) {
            $monthDate = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        } else {
            $monthDate = now()->startOfMonth();
        }

        return [
            'type' => 'monthly',
            'value' => $monthDate->format('Y-m'),
            'label' => $monthDate->format('F Y'),
            'start' => $monthDate->copy()->startOfMonth(),
            'end' => $monthDate->copy()->endOfMonth(),
        ];
    }

    private function bookingMonthOptions(): Collection
    {
        return Booking::activeBookings()
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

    private function bookingYearOptions(): Collection
    {
        return Booking::activeBookings()
            ->selectRaw('YEAR(created_at) as year_key')
            ->groupBy('year_key')
            ->orderByDesc('year_key')
            ->get()
            ->map(fn ($row) => [
                'value' => (string) $row->year_key,
                'label' => (string) $row->year_key,
            ]);
    }

    private function buildBookingReport(Collection $bookings, array $reportPeriod): array
    {
        $confirmedCount = $bookings->where('status', 'confirmed')->count();
        $completedCount = $bookings->where('status', 'completed')->count();

        return [
            'period_type' => $reportPeriod['type'],
            'period_value' => $reportPeriod['value'],
            'period_label' => $reportPeriod['label'],
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

    private function buildInvoicePdf(Booking $booking)
    {
        return Pdf::loadView('admin.bookings.invoice-pdf', [
            'booking' => $booking,
        ])->setPaper('a4');
    }

    private function invoiceEmailIsConfigured(): bool
    {
        $defaultMailer = config('mail.default');

        if (in_array($defaultMailer, ['log', 'array'], true)) {
            return false;
        }

        if ($defaultMailer === 'smtp') {
            return filled(config('mail.mailers.smtp.host'))
                && filled(config('mail.mailers.smtp.port'))
                && filled(config('mail.mailers.smtp.username'))
                && filled(config('mail.mailers.smtp.password'))
                && filled(config('mail.from.address'));
        }

        return filled(config('mail.from.address'));
    }

    private function resolveInvoiceEmailErrorMessage(\Throwable $exception): string
    {
        $message = strtolower($exception->getMessage());

        if (
            str_contains($message, 'application-specific password required')
            || str_contains($message, 'invalidsecondfactor')
            || str_contains($message, '5.7.9')
        ) {
            return 'Gmail rejected the login. Please use a Google App Password in MAIL_PASSWORD instead of the normal Gmail password.';
        }

        return 'The invoice email could not be sent. Please check the mail settings and try again.';
    }
}
