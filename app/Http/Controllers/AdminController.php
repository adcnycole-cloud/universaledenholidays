<?php

namespace App\Http\Controllers;

use App\Mail\BookingInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BlogPost;
use App\Models\Booking;
use App\Models\CompanyCertification;
use App\Models\HomeHeroSlide;
use App\Models\NewsFeature;
use App\Models\Package;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Testimonial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    private const HOME_HERO_SLIDE_MAX_KB = 20480;
    private const STAFF_PHOTO_MAX_KB = 5120;
    private const PACKAGE_ADMIN_TEMPLATE_PATH = 'resources/admin-templates/universal_eden_easy_package_upload.xlsx';

    private const PACKAGE_DURATION_OPTIONS = ['Day Trip', '2D1N', '3D2N', '4D3N'];
    private const PACKAGE_IMPORT_TEMPLATE_COLUMNS = [
        ['header' => 'name', 'required' => 'Yes', 'description' => 'Package name', 'sample' => 'Kundasang Day Trip'],
        ['header' => 'location', 'required' => 'Yes', 'description' => 'Main destination or area', 'sample' => 'Kundasang, Sabah'],
        ['header' => 'summary', 'required' => 'Yes', 'description' => 'Short package summary', 'sample' => 'Day trip with scenic stops and local attractions.'],
        ['header' => 'description', 'required' => 'No', 'description' => 'Short admin description', 'sample' => 'Includes nature stops and flexible free time.'],
        ['header' => 'package_type', 'required' => 'Yes', 'description' => 'Allowed values: Day Trip, 2D1N, 3D2N, 4D3N', 'sample' => 'Day Trip'],
        ['header' => 'duration_detail', 'required' => 'Day Trip only', 'description' => 'Detailed duration shown for day trips', 'sample' => '10 hours'],
        ['header' => 'departure_time', 'required' => 'Yes', 'description' => 'Main departure time text', 'sample' => '7:30 AM'],
        ['header' => 'pickup_location', 'required' => 'No', 'description' => 'Optional pickup location', 'sample' => 'Kota Kinabalu hotel lobby'],
        ['header' => 'dropoff_location', 'required' => 'No', 'description' => 'Optional drop-off location', 'sample' => 'Kota Kinabalu hotel lobby'],
        ['header' => 'minimum_age_mode', 'required' => 'Yes', 'description' => 'Use no_limit or above_age', 'sample' => 'no_limit'],
        ['header' => 'minimum_age_years', 'required' => 'If above_age', 'description' => 'Whole number age when minimum_age_mode is above_age', 'sample' => '7'],
        ['header' => 'capacity', 'required' => 'No', 'description' => 'Optional capacity value. Numbers only.', 'sample' => '20'],
        ['header' => 'pricing_group_size_label', 'required' => 'Yes', 'description' => 'Use | between multiple pricing rows', 'sample' => '1-2 pax|3-5 pax'],
        ['header' => 'pricing_malaysia_adult_price_myr', 'required' => 'Optional per tier', 'description' => 'Use | between multiple pricing rows. Leave blank or use 0 when not needed.', 'sample' => '180|150'],
        ['header' => 'pricing_malaysia_child_price_myr', 'required' => 'Optional per tier', 'description' => 'Use | between multiple pricing rows. Leave blank or use 0 when not needed.', 'sample' => '150|'],
        ['header' => 'pricing_international_adult_price_myr', 'required' => 'Optional per tier', 'description' => 'Use | between multiple pricing rows. Leave blank or use 0 when not needed.', 'sample' => '220|190'],
        ['header' => 'pricing_international_child_price_myr', 'required' => 'Optional per tier', 'description' => 'Use | between multiple pricing rows. Leave blank or use 0 when not needed.', 'sample' => '190|'],
        ['header' => 'image_url', 'required' => 'No', 'description' => 'Optional image URL. Put the full https:// link here, not in capacity.', 'sample' => 'https://example.com/kundasang.jpg'],
        ['header' => 'itinerary_day_number', 'required' => 'No', 'description' => 'Use | between itinerary rows. Example: Day 1|Day 1|Day 2', 'sample' => 'Day 1|Day 1|Day 2'],
        ['header' => 'itinerary_time', 'required' => 'No', 'description' => 'Use | between itinerary rows. Keep time separate from day.', 'sample' => '13:00|18:30|07:30'],
        ['header' => 'itinerary_activity', 'required' => 'No', 'description' => 'Use | between itinerary rows for the main activity text.', 'sample' => 'Pickup from hotel|Sunset viewing|Breakfast'],
        ['header' => 'itinerary_notes', 'required' => 'No', 'description' => 'Optional notes for each itinerary row. Use | between rows.', 'sample' => 'Proceed to Kudat|Free and easy|American breakfast'],
        ['header' => 'is_featured', 'required' => 'No', 'description' => 'Use yes/no, true/false, or 1/0', 'sample' => 'yes'],
        ['header' => 'is_top_choice', 'required' => 'No', 'description' => 'Use yes/no, true/false, or 1/0', 'sample' => 'no'],
        ['header' => 'is_discounted', 'required' => 'No', 'description' => 'Use yes/no, true/false, or 1/0', 'sample' => 'no'],
        ['header' => 'discount_percentage', 'required' => 'If discounted', 'description' => 'Discount percentage from 0 to 100. Leave blank when is_discounted is no.', 'sample' => '10'],
        ['header' => 'is_active', 'required' => 'No', 'description' => 'Use yes/no, true/false, or 1/0', 'sample' => 'yes'],
    ];

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

    public function landingPage(): View
    {
        return view('admin.landing-page', $this->sharedAdminData());
    }

    public function blogs(): View
    {
        return view('admin.blogs', $this->sharedAdminData());
    }

    public function transport(): View
    {
        return view('admin.transport', $this->sharedAdminData());
    }

    public function packages(): View
    {
        return view('admin.packages', $this->sharedAdminData());
    }

    public function importPackages(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'package_import_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        try {
            $spreadsheet = IOFactory::load($validated['package_import_file']->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray('', true, true, false);
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'package_import_file' => 'The uploaded file could not be read. Please use the package Excel template or a standard CSV file.',
            ]);
        }

        if ($spreadsheet->sheetNameExists('01 Package Entry')) {
            $importCount = $this->importPackagesFromAdminWorkbook($spreadsheet);

            return back()->with('success', $importCount.' package'.($importCount === 1 ? '' : 's').' imported successfully.');
        }

        if ($spreadsheet->sheetNameExists('Package Upload')) {
            $importCount = $this->importPackagesFromEasyWorkbook($spreadsheet);

            return back()->with('success', $importCount.' package'.($importCount === 1 ? '' : 's').' imported successfully.');
        }

        if ($rows === [] || count($rows) < 2) {
            throw ValidationException::withMessages([
                'package_import_file' => 'The import file is empty. Add the header row and at least one package row.',
            ]);
        }

        $headerRow = array_shift($rows);
        $headers = collect($headerRow)
            ->map(fn ($value) => $this->normalizePackageImportHeader((string) $value))
            ->values()
            ->all();

        $missingHeaders = collect(self::PACKAGE_IMPORT_TEMPLATE_COLUMNS)
            ->pluck('header')
            ->intersect([
                'name',
                'location',
                'summary',
                'package_type',
                'departure_time',
                'minimum_age_mode',
                'pricing_group_size_label',
                'pricing_malaysia_adult_price_myr',
                'pricing_malaysia_child_price_myr',
                'pricing_international_adult_price_myr',
                'pricing_international_child_price_myr',
            ])
            ->reject(fn ($header) => in_array($header, $headers, true))
            ->values()
            ->all();

        if ($missingHeaders !== []) {
            throw ValidationException::withMessages([
                'package_import_file' => 'Missing required columns: '.implode(', ', $missingHeaders).'. Download the latest package template and try again.',
            ]);
        }

        $seenCodes = [];
        $importCount = 0;

        DB::transaction(function () use ($rows, $headers, &$seenCodes, &$importCount) {
            foreach ($rows as $index => $rowValues) {
                $rowNumber = $index + 2;
                $row = $this->combinePackageImportRow($headers, $rowValues);

                if ($this->packageImportRowIsEmpty($row)) {
                    continue;
                }

                $normalizedRow = $this->normalizePackageImportRow($row);
                $validator = Validator::make(
                    $normalizedRow,
                    $this->packageImportValidationRules(),
                    $this->packageImportValidationMessages($rowNumber)
                );

                if ($validator->fails()) {
                    throw ValidationException::withMessages($validator->errors()->toArray());
                }

                $importData = $validator->validated();
                $importData['tour_code'] = $this->resolveTourCode(
                    (string) ($importData['tour_code'] ?? ''),
                    $this->isDayTripPackage((string) $importData['package_type'], null),
                    'packages',
                    null,
                    $seenCodes
                );

                if (in_array($importData['tour_code'], $seenCodes, true)) {
                    throw ValidationException::withMessages([
                        'package_import_file' => 'Row '.$rowNumber.': duplicate tour code '.$importData['tour_code'].' was found in the same import file.',
                    ]);
                }

                $seenCodes[] = $importData['tour_code'];

                if (Package::query()->where('tour_code', $importData['tour_code'])->exists()) {
                    throw ValidationException::withMessages([
                        'package_import_file' => 'Row '.$rowNumber.': the tour code '.$importData['tour_code'].' already exists.',
                    ]);
                }

                $packagePayload = $this->buildImportedPackagePayload($importData, $rowNumber);
                Package::create($packagePayload);
                $importCount++;
            }
        });

        if ($importCount === 0) {
            throw ValidationException::withMessages([
                'package_import_file' => 'No package rows were found to import.',
            ]);
        }

        return back()->with('success', $importCount.' package'.($importCount === 1 ? '' : 's').' imported successfully.');
    }

    public function downloadPackageTemplate(Request $request)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));

        abort_unless($format === 'xlsx', 404);

        $spreadsheet = new Spreadsheet();
        $headers = $this->easyPackageUploadTemplateHeaders();
        $fillSheet = $spreadsheet->getActiveSheet();
        $fillSheet->setTitle('Package Upload');
        $fillSheet->fromArray([
            $headers,
            $this->easyPackageUploadTemplateSampleRow(),
        ], null, 'A1');

        foreach (range(1, count($headers)) as $columnIndex) {
            $fillSheet->getColumnDimensionByColumn($columnIndex)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Row');
        $exampleSheet->fromArray([
            ['Packages Import Example'],
            ['Use this as a sample only. Copy the structure, not necessarily the exact values.'],
            $headers,
            $this->easyPackageUploadTemplateSampleRow(),
        ], null, 'A1');

        foreach (range(1, count($headers)) as $columnIndex) {
            $exampleSheet->getColumnDimensionByColumn($columnIndex)->setAutoSize(true);
        }

        $notesSheet = $spreadsheet->createSheet();
        $notesSheet->setTitle('Notes');
        $notesSheet->fromArray([
            ['Packages Import Notes'],
        ], null, 'A1');

        foreach ($this->easyPackageUploadTemplateNotes() as $noteIndex => $note) {
            $notesSheet->setCellValue('A'.($noteIndex + 2), '- '.$note);
        }

        $notesSheet->getColumnDimension('A')->setWidth(120);

        $metaSheet = $spreadsheet->createSheet();
        $metaSheet->setTitle('Column Guide');
        $metaSheet->fromArray([
            ['No.', 'Header', 'Required', 'Description', 'Sample'],
        ], null, 'A1');

        foreach ($this->easyPackageUploadTemplateColumns() as $rowIndex => $column) {
            $metaSheet->fromArray([[
                $rowIndex + 1,
                $column['header'],
                $column['required'],
                $column['description'],
                $column['sample'],
            ]], null, 'A'.($rowIndex + 2));
        }

        foreach (range('A', 'E') as $columnLetter) {
            $metaSheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'packages-import-template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function testimonials(): View
    {
        return view('admin.testimonials', $this->sharedAdminData());
    }

    public function aboutUs(): View
    {
        return view('admin.about-us', $this->sharedAdminData());
    }

    public function staffs(): View
    {
        return view('admin.staff', $this->sharedAdminData());
    }

    public function certifications(): View
    {
        return view('admin.certifications', $this->sharedAdminData());
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

    public function storeAdminUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'tour_code.unique' => 'This tour code is already being used by another package. Please use a different code.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'preferred_currency' => 'MYR',
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'New admin account created successfully.');
    }

    public function updateAdminUser(Request $request, User $user): RedirectResponse
    {
        abort_if(! $user->isAdmin(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'tour_code.unique' => 'This tour code is already being used by another package. Please use a different code.',
        ]);

        $updates = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $updates['password'] = Hash::make($validated['password']);
        }

        $user->update($updates);

        return back()->with('success', 'Admin account updated successfully.');
    }

    public function destroyAdminUser(User $user): RedirectResponse
    {
        abort_if(! $user->isAdmin(), 404);
        abort_if(auth()->id() === $user->id, 422, 'You cannot remove your own admin account.');

        $user->delete();

        return back()->with('success', 'Admin account removed successfully.');
    }

    public function storeStaff(Request $request): RedirectResponse
    {
        $validated = $this->validateStaff($request);

        Staff::create([
            'name' => $validated['name'],
            'contact' => $validated['contact'] ?? null,
            'email' => $validated['email'] ?? null,
            'designation' => $validated['designation'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'photo_path' => $request->hasFile('photo')
                ? $request->file('photo')->store('staff-photos', 'public')
                : null,
        ]);

        return back()->with('success', 'Staff profile added successfully.');
    }

    public function updateStaff(Request $request, Staff $staff): RedirectResponse
    {
        $validated = $this->validateStaff($request, $staff);

        $updates = [
            'name' => $validated['name'],
            'contact' => $validated['contact'] ?? null,
            'email' => $validated['email'] ?? null,
            'designation' => $validated['designation'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if ($request->hasFile('photo')) {
            if ($staff->photo_path) {
                Storage::disk('public')->delete($staff->photo_path);
            }

            $updates['photo_path'] = $request->file('photo')->store('staff-photos', 'public');
        }

        $staff->update($updates);

        return back()->with('success', 'Staff profile updated successfully.');
    }

    public function destroyStaff(Staff $staff): RedirectResponse
    {
        if ($staff->photo_path) {
            Storage::disk('public')->delete($staff->photo_path);
        }

        $staff->delete();

        return back()->with('success', 'Staff profile deleted successfully.');
    }

    public function storeCompanyCertification(Request $request): RedirectResponse
    {
        $validated = $this->validateCompanyCertification($request);

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('company-certifications/logos', 'public')
            : null;

        $certificateSource = $validated['certificate_source'];
        $certificatePath = null;
        $certificateLink = null;

        if ($certificateSource === 'file' && $request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('company-certifications/files', 'public');
        }

        if ($certificateSource === 'link') {
            $certificateLink = $validated['certificate_link'];
        }

        CompanyCertification::create([
            'title' => $validated['title'],
            'value' => $validated['value'] ?? '',
            'description' => $validated['description'] ?? null,
            'logo_path' => $logoPath,
            'certificate_path' => $certificatePath,
            'certificate_link' => $certificateLink,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        return back()->with('success', 'Company certification added successfully.');
    }

    public function updateCompanyCertification(Request $request, CompanyCertification $companyCertification): RedirectResponse
    {
        $validated = $this->validateCompanyCertification($request, $companyCertification);
        $certificateSource = $validated['certificate_source'];

        $updates = [
            'title' => $validated['title'],
            'value' => $validated['value'] ?? $companyCertification->value ?? '',
            'description' => $validated['description'] ?? $companyCertification->description,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if ($request->hasFile('logo')) {
            if ($companyCertification->logo_path) {
                Storage::disk('public')->delete($companyCertification->logo_path);
            }

            $updates['logo_path'] = $request->file('logo')->store('company-certifications/logos', 'public');
        }

        if ($certificateSource === 'file' && $request->hasFile('certificate')) {
            if ($companyCertification->certificate_path) {
                Storage::disk('public')->delete($companyCertification->certificate_path);
            }

            $updates['certificate_path'] = $request->file('certificate')->store('company-certifications/files', 'public');
            $updates['certificate_link'] = null;
        }

        if ($certificateSource === 'link') {
            if ($companyCertification->certificate_path) {
                Storage::disk('public')->delete($companyCertification->certificate_path);
            }

            $updates['certificate_path'] = null;
            $updates['certificate_link'] = $validated['certificate_link'];
        }

        $companyCertification->update($updates);

        return back()->with('success', 'Company certification updated successfully.');
    }

    public function destroyCompanyCertification(CompanyCertification $companyCertification): RedirectResponse
    {
        if ($companyCertification->logo_path) {
            Storage::disk('public')->delete($companyCertification->logo_path);
        }

        if ($companyCertification->certificate_path) {
            Storage::disk('public')->delete($companyCertification->certificate_path);
        }

        $companyCertification->delete();

        return back()->with('success', 'Company certification deleted successfully.');
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
        $request->merge([
            'image_url' => $this->normalizeOptionalUrl($request->input('image_url')),
            'tour_code' => $request->input('category') === 'package'
                ? $this->resolveTourCode(
                    (string) $request->input('tour_code', ''),
                    $request->input('package_type') === 'Day Trip',
                    'products'
                )
                : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:transport,package'],
            'location' => ['required', 'string', 'max:255'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'dropoff_location' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:2000'],
            'image_url' => $this->productImageUrlRules(),
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'string', 'max:5000'],
            'gallery_image_files' => ['nullable', 'array', 'max:20'],
            'gallery_image_files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'itinerary_items_text' => ['nullable', 'string', 'max:5000'],
            'itinerary_day_number' => ['nullable', 'array', 'max:31'],
            'itinerary_day_number.*' => ['nullable', 'string', 'max:50'],
            'itinerary_time' => ['nullable', 'array', 'max:31'],
            'itinerary_time.*' => ['nullable', 'string', 'max:100'],
            'itinerary_activity' => ['nullable', 'array', 'max:31'],
            'itinerary_activity.*' => ['nullable', 'string', 'max:1000'],
            'itinerary_notes' => ['nullable', 'array', 'max:31'],
            'itinerary_notes.*' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'package_type' => [
                Rule::requiredIf($request->input('category') === 'package'),
                'nullable',
                'string',
                'in:'.implode(',', self::PACKAGE_DURATION_OPTIONS),
            ],
            'duration' => [
                Rule::requiredIf($request->input('category') === 'transport'),
                'nullable',
                'string',
                'max:100',
            ],
            'duration_detail' => [
                Rule::requiredIf(
                    $request->input('category') === 'package'
                    && $request->input('package_type') === 'Day Trip'
                ),
                'nullable',
                'string',
                'max:100',
            ],
            'minimum_age_mode' => [
                Rule::requiredIf($request->input('category') === 'package'),
                'nullable',
                'string',
                'in:no_limit,above_age',
            ],
            'minimum_age_years' => [
                Rule::requiredIf(
                    $request->input('category') === 'package'
                    && $request->input('minimum_age_mode') === 'above_age'
                ),
                'nullable',
                'integer',
                'min:1',
                'max:120',
            ],
            'departure_time' => [
                Rule::requiredIf($request->input('category') === 'package'),
                'nullable',
                'string',
                'max:100',
            ],
            'group_size_label' => [
                'nullable',
                'string',
                'max:100',
            ],
            'pricing_group_size_label' => [
                Rule::requiredIf($request->input('category') === 'package'),
                'nullable',
                'array',
                'min:1',
                'max:20',
            ],
            'pricing_group_size_label.*' => ['nullable', 'string', 'max:100'],
            'pricing_malaysia_adult_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_malaysia_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_malaysia_child_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_malaysia_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_adult_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_international_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_child_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_international_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'tour_code' => [
                Rule::requiredIf($request->input('category') === 'package'),
                'nullable',
                'string',
                'max:50',
                'regex:/^(DT|OT)-UEH[A-Z0-9]+$/',
                Rule::unique('products', 'tour_code'),
            ],
            'malaysia_adult_price_myr' => [Rule::requiredIf($request->input('category') !== 'package'), 'nullable', 'numeric', 'min:0'],
            'malaysia_child_price_myr' => [Rule::requiredIf($request->input('category') !== 'package'), 'nullable', 'numeric', 'min:0'],
            'international_adult_price_myr' => [Rule::requiredIf($request->input('category') !== 'package'), 'nullable', 'numeric', 'min:0'],
            'international_child_price_myr' => [Rule::requiredIf($request->input('category') !== 'package'), 'nullable', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:0', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
            'is_discounted' => ['nullable', 'boolean'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->storeProductImage($request->file('image'));
        }

        $galleryImages = $validated['category'] === 'transport'
            ? $this->parseGalleryImageUrls($validated['gallery_images'] ?? null)
            : [];

        if ($request->hasFile('gallery_image_files')) {
            $galleryImages = array_merge(
                $galleryImages,
                $this->storeProductGalleryImages($request->file('gallery_image_files')),
            );
        }

        if ($validated['category'] === 'package') {
            $pricingTiers = $this->buildPackagePricingTiers($validated);
            $pricingSummary = $this->summarizePackagePricingTiers($pricingTiers);
            $validated['duration'] = ($validated['package_type'] ?? null) === 'Day Trip'
                ? ($validated['duration_detail'] ?? 'Day Trip')
                : (string) ($validated['package_type'] ?? '');
            $validated['minimum_age'] = ($validated['minimum_age_mode'] ?? 'no_limit') === 'above_age'
                ? 'Above '.(int) ($validated['minimum_age_years'] ?? 0).' years old'
                : 'No limit';
            $validated['pricing_tiers'] = $pricingTiers;
            $validated['group_size_label'] = $pricingSummary['group_size_label'];
            $validated['price_myr'] = $pricingSummary['price_myr'];
            $validated['malaysia_adult_price_myr'] = $pricingSummary['malaysia_adult_price_myr'];
            $validated['malaysia_child_price_myr'] = $pricingSummary['malaysia_child_price_myr'];
            $validated['international_adult_price_myr'] = $pricingSummary['international_adult_price_myr'];
            $validated['international_child_price_myr'] = $pricingSummary['international_child_price_myr'];
        }

        unset(
            $validated['package_type'],
            $validated['duration_detail'],
            $validated['minimum_age_mode'],
            $validated['minimum_age_years'],
            $validated['pricing_group_size_label'],
            $validated['pricing_malaysia_adult_price_myr'],
            $validated['pricing_malaysia_child_price_myr'],
            $validated['pricing_international_adult_price_myr'],
            $validated['pricing_international_child_price_myr'],
        );

        $legacyItineraryText = trim((string) ($validated['itinerary_items_text'] ?? ''));
        $structuredItinerary = $this->buildStructuredItinerary($validated);
        unset(
            $validated['itinerary_items_text'],
            $validated['itinerary_day_number'],
            $validated['itinerary_time'],
            $validated['itinerary_activity'],
            $validated['itinerary_notes'],
        );

        $productPayload = array_merge($validated, [
            'price_myr' => $validated['price_myr'] ?? $validated['malaysia_adult_price_myr'],
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
        $productPayload['description'] = (string) ($validated['description'] ?? '');

        if ($validated['category'] === 'package' && $legacyItineraryText !== '') {
            $productPayload['itinerary_items'] = $this->normalizeMultilineEntries($legacyItineraryText);
        }

        Product::create($productPayload);

        return back()->with('success', 'Product saved successfully.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $request->merge([
            'image_url' => $this->normalizeOptionalUrl($request->input('image_url')),
            'tour_code' => $product->category === 'package'
                ? $this->resolveTourCode(
                    (string) $request->input('tour_code', ''),
                    $this->isDayTripPackage((string) $request->input('duration', $product->duration), (string) $product->tour_code),
                    'products',
                    $product->id,
                    [],
                    (string) $product->tour_code
                )
                : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'dropoff_location' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:2000'],
            'image_url' => $this->productImageUrlRules(),
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'existing_gallery_images' => ['nullable', 'array', 'max:20'],
            'existing_gallery_images.*' => ['string', 'max:2048'],
            'gallery_image_files' => ['nullable', 'array', 'max:20'],
            'gallery_image_files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'itinerary_items_text' => ['nullable', 'string', 'max:5000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'package_type' => [
                Rule::requiredIf($product->category === 'package'),
                'nullable',
                'string',
                'in:'.implode(',', self::PACKAGE_DURATION_OPTIONS),
            ],
            'duration' => [
                Rule::requiredIf($product->category !== 'package'),
                'nullable',
                'string',
                'max:100',
            ],
            'duration_detail' => [
                Rule::requiredIf(
                    $product->category === 'package'
                    && $request->input('package_type') === 'Day Trip'
                ),
                'nullable',
                'string',
                'max:100',
            ],
            'minimum_age_mode' => [
                Rule::requiredIf($product->category === 'package'),
                'nullable',
                'string',
                'in:no_limit,above_age',
            ],
            'minimum_age_years' => [
                Rule::requiredIf(
                    $product->category === 'package'
                    && $request->input('minimum_age_mode') === 'above_age'
                ),
                'nullable',
                'integer',
                'min:1',
                'max:120',
            ],
            'departure_time' => [
                Rule::requiredIf($product->category === 'package'),
                'nullable',
                'string',
                'max:100',
            ],
            'group_size_label' => [
                'nullable',
                'string',
                'max:100',
            ],
            'pricing_group_size_label' => [
                Rule::requiredIf($product->category === 'package'),
                'nullable',
                'array',
                'min:1',
                'max:20',
            ],
            'pricing_group_size_label.*' => ['nullable', 'string', 'max:100'],
            'pricing_malaysia_adult_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_malaysia_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_malaysia_child_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_malaysia_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_adult_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_international_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_child_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_international_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'tour_code' => [
                Rule::requiredIf($product->category === 'package'),
                'nullable',
                'string',
                'max:50',
                'regex:/^(DT|OT)-UEH[A-Z0-9]+$/',
                Rule::unique('products', 'tour_code')->ignore($product->id),
            ],
            'malaysia_adult_price_myr' => [Rule::requiredIf($product->category !== 'package'), 'nullable', 'numeric', 'min:0'],
            'malaysia_child_price_myr' => [Rule::requiredIf($product->category !== 'package'), 'nullable', 'numeric', 'min:0'],
            'international_adult_price_myr' => [Rule::requiredIf($product->category !== 'package'), 'nullable', 'numeric', 'min:0'],
            'international_child_price_myr' => [Rule::requiredIf($product->category !== 'package'), 'nullable', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:0', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
            'is_discounted' => ['nullable', 'boolean'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

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

        if ($product->category === 'package') {
            $pricingTiers = $this->buildPackagePricingTiers($validated);
            $pricingSummary = $this->summarizePackagePricingTiers($pricingTiers);
            $validated['duration'] = ($validated['package_type'] ?? null) === 'Day Trip'
                ? ($validated['duration_detail'] ?? $product->duration ?? 'Day Trip')
                : (string) ($validated['package_type'] ?? $product->duration);
            $validated['minimum_age'] = ($validated['minimum_age_mode'] ?? 'no_limit') === 'above_age'
                ? 'Above '.(int) ($validated['minimum_age_years'] ?? 0).' years old'
                : 'No limit';
            $validated['pricing_tiers'] = $pricingTiers;
            $validated['group_size_label'] = $pricingSummary['group_size_label'];
            $validated['price_myr'] = $pricingSummary['price_myr'];
            $validated['malaysia_adult_price_myr'] = $pricingSummary['malaysia_adult_price_myr'];
            $validated['malaysia_child_price_myr'] = $pricingSummary['malaysia_child_price_myr'];
            $validated['international_adult_price_myr'] = $pricingSummary['international_adult_price_myr'];
            $validated['international_child_price_myr'] = $pricingSummary['international_child_price_myr'];
        }

        unset(
            $validated['package_type'],
            $validated['duration_detail'],
            $validated['minimum_age_mode'],
            $validated['minimum_age_years'],
            $validated['pricing_group_size_label'],
            $validated['pricing_malaysia_adult_price_myr'],
            $validated['pricing_malaysia_child_price_myr'],
            $validated['pricing_international_adult_price_myr'],
            $validated['pricing_international_child_price_myr'],
        );

        $legacyItineraryText = trim((string) ($validated['itinerary_items_text'] ?? ''));
        $structuredItinerary = $this->buildStructuredItinerary($validated);
        unset(
            $validated['itinerary_items_text'],
            $validated['itinerary_day_number'],
            $validated['itinerary_time'],
            $validated['itinerary_activity'],
            $validated['itinerary_notes'],
        );

        $productPayload = array_merge($validated, [
            'price_myr' => $validated['price_myr'] ?? $validated['malaysia_adult_price_myr'],
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
        $productPayload['description'] = (string) ($validated['description'] ?? $product->description ?? '');

        if ($product->category === 'package' && $legacyItineraryText !== '') {
            $productPayload['itinerary_items'] = $this->normalizeMultilineEntries($legacyItineraryText);
        }

        $product->update($productPayload);

        return back()->with('success', 'Product updated successfully.');
    }

    private function normalizeTourCode(string $tourCode, bool $isDayTrip): string
    {
        $cleanCode = strtoupper(trim($tourCode));
        $cleanCode = preg_replace('/\s+/', '', $cleanCode) ?? $cleanCode;
        $cleanCode = str_replace(['_', '.'], '-', $cleanCode);
        $cleanCode = preg_replace('/^(DT|OT)-?UEH/i', '', $cleanCode) ?? $cleanCode;
        $cleanCode = preg_replace('/^UEH/i', '', $cleanCode) ?? $cleanCode;
        $suffix = preg_replace('/[^A-Z0-9]/', '', $cleanCode) ?? '';

        return ($isDayTrip ? 'DT-UEH' : 'OT-UEH').$suffix;
    }

    private function resolveTourCode(
        string $tourCode,
        bool $isDayTrip,
        string $table,
        ?int $ignoreId = null,
        array $reservedCodes = [],
        ?string $existingTourCode = null
    ): string {
        $normalizedCode = $this->normalizeTourCode($tourCode, $isDayTrip);
        $prefix = $isDayTrip ? 'DT-UEH' : 'OT-UEH';

        if ($normalizedCode !== $prefix) {
            return $normalizedCode;
        }

        if (filled($existingTourCode)) {
            $existingNormalizedCode = $this->normalizeTourCode((string) $existingTourCode, $isDayTrip);

            if ($existingNormalizedCode !== $prefix) {
                return $existingNormalizedCode;
            }
        }

        return $this->generateNextTourCode($isDayTrip, $table, $ignoreId, $reservedCodes);
    }

    private function generateNextTourCode(bool $isDayTrip, string $table, ?int $ignoreId = null, array $reservedCodes = []): string
    {
        $prefix = $isDayTrip ? 'DT-UEH' : 'OT-UEH';
        $query = DB::table($table)->select('tour_code')->where('tour_code', 'like', $prefix.'%');

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        $usedCodes = collect($reservedCodes)
            ->merge($query->pluck('tour_code')->all())
            ->filter(fn ($code) => filled($code))
            ->map(fn ($code) => $this->normalizeTourCode((string) $code, $isDayTrip))
            ->unique()
            ->values();

        $nextNumber = $usedCodes
            ->map(function ($code) use ($prefix) {
                $suffix = substr((string) $code, strlen($prefix));

                return ctype_digit($suffix) ? (int) $suffix : null;
            })
            ->filter(fn ($value) => $value !== null)
            ->max();

        $counter = max(1, ((int) $nextNumber) + 1);

        do {
            $candidate = $prefix.str_pad((string) $counter, 3, '0', STR_PAD_LEFT);
            $counter++;
        } while ($usedCodes->contains($candidate));

        return $candidate;
    }

    private function isDayTripPackage(string $label, ?string $tourCode = null): bool
    {
        $normalizedLabel = strtolower(trim($label));
        $compactLabel = preg_replace('/\s+/', '', $normalizedLabel) ?? $normalizedLabel;

        if (is_string($tourCode) && str_starts_with(strtoupper(trim($tourCode)), 'DT-UEH')) {
            return true;
        }

        return str_contains($normalizedLabel, 'day trip')
            || str_contains($compactLabel, '1day');
    }

    private function importPackagesFromAdminWorkbook(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet): int
    {
        $entryRows = $this->readAdminTemplateSheetRows($spreadsheet, '01 Package Entry');

        if ($entryRows === []) {
            throw ValidationException::withMessages([
                'package_import_file' => 'The package entry sheet is empty. Fill in at least one package row.',
            ]);
        }

        $pricingRows = collect($this->readAdminTemplateSheetRows($spreadsheet, '02 Group Pricing'))->groupBy('package_id_ref');
        $itineraryRows = collect($this->readAdminTemplateSheetRows($spreadsheet, '03 Itinerary'))->groupBy('package_id_ref');
        $detailsRows = collect($this->readAdminTemplateSheetRows($spreadsheet, '04 Package Details'))->keyBy('package_id_ref');
        $contentRows = collect($this->readAdminTemplateSheetRows($spreadsheet, '05 Other Content'))->keyBy('package_id_ref');
        $optionalRows = collect($this->readAdminTemplateSheetRows($spreadsheet, '06 Optional Activities'))->groupBy('package_id_ref');
        $imageRows = collect($this->readAdminTemplateSheetRows($spreadsheet, '07 Images'))->groupBy('package_id_ref');

        $seenCodes = [];
        $importCount = 0;

        DB::transaction(function () use (
            $entryRows,
            $pricingRows,
            $itineraryRows,
            $detailsRows,
            $contentRows,
            $optionalRows,
            $imageRows,
            &$seenCodes,
            &$importCount
        ) {
            foreach ($entryRows as $entryRow) {
                $rowLabel = '01 Package Entry row '.($entryRow['_row'] ?? '?');
                $importData = $this->buildAdminWorkbookImportData(
                    $entryRow,
                    $pricingRows->get($entryRow['package_id_ref'] ?? '', collect())->all(),
                    $itineraryRows->get($entryRow['package_id_ref'] ?? '', collect())->all(),
                    $detailsRows->get($entryRow['package_id_ref'] ?? ''),
                    $contentRows->get($entryRow['package_id_ref'] ?? ''),
                    $optionalRows->get($entryRow['package_id_ref'] ?? '', collect())->all(),
                    $imageRows->get($entryRow['package_id_ref'] ?? '', collect())->all(),
                );

                $validator = Validator::make(
                    $importData,
                    $this->packageImportValidationRules(),
                    $this->packageImportValidationMessages($rowLabel)
                );

                if ($validator->fails()) {
                    throw ValidationException::withMessages($validator->errors()->toArray());
                }

                $validatedImportData = array_merge(
                    $validator->validated(),
                    Arr::only($importData, [
                        'itinerary_day_number',
                        'itinerary_time',
                        'itinerary_activity',
                        'itinerary_notes',
                        'package_details',
                        'service_inclusions',
                        'tour_highlights',
                        'recommended_attire',
                        'things_to_know',
                        'travel_tips',
                        'optional_activities',
                        'gallery_images',
                    ])
                );

                $validatedImportData['tour_code'] = $this->resolveTourCode(
                    (string) ($validatedImportData['tour_code'] ?? ''),
                    $this->isDayTripPackage((string) $validatedImportData['package_type'], null),
                    'packages',
                    null,
                    $seenCodes
                );

                if (in_array($validatedImportData['tour_code'], $seenCodes, true)) {
                    throw ValidationException::withMessages([
                        'package_import_file' => $rowLabel.': duplicate tour code '.$validatedImportData['tour_code'].' was found in the same import file.',
                    ]);
                }

                $seenCodes[] = $validatedImportData['tour_code'];

                if (Package::query()->where('tour_code', $validatedImportData['tour_code'])->exists()) {
                    throw ValidationException::withMessages([
                        'package_import_file' => $rowLabel.': the tour code '.$validatedImportData['tour_code'].' already exists.',
                    ]);
                }

                $packagePayload = $this->buildImportedPackagePayload($validatedImportData, $rowLabel);
                Package::create($packagePayload);
                $importCount++;
            }
        });

        if ($importCount === 0) {
            throw ValidationException::withMessages([
                'package_import_file' => 'No package rows were found to import from the admin workbook.',
            ]);
        }

        return $importCount;
    }

    private function importPackagesFromEasyWorkbook(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet): int
    {
        $rows = $this->readSimpleImportSheetRows($spreadsheet, 'Package Upload');

        if ($rows === []) {
            throw ValidationException::withMessages([
                'package_import_file' => 'The Package Upload sheet is empty. Fill in at least one package row.',
            ]);
        }

        $seenCodes = [];
        $importCount = 0;

        DB::transaction(function () use ($rows, &$seenCodes, &$importCount) {
            foreach ($rows as $row) {
                $rowLabel = 'Package Upload row '.($row['_row'] ?? '?');
                $importData = $this->buildEasyWorkbookImportData($row);

                $validator = Validator::make(
                    $importData,
                    $this->packageImportValidationRules(),
                    $this->packageImportValidationMessages($rowLabel)
                );

                if ($validator->fails()) {
                    throw ValidationException::withMessages($validator->errors()->toArray());
                }

                $validatedImportData = array_merge(
                    $validator->validated(),
                    Arr::only($importData, [
                        'itinerary_day_number',
                        'itinerary_time',
                        'itinerary_activity',
                        'itinerary_notes',
                        'package_details',
                        'service_inclusions',
                    ])
                );

                $validatedImportData['tour_code'] = $this->resolveTourCode(
                    (string) ($validatedImportData['tour_code'] ?? ''),
                    $this->isDayTripPackage((string) $validatedImportData['package_type'], null),
                    'packages',
                    null,
                    $seenCodes
                );

                if (in_array($validatedImportData['tour_code'], $seenCodes, true)) {
                    throw ValidationException::withMessages([
                        'package_import_file' => $rowLabel.': duplicate tour code '.$validatedImportData['tour_code'].' was found in the same import file.',
                    ]);
                }

                $seenCodes[] = $validatedImportData['tour_code'];

                if (Package::query()->where('tour_code', $validatedImportData['tour_code'])->exists()) {
                    throw ValidationException::withMessages([
                        'package_import_file' => $rowLabel.': the tour code '.$validatedImportData['tour_code'].' already exists.',
                    ]);
                }

                $packagePayload = $this->buildImportedPackagePayload($validatedImportData, $rowLabel);
                Package::create($packagePayload);
                $importCount++;
            }
        });

        if ($importCount === 0) {
            throw ValidationException::withMessages([
                'package_import_file' => 'No package rows were found to import from the easy workbook.',
            ]);
        }

        return $importCount;
    }

    private function readSimpleImportSheetRows(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, string $sheetName): array
    {
        $sheet = $spreadsheet->getSheetByName($sheetName);

        if (! $sheet) {
            return [];
        }

        $rows = $sheet->toArray('', true, true, false);

        if ($rows === []) {
            return [];
        }

        $headers = collect(array_shift($rows))
            ->map(fn ($value) => $this->normalizePackageImportHeader((string) $value))
            ->values()
            ->all();

        $mappedRows = [];

        foreach ($rows as $index => $rowValues) {
            $row = $this->combinePackageImportRow($headers, $rowValues);

            if ($this->packageImportRowIsEmpty($row)) {
                continue;
            }

            $row['_row'] = $index + 2;
            $mappedRows[] = $row;
        }

        return $mappedRows;
    }

    private function readAdminTemplateSheetRows(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, string $sheetName): array
    {
        $sheet = $spreadsheet->getSheetByName($sheetName);

        if (! $sheet) {
            return [];
        }

        $rows = $sheet->toArray('', true, true, false);
        $headerIndex = null;
        $headers = [];

        foreach ($rows as $rowIndex => $row) {
            $normalized = collect($row)
                ->map(fn ($value) => $this->normalizePackageImportHeader((string) $value))
                ->values()
                ->all();

            if (in_array('package_id_ref', $normalized, true)) {
                $headerIndex = $rowIndex;
                $headers = $normalized;
                break;
            }
        }

        if ($headerIndex === null) {
            return [];
        }

        $mappedRows = [];

        foreach (array_slice($rows, $headerIndex + 1) as $offset => $rowValues) {
            $row = $this->combinePackageImportRow($headers, $rowValues);

            if ($this->packageImportRowIsEmpty($row)) {
                continue;
            }

            $row['_row'] = $headerIndex + $offset + 2;
            $mappedRows[] = $row;
        }

        return $mappedRows;
    }

    private function buildAdminWorkbookImportData(
        array $entryRow,
        array $pricingRows,
        array $itineraryRows,
        mixed $detailsRow,
        mixed $contentRow,
        array $optionalRows,
        array $imageRows
    ): array {
        $mainImage = $this->normalizeImportedImageReference($entryRow['main_image_filename_or_url'] ?? null)
            ?? $this->normalizeImportedImageReference(collect($imageRows)->pluck('main_image_file')->first());

        $galleryImages = collect($imageRows)
            ->flatMap(function ($row) {
                return collect(range(1, 6))
                    ->map(fn ($index) => $row['gallery_image_'.$index] ?? null);
            })
            ->map(fn ($image) => $this->normalizeImportedImageReference($image))
            ->filter()
            ->values()
            ->all();

        $pricingRows = collect($pricingRows)->filter(function ($row) {
            return filled($row['group_size_no_pax'] ?? null)
                || filled($row['malaysia_adult_price_myr'] ?? null)
                || filled($row['malaysia_child_price_myr'] ?? null)
                || filled($row['international_adult_price_myr'] ?? null)
                || filled($row['international_child_price_myr'] ?? null);
        })->values();

        $itineraryRows = collect($itineraryRows)->filter(function ($row) {
            return filled($row['day_number'] ?? null)
                || filled($row['time'] ?? null)
                || filled($row['activity'] ?? null);
        })->values();

        $optionalRowsCollection = collect($optionalRows)->filter(function ($row) {
            return filled($row['activity'] ?? null)
                || filled($row['rate_price'] ?? null)
                || filled($row['optional_section_description'] ?? null);
        })->values();

        $showOptionalSection = $optionalRowsCollection->isNotEmpty()
            && ! $optionalRowsCollection->every(fn ($row) => strtolower(trim((string) ($row['show_optional_section'] ?? ''))) === 'no');

        $detailsRow = is_array($detailsRow) ? $detailsRow : [];
        $contentRow = is_array($contentRow) ? $contentRow : [];

        $packageDetails = [
            'includes' => $this->buildStructuredPackageDetailItemsFromPipeValue($detailsRow['includes'] ?? null, 'tick'),
            'excludes' => $this->buildStructuredPackageDetailItemsFromPipeValue($detailsRow['excludes'] ?? null, 'x'),
            'things_to_bring' => $this->buildStructuredPackageDetailItemsFromPipeValue($detailsRow['things_to_bring'] ?? null, 'exclamation'),
            'important_notes' => $this->buildStructuredPackageDetailItemsFromPipeValue($detailsRow['important_notes'] ?? null, 'exclamation'),
        ];

        return [
            'name' => $entryRow['name'] ?? '',
            'tour_code' => $entryRow['tour_code'] ?? '',
            'location' => $entryRow['location'] ?? '',
            'summary' => $entryRow['summary'] ?? '',
            'description' => $entryRow['description'] ?? '',
            'package_type' => $entryRow['package_type'] ?? '',
            'duration_detail' => $entryRow['duration_detail'] ?? '',
            'departure_time' => $entryRow['departure_time'] ?? '',
            'pickup_location' => $entryRow['pickup_location'] ?? '',
            'dropoff_location' => $entryRow['dropoff_location'] ?? '',
            'minimum_age_mode' => $entryRow['minimum_age_mode'] ?? 'no_limit',
            'minimum_age_years' => $entryRow['minimum_age_years'] ?? null,
            'capacity' => $entryRow['capacity'] ?? null,
            'pricing_group_size_label' => $pricingRows->pluck('group_size_no_pax')->map(fn ($value) => trim((string) $value))->all(),
            'pricing_malaysia_adult_price_myr' => $pricingRows->pluck('malaysia_adult_price_myr')->map(fn ($value) => $value === '' ? null : $value)->all(),
            'pricing_malaysia_child_price_myr' => $pricingRows->pluck('malaysia_child_price_myr')->map(fn ($value) => $value === '' ? null : $value)->all(),
            'pricing_international_adult_price_myr' => $pricingRows->pluck('international_adult_price_myr')->map(fn ($value) => $value === '' ? null : $value)->all(),
            'pricing_international_child_price_myr' => $pricingRows->pluck('international_child_price_myr')->map(fn ($value) => $value === '' ? null : $value)->all(),
            'image_url' => $mainImage,
            'itinerary_day_number' => $itineraryRows->pluck('day_number')->map(fn ($value) => trim((string) $value))->all(),
            'itinerary_time' => $itineraryRows->pluck('time')->map(fn ($value) => trim((string) $value))->all(),
            'itinerary_activity' => $itineraryRows->pluck('activity')->map(fn ($value) => trim((string) $value))->all(),
            'itinerary_notes' => array_fill(0, $itineraryRows->count(), ''),
            'is_featured' => false,
            'is_top_choice' => false,
            'is_discounted' => false,
            'discount_percentage' => null,
            'is_active' => true,
            'gallery_images' => $galleryImages,
            'package_details' => $packageDetails,
            'service_inclusions' => $this->buildLegacyServiceInclusionsFromPackageDetails($packageDetails),
            'tour_highlights' => $this->buildStructuredRichTextContentFromPipeValue($contentRow['tour_highlights'] ?? null),
            'recommended_attire' => $this->buildStructuredRichTextContentFromPipeValue($contentRow['recommended_attire'] ?? null),
            'things_to_know' => $this->buildStructuredRichTextContentFromPipeValue($contentRow['things_you_should_know'] ?? null),
            'travel_tips' => $this->buildStructuredRichTextContentFromPipeValue($contentRow['useful_travel_tips'] ?? null),
            'optional_activities' => $showOptionalSection
                ? [
                    'description' => trim((string) ($optionalRowsCollection->pluck('optional_section_description')->first(fn ($value) => filled($value)) ?? '')),
                    'rows' => $this->buildStructuredOptionalActivityRows(
                        $optionalRowsCollection->pluck('activity')->map(fn ($value) => trim((string) $value))->all(),
                        $optionalRowsCollection->pluck('rate_price')->map(fn ($value) => trim((string) $value))->all(),
                    ),
                ]
                : [],
        ];
    }

    private function buildEasyWorkbookImportData(array $row): array
    {
        $minimumAge = trim((string) ($row['minimum_age'] ?? ''));
        $minimumAgeMode = str_starts_with(strtolower($minimumAge), 'above ') ? 'above_age' : 'no_limit';
        preg_match('/(\d+)/', $minimumAge, $minimumAgeMatches);

        $itineraryDayNumbers = $this->parsePackageImportList($row['itinerary_day_number'] ?? '');
        $itineraryTimes = $this->parsePackageImportList($row['itinerary_time'] ?? '');
        $itineraryActivities = $this->parsePackageImportList($row['itinerary_activity'] ?? '');
        $itinerarySegments = collect();

        if ($itineraryDayNumbers !== [] || $itineraryTimes !== [] || $itineraryActivities !== []) {
            $rowCount = max(count($itineraryDayNumbers), count($itineraryTimes), count($itineraryActivities));
            $itinerarySegments = collect(range(0, max(0, $rowCount - 1)))
                ->map(function (int $index) use ($itineraryDayNumbers, $itineraryTimes, $itineraryActivities) {
                    $dayNumber = trim((string) ($itineraryDayNumbers[$index] ?? ''));
                    $time = trim((string) ($itineraryTimes[$index] ?? ''));
                    $activity = trim((string) ($itineraryActivities[$index] ?? ''));

                    if ($dayNumber === '' && $time === '' && $activity === '') {
                        return null;
                    }

                    return [
                        'day_number' => $dayNumber,
                        'time' => $time,
                        'activity' => $activity,
                    ];
                })
                ->filter()
                ->values();
        } else {
            $itinerarySegments = collect($this->parsePackageImportList($row['itinerary'] ?? ''))
                ->map(function (string $segment) {
                    $segment = trim($segment);

                    if ($segment === '') {
                        return null;
                    }

                    $parts = preg_split('/\s+-\s+/', $segment, 2);

                    return [
                        'time' => trim((string) ($parts[0] ?? '')),
                        'activity' => trim((string) ($parts[1] ?? $segment)),
                    ];
                })
                ->filter()
                ->values();
        }

        $packageDetails = [
            'includes' => $this->buildStructuredPackageDetailItemsFromPipeValue($row['includes'] ?? null, 'tick'),
            'excludes' => $this->buildStructuredPackageDetailItemsFromPipeValue($row['excludes'] ?? null, 'x'),
            'things_to_bring' => $this->buildStructuredPackageDetailItemsFromPipeValue(
                $row['things_to_bring'] ?? $row['recommended_attire'] ?? null,
                'exclamation'
            ),
            'important_notes' => $this->buildStructuredPackageDetailItemsFromPipeValue($row['important_notes'] ?? null, 'exclamation'),
        ];

        return [
            'name' => $row['package_name'] ?? '',
            'tour_code' => $row['tour_code'] ?? '',
            'location' => $row['location'] ?? '',
            'summary' => $row['summary'] ?? '',
            'description' => $row['description'] ?? '',
            'package_type' => $row['package_type'] ?? '',
            'duration_detail' => $this->isDayTripPackage((string) ($row['package_type'] ?? ''), (string) ($row['tour_code'] ?? ''))
                ? ($row['duration'] ?? '')
                : '',
            'departure_time' => $row['departure_time'] ?? '',
            'pickup_location' => $row['pickup_location'] ?? '',
            'dropoff_location' => $row['dropoff_location'] ?? '',
            'minimum_age_mode' => $minimumAgeMode,
            'minimum_age_years' => $minimumAgeMode === 'above_age' ? ($minimumAgeMatches[1] ?? null) : null,
            'capacity' => $this->normalizeOptionalImportInteger($row['capacity'] ?? null),
            'pricing_group_size_label' => $this->parsePackageImportList($row['group_sizes'] ?? ''),
            'pricing_malaysia_adult_price_myr' => $this->parsePackageImportNumericList($row['malaysia_adult_prices'] ?? ''),
            'pricing_malaysia_child_price_myr' => $this->parsePackageImportNumericList($row['malaysia_child_prices'] ?? ''),
            'pricing_international_adult_price_myr' => $this->parsePackageImportNumericList($row['international_adult_prices'] ?? ''),
            'pricing_international_child_price_myr' => $this->parsePackageImportNumericList($row['international_child_prices'] ?? ''),
            'image_url' => null,
            'itinerary_day_number' => $itinerarySegments->pluck('day_number')->filter(fn ($value) => trim((string) $value) !== '')->isNotEmpty()
                ? $itinerarySegments->pluck('day_number')->map(fn ($value) => trim((string) $value))->all()
                : $this->buildEasyWorkbookDayNumbers($itinerarySegments->count(), (string) ($row['package_type'] ?? ''), (string) ($row['duration'] ?? '')),
            'itinerary_time' => $itinerarySegments->pluck('time')->all(),
            'itinerary_activity' => $itinerarySegments->pluck('activity')->all(),
            'itinerary_notes' => array_fill(0, $itinerarySegments->count(), ''),
            'is_featured' => false,
            'is_top_choice' => false,
            'is_discounted' => false,
            'discount_percentage' => null,
            'is_active' => true,
            'package_details' => $packageDetails,
            'service_inclusions' => $this->buildLegacyServiceInclusionsFromPackageDetails($packageDetails),
            'tour_highlights' => $this->buildStructuredRichTextContentFromPipeValue($row['tour_highlights'] ?? null),
            'recommended_attire' => $this->buildStructuredRichTextContentFromPipeValue($row['recommended_attire'] ?? null),
            'things_to_know' => $this->buildStructuredRichTextContentFromPipeValue($row['things_you_should_know'] ?? null),
        ];
    }

    private function buildEasyWorkbookDayNumbers(int $rowCount, string $packageType, string $duration): array
    {
        if ($rowCount < 1) {
            return [];
        }

        $normalizedPackageType = strtolower(trim($packageType));
        $normalizedDuration = strtolower(trim($duration));
        $dayCount = 1;

        if (preg_match('/(\d+)\s*d/i', $packageType, $matches) || preg_match('/(\d+)\s*d/i', $duration, $matches)) {
            $dayCount = max(1, (int) $matches[1]);
        } elseif (str_contains($normalizedPackageType, 'day trip') || str_contains($normalizedDuration, 'hour')) {
            $dayCount = 1;
        }

        if ($dayCount === 1) {
            return array_fill(0, $rowCount, 'Day 1');
        }

        $rowsPerDay = (int) ceil($rowCount / $dayCount);

        return collect(range(0, $rowCount - 1))
            ->map(fn (int $index) => 'Day '.min($dayCount, (int) floor($index / max(1, $rowsPerDay)) + 1))
            ->all();
    }

    private function normalizeImportedImageReference(mixed $value): ?string
    {
        $value = trim((string) ($value ?? ''));

        if ($value === '') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) || str_starts_with($value, '/storage/')) {
            return $value;
        }

        return null;
    }

    private function buildStructuredPackageDetailItemsFromPipeValue(?string $value, string $defaultSymbol): array
    {
        $items = $this->parsePackageImportTextList($value ?? '');
        $items = array_values(array_filter($items, fn ($item) => $item !== ''));

        if ($items === []) {
            return [];
        }

        $allowedSymbols = match ($defaultSymbol) {
            'x' => ['x', 'round'],
            'exclamation' => ['exclamation', 'round'],
            default => ['tick', 'round'],
        };

        return $this->buildStructuredPackageDetailItems(
            array_fill(0, count($items), $defaultSymbol),
            $items,
            $allowedSymbols,
            $defaultSymbol
        );
    }

    private function buildStructuredRichTextContentFromPipeValue(?string $value): array
    {
        $items = $this->parsePackageImportTextList($value ?? '');
        $items = array_values(array_filter($items, fn ($item) => $item !== ''));

        if ($items === []) {
            return [];
        }

        if (count($items) === 1) {
            return $this->buildStructuredRichTextContent($items[0]);
        }

        $html = '<ul>'.collect($items)->map(fn ($item) => '<li>'.e($item).'</li>')->implode('').'</ul>';

        return $this->buildStructuredRichTextContent($html);
    }

    private function normalizePackageImportHeader(string $value): string
    {
        $normalized = Str::of($value)
            ->lower()
            ->replace(['/', '-', ' '], '_')
            ->replaceMatches('/[^a-z0-9_]/', '')
            ->trim('_')
            ->value();

        return $normalized;
    }

    private function combinePackageImportRow(array $headers, array $rowValues): array
    {
        $row = [];

        foreach ($headers as $index => $header) {
            if ($header === '') {
                continue;
            }

            $row[$header] = is_string($rowValues[$index] ?? null)
                ? trim((string) $rowValues[$index])
                : $rowValues[$index];
        }

        return $row;
    }

    private function packageImportRowIsEmpty(array $row): bool
    {
        return collect($row)->every(function ($value) {
            if (is_bool($value) || is_numeric($value)) {
                return false;
            }

            return trim((string) $value) === '';
        });
    }

    private function normalizePackageImportRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[$key] = is_string($value) ? trim($value) : $value;
        }

        foreach ([
            'pricing_group_size_label',
            'pricing_malaysia_adult_price_myr',
            'pricing_malaysia_child_price_myr',
            'pricing_international_adult_price_myr',
            'pricing_international_child_price_myr',
        ] as $field) {
            $normalized[$field] = $this->parsePackageImportList($normalized[$field] ?? '');
        }

        foreach ([
            'itinerary_day_number',
            'itinerary_time',
            'itinerary_activity',
            'itinerary_notes',
        ] as $field) {
            $normalized[$field] = $this->parsePackageImportList($normalized[$field] ?? '');
        }

        $normalized['capacity'] = $this->normalizeOptionalImportInteger($normalized['capacity'] ?? null);

        if (
            $normalized['capacity'] === null
            && blank($normalized['image_url'] ?? null)
            && filled($row['capacity'] ?? null)
            && filter_var((string) $row['capacity'], FILTER_VALIDATE_URL)
        ) {
            $normalized['image_url'] = trim((string) $row['capacity']);
        }

        if (! empty($normalized['itinerary_items_text'])) {
            $normalized['itinerary_items_text'] = collect(explode('|', (string) $normalized['itinerary_items_text']))
                ->map(fn ($item) => trim($item))
                ->filter()
                ->implode(PHP_EOL);
        }

        foreach (['is_featured', 'is_top_choice', 'is_discounted', 'is_active'] as $field) {
            $normalized[$field] = $this->normalizePackageImportBoolean($normalized[$field] ?? null);
        }

        return $normalized;
    }

    private function normalizeOptionalImportInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value) && (string) (int) $value === trim((string) $value)) {
            return (int) $value;
        }

        return null;
    }

    private function parsePackageImportList(mixed $value): array
    {
        return collect(explode('|', (string) $value))
            ->map(fn ($item) => trim($item))
            ->values()
            ->all();
    }

    private function parsePackageImportTextList(mixed $value): array
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", (string) $value);

        return collect(preg_split('/\||\n/u', $normalized) ?: [])
            ->map(fn ($item) => trim((string) $item))
            ->values()
            ->all();
    }

    private function parsePackageImportNumericList(mixed $value): array
    {
        return collect($this->parsePackageImportList($value))
            ->map(function ($item) {
                $item = trim((string) $item);

                return $item === '' ? null : $item;
            })
            ->all();
    }

    private function normalizePackageImportBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'y'], true);
    }

    private function packageImportValidationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tour_code' => ['nullable', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'package_type' => ['required', 'string', 'in:'.implode(',', self::PACKAGE_DURATION_OPTIONS)],
            'duration_detail' => ['nullable', 'string', 'max:100'],
            'departure_time' => ['required', 'string', 'max:100'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'dropoff_location' => ['nullable', 'string', 'max:255'],
            'minimum_age_mode' => ['required', 'string', 'in:no_limit,above_age'],
            'minimum_age_years' => ['nullable', 'integer', 'min:1', 'max:120'],
            'capacity' => ['nullable', 'integer', 'min:0', 'max:500'],
            'pricing_group_size_label' => ['required', 'array', 'min:1', 'max:20'],
            'pricing_group_size_label.*' => ['required', 'string', 'max:100'],
            'pricing_malaysia_adult_price_myr' => ['required', 'array', 'min:1', 'max:20'],
            'pricing_malaysia_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_malaysia_child_price_myr' => ['required', 'array', 'min:1', 'max:20'],
            'pricing_malaysia_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_adult_price_myr' => ['required', 'array', 'min:1', 'max:20'],
            'pricing_international_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_child_price_myr' => ['required', 'array', 'min:1', 'max:20'],
            'pricing_international_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'itinerary_items_text' => ['nullable', 'string', 'max:5000'],
            'itinerary_day_number' => ['nullable', 'array', 'max:31'],
            'itinerary_day_number.*' => ['nullable', 'string', 'max:50'],
            'itinerary_time' => ['nullable', 'array', 'max:31'],
            'itinerary_time.*' => ['nullable', 'string', 'max:100'],
            'itinerary_activity' => ['nullable', 'array', 'max:31'],
            'itinerary_activity.*' => ['nullable', 'string', 'max:1000'],
            'itinerary_notes' => ['nullable', 'array', 'max:31'],
            'itinerary_notes.*' => ['nullable', 'string', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
            'is_discounted' => ['nullable', 'boolean'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    private function packageImportValidationMessages(string|int $rowNumber): array
    {
        return [
            'name.required' => 'Row '.$rowNumber.': name is required.',
            'location.required' => 'Row '.$rowNumber.': location is required.',
            'summary.required' => 'Row '.$rowNumber.': summary is required.',
            'package_type.required' => 'Row '.$rowNumber.': package_type is required.',
            'package_type.in' => 'Row '.$rowNumber.': package_type must be Day Trip, 2D1N, 3D2N, or 4D3N.',
            'departure_time.required' => 'Row '.$rowNumber.': departure_time is required.',
            'minimum_age_mode.required' => 'Row '.$rowNumber.': minimum_age_mode is required.',
            'minimum_age_mode.in' => 'Row '.$rowNumber.': minimum_age_mode must be no_limit or above_age.',
            'pricing_group_size_label.required' => 'Row '.$rowNumber.': pricing_group_size_label is required.',
            'image_url.url' => 'Row '.$rowNumber.': image_url must be a valid URL.',
        ];
    }

    private function buildImportedPackagePayload(array $importData, string|int $rowNumber): array
    {
        if (
            ($importData['package_type'] ?? null) === 'Day Trip'
            && blank($importData['duration_detail'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'package_import_file' => 'Row '.$rowNumber.': Day Trip rows must include duration_detail.',
            ]);
        }

        if (
            ($importData['minimum_age_mode'] ?? 'no_limit') === 'above_age'
            && blank($importData['minimum_age_years'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'package_import_file' => 'Row '.$rowNumber.': rows using minimum_age_mode above_age must include minimum_age_years.',
            ]);
        }

        $pricingCounts = [
            count($importData['pricing_group_size_label'] ?? []),
            count($importData['pricing_malaysia_adult_price_myr'] ?? []),
            count($importData['pricing_malaysia_child_price_myr'] ?? []),
            count($importData['pricing_international_adult_price_myr'] ?? []),
            count($importData['pricing_international_child_price_myr'] ?? []),
        ];

        if (count(array_unique($pricingCounts)) !== 1) {
            throw ValidationException::withMessages([
                'package_import_file' => 'Row '.$rowNumber.': pricing columns must all have the same number of values.',
            ]);
        }

        $request = new Request([
            'is_featured' => $importData['is_featured'] ?? false,
            'is_top_choice' => $importData['is_top_choice'] ?? false,
            'is_discounted' => $importData['is_discounted'] ?? false,
            'is_active' => array_key_exists('is_active', $importData) ? $importData['is_active'] : true,
        ]);

        return $this->buildPackagePayload($importData, $request, []);
    }

    private function packageTemplateNotes(): array
    {
        return [
            'Use one row per package.',
            'Keep the header row exactly as downloaded.',
            'Do not move values into the wrong column. capacity is numeric only, and image_url must be the full URL column.',
            'For pricing columns, separate multiple pricing tiers with the | character.',
            'For itinerary columns, keep day and time in separate columns and use the | character to separate rows.',
            'Allowed package_type values are Day Trip, 2D1N, 3D2N, and 4D3N.',
            'Use minimum_age_mode as no_limit or above_age.',
            'Use yes/no, true/false, or 1/0 for boolean columns.',
            'Leave discount_percentage blank when is_discounted is no.',
            'Blank adult or child price cells are allowed, but each pricing row must still have at least one price.',
            'Older files using itinerary_items_text can still import, but the new template uses itinerary_day_number, itinerary_time, itinerary_activity, and itinerary_notes.',
        ];
    }

    private function easyPackageUploadTemplateColumns(): array
    {
        return [
            ['header' => 'package_name', 'required' => 'Yes', 'description' => 'Package name.', 'sample' => 'Package Name Here'],
            ['header' => 'location', 'required' => 'Yes', 'description' => 'Main destination or area.', 'sample' => 'Semporna, Sabah'],
            ['header' => 'summary', 'required' => 'Yes', 'description' => 'Short package summary.', 'sample' => 'Short package summary here'],
            ['header' => 'description', 'required' => 'No', 'description' => 'Full package description.', 'sample' => 'Full package description here'],
            ['header' => 'package_type', 'required' => 'Yes', 'description' => 'Allowed values: Day Trip, 2D1N, 3D2N, 4D3N.', 'sample' => 'Day Trip'],
            ['header' => 'duration', 'required' => 'Day Trip only', 'description' => 'Detailed duration for day trips.', 'sample' => '8 Hours'],
            ['header' => 'departure_time', 'required' => 'Yes', 'description' => 'Main departure time text.', 'sample' => '8:00 AM'],
            ['header' => 'pickup_location', 'required' => 'No', 'description' => 'Optional pickup location.', 'sample' => 'Hotel pickup / Jetty'],
            ['header' => 'dropoff_location', 'required' => 'No', 'description' => 'Optional drop-off location.', 'sample' => 'Hotel drop-off / Jetty'],
            ['header' => 'capacity', 'required' => 'No', 'description' => 'Can be blank or 0.', 'sample' => '20'],
            ['header' => 'minimum_age', 'required' => 'Yes', 'description' => 'Use values like No Limit or Above 7.', 'sample' => 'No Limit'],
            ['header' => 'group_sizes', 'required' => 'Yes', 'description' => 'Use | between multiple pricing rows.', 'sample' => '1-2 Pax|3-5 Pax|6-10 Pax'],
            ['header' => 'malaysia_adult_prices', 'required' => 'Optional per tier', 'description' => 'Use | between pricing rows. Blank or 0 allowed.', 'sample' => '250|220|200'],
            ['header' => 'malaysia_child_prices', 'required' => 'Optional per tier', 'description' => 'Use | between pricing rows. Blank or 0 allowed.', 'sample' => '200|180|160'],
            ['header' => 'international_adult_prices', 'required' => 'Optional per tier', 'description' => 'Use | between pricing rows. Blank or 0 allowed.', 'sample' => '300|270|250'],
            ['header' => 'international_child_prices', 'required' => 'Optional per tier', 'description' => 'Use | between pricing rows. Blank or 0 allowed.', 'sample' => '250|230|210'],
            ['header' => 'itinerary_day_number', 'required' => 'No', 'description' => 'Use | between itinerary rows.', 'sample' => 'Day 1|Day 1|Day 1|Day 1|Day 1'],
            ['header' => 'itinerary_time', 'required' => 'No', 'description' => 'Keep time separate from day. Use | between rows.', 'sample' => '08:00 AM|09:00 AM|10:00 AM|12:00 PM|04:00 PM'],
            ['header' => 'itinerary_activity', 'required' => 'No', 'description' => 'Main activity text for each itinerary row.', 'sample' => 'Pickup|Depart jetty|Activity 1|Lunch|Return'],
            ['header' => 'includes', 'required' => 'No', 'description' => 'Use | to create separate bullet items automatically.', 'sample' => 'Hotel pickup|Boat transfer|Lunch|Guide'],
            ['header' => 'excludes', 'required' => 'No', 'description' => 'Use | to create separate bullet items automatically.', 'sample' => 'Personal expenses|Travel insurance'],
            ['header' => 'important_notes', 'required' => 'No', 'description' => 'Use | to create separate bullet items automatically.', 'sample' => 'Subject to weather|Minimum 2 pax required'],
            ['header' => 'tour_highlights', 'required' => 'No', 'description' => 'Use | to create separate bullet items automatically.', 'sample' => 'Island hopping|Snorkelling|Beautiful beach'],
            ['header' => 'recommended_attire', 'required' => 'No', 'description' => 'Use | to create separate bullet items automatically.', 'sample' => 'Comfortable clothes|Swimwear|Slippers/Sandals'],
            ['header' => 'things_you_should_know', 'required' => 'No', 'description' => 'Use | to create separate bullet items automatically.', 'sample' => 'Bring passport/IC|Arrive 15 minutes early'],
        ];
    }

    private function easyPackageUploadTemplateHeaders(): array
    {
        return array_column($this->easyPackageUploadTemplateColumns(), 'header');
    }

    private function easyPackageUploadTemplateSampleRow(): array
    {
        return array_column($this->easyPackageUploadTemplateColumns(), 'sample');
    }

    private function easyPackageUploadTemplateNotes(): array
    {
        return [
            'tour_code is not needed because the system assigns it automatically.',
            'Keep the header row exactly as downloaded.',
            'Use | to separate multiple pricing rows or multiple bullet items inside a cell.',
            'Adult or child prices may be blank or 0, but each pricing row should still have at least one price.',
            'Capacity can be blank or 0.',
            'Put itinerary day values in itinerary_day_number, time values in itinerary_time, and matching text in itinerary_activity.',
            'Allowed package_type values are Day Trip, 2D1N, 3D2N, and 4D3N.',
            'minimum_age accepts values like No Limit or Above 7.',
        ];
    }

    private function packageTemplateExampleRow(): array
    {
        return [
            'Kudat Tommy Place 2D1N',
            'Kudat / Tip Of Borneo, Sabah',
            '2D1N private Kudat getaway with stay, sunset and snorkeling.',
            'Includes return land transfer, accommodation, meals, boat snorkeling and activities.',
            '2D1N',
            '',
            '13:00',
            'Kota Kinabalu hotel/hostel/lodge lobby',
            'Kota Kinabalu hotel/hostel/lodge lobby',
            'no_limit',
            '',
            '',
            '1 pax',
            '620',
            '',
            '620',
            '',
            'https://sabahbooking.com/holiday-59',
            'Day 1|Day 1|Day 2|Day 2',
            '13:00|18:30|07:30|12:00',
            'Pickup from hotel|Sunset viewing and check in|Breakfast and snorkeling|Return to Kota Kinabalu',
            'Proceed to Kudat|Free and easy|American breakfast|Hotel drop-off',
            'no',
            'no',
            'no',
            '',
            'yes',
        ];
    }

    private function buildPackagePricingTiers(array $validated): array
    {
        $groupLabels = $validated['pricing_group_size_label'] ?? [];
        $malaysiaAdults = $validated['pricing_malaysia_adult_price_myr'] ?? [];
        $malaysiaChildren = $validated['pricing_malaysia_child_price_myr'] ?? [];
        $internationalAdults = $validated['pricing_international_adult_price_myr'] ?? [];
        $internationalChildren = $validated['pricing_international_child_price_myr'] ?? [];
        $rowCount = max(
            count($groupLabels),
            count($malaysiaAdults),
            count($malaysiaChildren),
            count($internationalAdults),
            count($internationalChildren),
        );

        $pricingTiers = collect(range(0, max(0, $rowCount - 1)))
            ->map(function (int $index) use ($groupLabels, $malaysiaAdults, $malaysiaChildren, $internationalAdults, $internationalChildren) {
                $groupSizeLabel = trim((string) ($groupLabels[$index] ?? ''));
                $malaysiaAdultPrice = $malaysiaAdults[$index] ?? null;
                $malaysiaChildPrice = $malaysiaChildren[$index] ?? null;
                $internationalAdultPrice = $internationalAdults[$index] ?? null;
                $internationalChildPrice = $internationalChildren[$index] ?? null;

                $isEmptyRow = $groupSizeLabel === ''
                    && ($malaysiaAdultPrice === null || $malaysiaAdultPrice === '')
                    && ($malaysiaChildPrice === null || $malaysiaChildPrice === '')
                    && ($internationalAdultPrice === null || $internationalAdultPrice === '')
                    && ($internationalChildPrice === null || $internationalChildPrice === '');

                if ($isEmptyRow) {
                    return null;
                }

                $hasAnyPrice = collect([
                    $this->normalizeOptionalPackagePrice($malaysiaAdultPrice),
                    $this->normalizeOptionalPackagePrice($malaysiaChildPrice),
                    $this->normalizeOptionalPackagePrice($internationalAdultPrice),
                    $this->normalizeOptionalPackagePrice($internationalChildPrice),
                ])->contains(fn ($price) => $price !== null);

                if ($groupSizeLabel === '' || ! $hasAnyPrice) {
                    throw ValidationException::withMessages([
                        'pricing_group_size_label' => 'Each group size row must include the pax label and at least one price.',
                    ]);
                }

                return [
                    'group_size_label' => $groupSizeLabel,
                    'malaysia_adult_price_myr' => $this->normalizeOptionalPackagePrice($malaysiaAdultPrice),
                    'malaysia_child_price_myr' => $this->normalizeOptionalPackagePrice($malaysiaChildPrice),
                    'international_adult_price_myr' => $this->normalizeOptionalPackagePrice($internationalAdultPrice),
                    'international_child_price_myr' => $this->normalizeOptionalPackagePrice($internationalChildPrice),
                ];
            })
            ->filter()
            ->values()
            ->all();

        if ($pricingTiers === []) {
            throw ValidationException::withMessages([
                'pricing_group_size_label' => 'Add at least one group size pricing row for this package.',
            ]);
        }

        return $pricingTiers;
    }

    private function summarizePackagePricingTiers(array $pricingTiers): array
    {
        $startingTier = collect($pricingTiers)
            ->sortBy(fn (array $tier) => $this->lowestPackageTierPrice($tier) ?? INF)
            ->first();

        return [
            'group_size_label' => (string) ($startingTier['group_size_label'] ?? ''),
            'price_myr' => $this->lowestPackageTierPrice($startingTier) ?? 0,
            'malaysia_adult_price_myr' => $this->normalizeOptionalPackagePrice($startingTier['malaysia_adult_price_myr'] ?? null),
            'malaysia_child_price_myr' => $this->normalizeOptionalPackagePrice($startingTier['malaysia_child_price_myr'] ?? null),
            'international_adult_price_myr' => $this->normalizeOptionalPackagePrice($startingTier['international_adult_price_myr'] ?? null),
            'international_child_price_myr' => $this->normalizeOptionalPackagePrice($startingTier['international_child_price_myr'] ?? null),
        ];
    }

    private function normalizeOptionalPackagePrice(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return round((float) $value, 2);
    }

    private function lowestPackageTierPrice(array $tier): ?float
    {
        return collect([
            $this->normalizeOptionalPackagePrice($tier['malaysia_adult_price_myr'] ?? null),
            $this->normalizeOptionalPackagePrice($tier['malaysia_child_price_myr'] ?? null),
            $this->normalizeOptionalPackagePrice($tier['international_adult_price_myr'] ?? null),
            $this->normalizeOptionalPackagePrice($tier['international_child_price_myr'] ?? null),
        ])->filter(fn ($price) => $price !== null)->min();
    }

    private function packageValidationRules(?Package $package = null, bool $isUpdate = false): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'dropoff_location' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:2000'],
            'image_url' => $this->productImageUrlRules(),
            'image' => [$isUpdate ? 'nullable' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'existing_gallery_images' => ['nullable', 'array', 'max:20'],
            'existing_gallery_images.*' => ['string', 'max:2048'],
            'gallery_image_files' => ['nullable', 'array', 'max:20'],
            'gallery_image_files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'itinerary_items_text' => ['nullable', 'string', 'max:5000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'package_type' => ['required', 'string', 'in:'.implode(',', self::PACKAGE_DURATION_OPTIONS)],
            'duration_detail' => [
                Rule::requiredIf($isUpdate ? request('package_type') === 'Day Trip' : request('package_type') === 'Day Trip'),
                'nullable',
                'string',
                'max:100',
            ],
            'minimum_age_mode' => ['required', 'string', 'in:no_limit,above_age'],
            'minimum_age_years' => [
                Rule::requiredIf(request('minimum_age_mode') === 'above_age'),
                'nullable',
                'integer',
                'min:1',
                'max:120',
            ],
            'departure_time' => ['required', 'string', 'max:100'],
            'group_size_label' => ['nullable', 'string', 'max:100'],
            'pricing_group_size_label' => ['required', 'array', 'min:1', 'max:20'],
            'pricing_group_size_label.*' => ['nullable', 'string', 'max:100'],
            'pricing_malaysia_adult_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_malaysia_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_malaysia_child_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_malaysia_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_adult_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_international_adult_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'pricing_international_child_price_myr' => ['nullable', 'array', 'max:20'],
            'pricing_international_child_price_myr.*' => ['nullable', 'numeric', 'min:0'],
            'tour_code' => [
                'required',
                'string',
                'max:50',
                'regex:/^(DT|OT)-UEH[A-Z0-9]+$/',
                Rule::unique('packages', 'tour_code')->ignore($package?->id),
            ],
            'capacity' => ['nullable', 'integer', 'min:0', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_top_choice' => ['nullable', 'boolean'],
            'is_discounted' => ['nullable', 'boolean'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    private function buildPackagePayload(array $validated, Request $request, array $galleryImages, ?Package $package = null): array
    {
        $pricingTiers = $this->buildPackagePricingTiers($validated);
        $pricingSummary = $this->summarizePackagePricingTiers($pricingTiers);
        $validated['duration'] = ($validated['package_type'] ?? null) === 'Day Trip'
            ? ($validated['duration_detail'] ?? ($package?->duration ?? 'Day Trip'))
            : (string) ($validated['package_type'] ?? ($package?->duration ?? ''));
        $validated['minimum_age'] = ($validated['minimum_age_mode'] ?? 'no_limit') === 'above_age'
            ? 'Above '.(int) ($validated['minimum_age_years'] ?? 0).' years old'
            : 'No limit';
        $validated['pricing_tiers'] = $pricingTiers;
        $validated['group_size_label'] = $pricingSummary['group_size_label'];
        $validated['price_myr'] = $pricingSummary['price_myr'];
        $validated['malaysia_adult_price_myr'] = $pricingSummary['malaysia_adult_price_myr'];
        $validated['malaysia_child_price_myr'] = $pricingSummary['malaysia_child_price_myr'];
        $validated['international_adult_price_myr'] = $pricingSummary['international_adult_price_myr'];
        $validated['international_child_price_myr'] = $pricingSummary['international_child_price_myr'];

        unset(
            $validated['package_type'],
            $validated['duration_detail'],
            $validated['minimum_age_mode'],
            $validated['minimum_age_years'],
            $validated['pricing_group_size_label'],
            $validated['pricing_malaysia_adult_price_myr'],
            $validated['pricing_malaysia_child_price_myr'],
            $validated['pricing_international_adult_price_myr'],
            $validated['pricing_international_child_price_myr'],
            $validated['existing_gallery_images']
        );

        $legacyItineraryText = trim((string) ($validated['itinerary_items_text'] ?? ''));
        $structuredItinerary = $this->buildStructuredItinerary($validated);
        unset(
            $validated['itinerary_items_text'],
            $validated['itinerary_day_number'],
            $validated['itinerary_time'],
            $validated['itinerary_activity'],
            $validated['itinerary_notes'],
        );

        $payload = array_merge($validated, [
            'price_myr' => $validated['price_myr'] ?? $validated['malaysia_adult_price_myr'],
            'gallery_images' => $galleryImages,
            'capacity' => $validated['capacity'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_top_choice' => $request->boolean('is_top_choice'),
            'is_discounted' => $request->boolean('is_discounted'),
            'discount_percentage' => $request->boolean('is_discounted') ? ($validated['discount_percentage'] ?? 0) : null,
            'is_active' => $package ? $request->boolean('is_active', $package->is_active) : true,
        ]);
        $payload['description'] = (string) ($validated['description'] ?? ($package?->description ?? ''));

        if ($structuredItinerary !== []) {
            $payload['itinerary_items'] = $structuredItinerary;
        } elseif ($legacyItineraryText !== '') {
            $payload['itinerary_items'] = $this->normalizeMultilineEntries($legacyItineraryText);
        }

        return $payload;
    }

    public function updateProductItinerary(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->category === 'package', 404);

        $validated = $request->validate([
            'itinerary_day_number' => ['nullable', 'array', 'max:31'],
            'itinerary_day_number.*' => ['nullable', 'string', 'max:50'],
            'itinerary_time' => ['nullable', 'array', 'max:31'],
            'itinerary_time.*' => ['nullable', 'string', 'max:100'],
            'itinerary_activity' => ['nullable', 'array', 'max:31'],
            'itinerary_activity.*' => ['nullable', 'string', 'max:1000'],
            'itinerary_notes' => ['nullable', 'array', 'max:31'],
            'itinerary_notes.*' => ['nullable', 'string', 'max:1000'],
        ]);

        $product->update([
            'itinerary_items' => $this->buildStructuredItinerary($validated),
        ]);

        return back()->with('success', 'Package itinerary updated successfully.');
    }

    public function updateProductPackageDetails(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->category === 'package', 404);

        $validated = $request->validate([
            'package_detail_include_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_include_symbol.*' => ['nullable', 'string', 'in:tick,round'],
            'package_detail_include_value' => ['nullable', 'array', 'max:30'],
            'package_detail_include_value.*' => ['nullable', 'string', 'max:5000'],
            'package_detail_exclude_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_exclude_symbol.*' => ['nullable', 'string', 'in:x,round'],
            'package_detail_exclude_value' => ['nullable', 'array', 'max:30'],
            'package_detail_exclude_value.*' => ['nullable', 'string', 'max:5000'],
            'package_detail_bring_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_bring_symbol.*' => ['nullable', 'string', 'in:exclamation,round'],
            'package_detail_bring_value' => ['nullable', 'array', 'max:30'],
            'package_detail_bring_value.*' => ['nullable', 'string', 'max:5000'],
            'package_detail_note_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_note_symbol.*' => ['nullable', 'string', 'in:exclamation,round'],
            'package_detail_note_value' => ['nullable', 'array', 'max:30'],
            'package_detail_note_value.*' => ['nullable', 'string', 'max:5000'],
        ]);

        $packageDetails = [
            'includes' => $this->buildStructuredPackageDetailItems(
                $validated['package_detail_include_symbol'] ?? [],
                $validated['package_detail_include_value'] ?? [],
                ['tick', 'round'],
                'tick',
            ),
            'excludes' => $this->buildStructuredPackageDetailItems(
                $validated['package_detail_exclude_symbol'] ?? [],
                $validated['package_detail_exclude_value'] ?? [],
                ['x', 'round'],
                'x',
            ),
            'things_to_bring' => $this->buildStructuredPackageDetailItems(
                $validated['package_detail_bring_symbol'] ?? [],
                $validated['package_detail_bring_value'] ?? [],
                ['exclamation', 'round'],
                'exclamation',
            ),
            'important_notes' => $this->buildStructuredPackageDetailItems(
                $validated['package_detail_note_symbol'] ?? [],
                $validated['package_detail_note_value'] ?? [],
                ['exclamation', 'round'],
                'exclamation',
            ),
        ];

        $product->update([
            'package_details' => $packageDetails,
            'service_inclusions' => $this->buildLegacyServiceInclusionsFromPackageDetails($packageDetails),
        ]);

        return back()->with('success', 'Package details updated successfully.');
    }

    public function updateProductPackageContent(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->category === 'package', 404);

        $validated = $request->validate([
            'tour_highlights' => ['nullable', 'string', 'max:5000'],
            'recommended_attire' => ['nullable', 'string', 'max:5000'],
            'things_to_know' => ['nullable', 'string', 'max:5000'],
            'travel_tips' => ['nullable', 'string', 'max:5000'],
        ]);

        $product->update([
            'tour_highlights' => $this->buildStructuredRichTextContent($validated['tour_highlights'] ?? null),
            'recommended_attire' => $this->buildStructuredRichTextContent($validated['recommended_attire'] ?? null),
            'things_to_know' => $this->buildStructuredRichTextContent($validated['things_to_know'] ?? null),
            'travel_tips' => $this->buildStructuredRichTextContent($validated['travel_tips'] ?? null),
        ]);

        return back()->with('success', 'Other package content updated successfully.');
    }

    public function updateProductOptionalActivities(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->category === 'package', 404);

        $validated = $request->validate([
            'optional_activities_description' => ['nullable', 'string', 'max:3000'],
            'optional_activity_name' => ['nullable', 'array', 'max:20'],
            'optional_activity_name.*' => ['nullable', 'string', 'max:255'],
            'optional_activity_rate' => ['nullable', 'array', 'max:20'],
            'optional_activity_rate.*' => ['nullable', 'string', 'max:255'],
        ]);

        $product->update([
            'optional_activities' => [
                'description' => trim((string) ($validated['optional_activities_description'] ?? '')),
                'rows' => $this->buildStructuredOptionalActivityRows(
                    $validated['optional_activity_name'] ?? [],
                    $validated['optional_activity_rate'] ?? [],
                ),
            ],
        ]);

        return back()->with('success', 'Optional activities updated successfully.');
    }

    public function updateProductActive(Request $request, Product $product)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $product->update([
            'is_active' => (bool) $validated['is_active'],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $product->is_active,
                'message' => 'Product visibility updated successfully.',
            ]);
        }

        return back()->with('success', 'Product visibility updated successfully.');
    }

    public function destroyProduct(Request $request, Product $product)
    {
        $this->deleteManagedProductImage($product->image_url);
        $this->deleteManagedProductImages($product->gallery_images ?? []);
        $product->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
                'product_id' => $product->id,
            ]);
        }

        return back()->with('success', 'Product deleted successfully.');
    }

    public function storePackage(Request $request): RedirectResponse
    {
        $request->merge([
            'tour_code' => $this->resolveTourCode(
                (string) $request->input('tour_code', ''),
                $this->isDayTripPackage((string) $request->input('package_type', ''), null),
                'packages'
            ),
        ]);

        $validated = $request->validate($this->packageValidationRules(), [
            'tour_code.unique' => 'This tour code is already being used by another package. Please use a different code.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->storeProductImage($request->file('image'));
        }

        $galleryImages = [];
        if ($request->hasFile('gallery_image_files')) {
            $galleryImages = $this->storeProductGalleryImages($request->file('gallery_image_files'));
        }

        $packagePayload = $this->buildPackagePayload($validated, $request, $galleryImages);
        Package::create($packagePayload);

        return back()->with('success', 'Package saved successfully.');
    }

    public function updatePackage(Request $request, Package $package): RedirectResponse
    {
        $request->merge([
            'image_url' => $this->normalizeOptionalUrl($request->input('image_url')),
            'tour_code' => $this->resolveTourCode(
                (string) $request->input('tour_code', ''),
                $this->isDayTripPackage((string) $request->input('duration', $package->duration), (string) $package->tour_code),
                'packages',
                $package->id,
                [],
                (string) $package->tour_code
            ),
        ]);

        $validated = $request->validate(
            $this->packageValidationRules($package, true),
            ['tour_code.unique' => 'This tour code is already being used by another package. Please use a different code.']
        );

        if ($request->hasFile('image')) {
            $this->deleteManagedProductImage($package->image_url);
            $validated['image_url'] = $this->storeProductImage($request->file('image'));
        }

        $existingGalleryImages = collect($validated['existing_gallery_images'] ?? [])
            ->filter(fn ($imageUrl) => is_string($imageUrl) && filled($imageUrl))
            ->values()
            ->all();

        $removedGalleryImages = array_values(array_diff($package->gallery_images ?? [], $existingGalleryImages));
        $this->deleteManagedProductImages($removedGalleryImages);

        $galleryImages = $existingGalleryImages;
        if ($request->hasFile('gallery_image_files')) {
            $galleryImages = array_merge($galleryImages, $this->storeProductGalleryImages($request->file('gallery_image_files')));
        }

        $packagePayload = $this->buildPackagePayload($validated, $request, $galleryImages, $package);
        $package->update($packagePayload);

        return back()->with('success', 'Package updated successfully.');
    }

    public function updatePackageItinerary(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'itinerary_day_number' => ['nullable', 'array', 'max:31'],
            'itinerary_day_number.*' => ['nullable', 'string', 'max:50'],
            'itinerary_time' => ['nullable', 'array', 'max:31'],
            'itinerary_time.*' => ['nullable', 'string', 'max:100'],
            'itinerary_activity' => ['nullable', 'array', 'max:31'],
            'itinerary_activity.*' => ['nullable', 'string', 'max:1000'],
            'itinerary_notes' => ['nullable', 'array', 'max:31'],
            'itinerary_notes.*' => ['nullable', 'string', 'max:1000'],
        ]);

        $package->update(['itinerary_items' => $this->buildStructuredItinerary($validated)]);

        return back()->with('success', 'Package itinerary updated successfully.');
    }

    public function updatePackageDetails(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'package_detail_include_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_include_symbol.*' => ['nullable', 'string', 'in:tick,round'],
            'package_detail_include_value' => ['nullable', 'array', 'max:30'],
            'package_detail_include_value.*' => ['nullable', 'string', 'max:5000'],
            'package_detail_exclude_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_exclude_symbol.*' => ['nullable', 'string', 'in:x,round'],
            'package_detail_exclude_value' => ['nullable', 'array', 'max:30'],
            'package_detail_exclude_value.*' => ['nullable', 'string', 'max:5000'],
            'package_detail_bring_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_bring_symbol.*' => ['nullable', 'string', 'in:exclamation,round'],
            'package_detail_bring_value' => ['nullable', 'array', 'max:30'],
            'package_detail_bring_value.*' => ['nullable', 'string', 'max:5000'],
            'package_detail_note_symbol' => ['nullable', 'array', 'max:30'],
            'package_detail_note_symbol.*' => ['nullable', 'string', 'in:exclamation,round'],
            'package_detail_note_value' => ['nullable', 'array', 'max:30'],
            'package_detail_note_value.*' => ['nullable', 'string', 'max:5000'],
        ]);

        $packageDetails = [
            'includes' => $this->buildStructuredPackageDetailItems($validated['package_detail_include_symbol'] ?? [], $validated['package_detail_include_value'] ?? [], ['tick', 'round'], 'tick'),
            'excludes' => $this->buildStructuredPackageDetailItems($validated['package_detail_exclude_symbol'] ?? [], $validated['package_detail_exclude_value'] ?? [], ['x', 'round'], 'x'),
            'things_to_bring' => $this->buildStructuredPackageDetailItems($validated['package_detail_bring_symbol'] ?? [], $validated['package_detail_bring_value'] ?? [], ['exclamation', 'round'], 'exclamation'),
            'important_notes' => $this->buildStructuredPackageDetailItems($validated['package_detail_note_symbol'] ?? [], $validated['package_detail_note_value'] ?? [], ['exclamation', 'round'], 'exclamation'),
        ];

        $package->update([
            'package_details' => $packageDetails,
            'service_inclusions' => $this->buildLegacyServiceInclusionsFromPackageDetails($packageDetails),
        ]);

        return back()->with('success', 'Package details updated successfully.');
    }

    public function updatePackageContent(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'tour_highlights' => ['nullable', 'string', 'max:5000'],
            'recommended_attire' => ['nullable', 'string', 'max:5000'],
            'things_to_know' => ['nullable', 'string', 'max:5000'],
            'travel_tips' => ['nullable', 'string', 'max:5000'],
        ]);

        $package->update([
            'tour_highlights' => $this->buildStructuredRichTextContent($validated['tour_highlights'] ?? null),
            'recommended_attire' => $this->buildStructuredRichTextContent($validated['recommended_attire'] ?? null),
            'things_to_know' => $this->buildStructuredRichTextContent($validated['things_to_know'] ?? null),
            'travel_tips' => $this->buildStructuredRichTextContent($validated['travel_tips'] ?? null),
        ]);

        return back()->with('success', 'Other package content updated successfully.');
    }

    public function updatePackageOptionalActivities(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'optional_activities_description' => ['nullable', 'string', 'max:3000'],
            'optional_activity_name' => ['nullable', 'array', 'max:20'],
            'optional_activity_name.*' => ['nullable', 'string', 'max:255'],
            'optional_activity_rate' => ['nullable', 'array', 'max:20'],
            'optional_activity_rate.*' => ['nullable', 'string', 'max:255'],
        ]);

        $package->update([
            'optional_activities' => [
                'description' => trim((string) ($validated['optional_activities_description'] ?? '')),
                'rows' => $this->buildStructuredOptionalActivityRows(
                    $validated['optional_activity_name'] ?? [],
                    $validated['optional_activity_rate'] ?? [],
                ),
            ],
        ]);

        return back()->with('success', 'Optional activities updated successfully.');
    }

    public function updatePackageActive(Request $request, Package $package)
    {
        $validated = $request->validate(['is_active' => ['required', 'boolean']]);
        $package->update(['is_active' => (bool) $validated['is_active']]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $package->is_active,
                'message' => 'Package visibility updated successfully.',
            ]);
        }

        return back()->with('success', 'Package visibility updated successfully.');
    }

    public function destroyPackage(Request $request, Package $package)
    {
        $this->deleteManagedProductImage($package->image_url);
        $this->deleteManagedProductImages($package->gallery_images ?? []);
        $package->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully.',
                'product_id' => $package->id,
            ]);
        }

        return back()->with('success', 'Package deleted successfully.');
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

    private function parseGalleryImageUrls(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value) ?: [])
            ->map(fn ($imageUrl) => trim($imageUrl))
            ->filter(fn ($imageUrl) => filled($imageUrl))
            ->values()
            ->all();
    }

    private function normalizeMultilineEntries(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value) ?: [])
            ->map(fn ($item) => trim($item))
            ->filter(fn ($item) => filled($item))
            ->values()
            ->all();
    }

    private function buildStructuredItinerary(array $validated): array
    {
        $dayNumbers = $validated['itinerary_day_number'] ?? [];
        $times = $validated['itinerary_time'] ?? [];
        $activities = $validated['itinerary_activity'] ?? [];
        $notes = $validated['itinerary_notes'] ?? [];
        $rowCount = max(count($dayNumbers), count($times), count($activities), count($notes));

        return collect(range(0, max(0, $rowCount - 1)))
            ->map(function (int $index) use ($dayNumbers, $times, $activities, $notes) {
                $dayNumber = trim((string) ($dayNumbers[$index] ?? ''));
                $time = trim((string) ($times[$index] ?? ''));
                $activity = trim((string) ($activities[$index] ?? ''));
                $note = trim((string) ($notes[$index] ?? ''));

                if ($dayNumber === '' && $time === '' && $activity === '' && $note === '') {
                    return null;
                }

                return [
                    'day_number' => $dayNumber,
                    'time' => $time,
                    'activity' => $activity,
                    'notes' => $note,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildStructuredServiceInclusions(array $validated): array
    {
        $labels = $validated['service_inclusion_label'] ?? [];
        $values = $validated['service_inclusion_value'] ?? [];
        $rowCount = max(count($labels), count($values));

        return collect(range(0, max(0, $rowCount - 1)))
            ->map(function (int $index) use ($labels, $values) {
                $label = trim((string) ($labels[$index] ?? ''));
                $value = trim((string) ($values[$index] ?? ''));

                if ($label === '' && $value === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'value' => $value,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildStructuredPackageDetailItems(array $symbols, array $values, array $allowedSymbols, string $defaultSymbol): array
    {
        $rowCount = max(count($symbols), count($values));

        return collect(range(0, max(0, $rowCount - 1)))
            ->map(function (int $index) use ($symbols, $values, $allowedSymbols, $defaultSymbol) {
                $symbol = trim((string) ($symbols[$index] ?? $defaultSymbol));
                $value = (string) ($values[$index] ?? '');
                $sanitizedHtml = $this->sanitizePackageDetailHtml($value);
                $plainText = $this->extractPlainTextFromHtml($sanitizedHtml);

                if ($plainText === '') {
                    return null;
                }

                if (! in_array($symbol, $allowedSymbols, true)) {
                    $symbol = $defaultSymbol;
                }

                return [
                    'symbol' => $symbol,
                    'text' => $plainText,
                    'html' => $sanitizedHtml,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildStructuredTextList(array $values): array
    {
        return collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter(fn ($value) => $value !== '')
            ->values()
            ->all();
    }

    private function buildStructuredRichTextContent(?string $value): array
    {
        $sanitizedHtml = $this->sanitizePackageDetailHtml((string) ($value ?? ''));
        $plainText = $this->extractPlainTextFromHtml($sanitizedHtml);

        if ($plainText === '') {
            return [];
        }

        return [[
            'text' => $plainText,
            'html' => $sanitizedHtml,
        ]];
    }

    private function buildStructuredOptionalActivityRows(array $names, array $rates): array
    {
        $rowCount = max(count($names), count($rates));

        return collect(range(0, max(0, $rowCount - 1)))
            ->map(function (int $index) use ($names, $rates) {
                $name = trim((string) ($names[$index] ?? ''));
                $rate = trim((string) ($rates[$index] ?? ''));

                if ($name === '' && $rate === '') {
                    return null;
                }

                return [
                    'name' => $name,
                    'rate' => $rate,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildLegacyServiceInclusionsFromPackageDetails(array $packageDetails): array
    {
        $labelMap = [
            'includes' => 'Inclusion',
            'excludes' => 'Exclusion',
            'things_to_bring' => 'Things to Bring',
            'important_notes' => 'Important Notes',
        ];

        return collect($labelMap)
            ->flatMap(function (string $label, string $key) use ($packageDetails) {
                return collect($packageDetails[$key] ?? [])
                    ->filter(fn ($item) => is_array($item) && filled($item['text'] ?? null))
                    ->map(fn ($item) => [
                        'label' => $label,
                        'value' => (string) ($item['text'] ?? ''),
                    ]);
            })
            ->values()
            ->all();
    }

    private function sanitizePackageDetailHtml(string $html): string
    {
        $trimmed = trim($html);

        if ($trimmed === '') {
            return '';
        }

        $document = new \DOMDocument('1.0', 'UTF-8');
        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $wrappedHtml = '<div data-package-detail-root="1">'.$trimmed.'</div>';

        $document->loadHTML(
            mb_convert_encoding($wrappedHtml, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        $allowedTags = ['div', 'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li'];
        $allowedPointStyles = ['tick', 'round', 'x', 'warning'];

        $sanitizeNode = function (\DOMNode $node) use (&$sanitizeNode, $allowedTags, $allowedPointStyles): void {
            if ($node instanceof \DOMText) {
                return;
            }

            if (! $node instanceof \DOMElement) {
                $node->parentNode?->removeChild($node);

                return;
            }

            $tagName = Str::lower($node->tagName);

            if (! in_array($tagName, $allowedTags, true)) {
                $parent = $node->parentNode;

                if (! $parent) {
                    return;
                }

                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }

                $parent->removeChild($node);

                return;
            }

            foreach (iterator_to_array($node->attributes ?? []) as $attribute) {
                $attributeName = Str::lower($attribute->nodeName);

                if ($attributeName === 'data-point-style' && in_array($tagName, ['ul', 'ol'], true)) {
                    $attributeValue = trim((string) $attribute->nodeValue);

                    if (in_array($attributeValue, $allowedPointStyles, true)) {
                        continue;
                    }
                }

                $node->removeAttribute($attribute->nodeName);
            }

            foreach (iterator_to_array($node->childNodes) as $childNode) {
                $sanitizeNode($childNode);
            }

            if ($tagName === 'li') {
                $normalizedText = $this->stripPackageDetailLeadingMarker($node->textContent ?? '');

                if ($normalizedText === '') {
                    $node->parentNode?->removeChild($node);

                    return;
                }

                while ($node->firstChild) {
                    $node->removeChild($node->firstChild);
                }

                $node->appendChild($node->ownerDocument->createTextNode($normalizedText));
            }

            if (in_array($tagName, ['ul', 'ol'], true) && ! $node->getElementsByTagName('li')->length) {
                $node->parentNode?->removeChild($node);
            }
        };

        $root = $document->documentElement;
        $sanitizeNode($root);

        $html = '';

        foreach (iterator_to_array($root->childNodes) as $childNode) {
            $html .= $document->saveHTML($childNode);
        }

        return $this->stripPackageDetailLeadingMarkersFromHtml(trim($html));
    }

    private function extractPlainTextFromHtml(string $html): string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', str_replace("\xc2\xa0", ' ', $text)) ?? '';

        return $this->stripPackageDetailLeadingMarker(trim($text));
    }

    private function stripPackageDetailLeadingMarker(string $text): string
    {
        $normalized = preg_replace('/^\s*(?:[•●○◦▪▫✓✔✕✖✗❌⚠!]+|\d+[.)])\s*/u', '', $text) ?? $text;

        return trim($normalized);
    }

    private function stripPackageDetailLeadingMarkersFromHtml(string $html): string
    {
        $cleaned = preg_replace(
            '/(<(?:p|div|li)[^>]*>\s*(?:<(?:strong|b|em|i|u)[^>]*>\s*)*)(?:[•●○◦▪▫✓✔✕✖✗❌⚠!]+|\d+[.)])\s*/u',
            '$1',
            $html
        );

        return trim((string) ($cleaned ?? $html));
    }

    private function normalizeOptionalUrl(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : null;
    }

    private function productImageUrlRules(): array
    {
        return [
            'nullable',
            'string',
            'max:2048',
            function (string $attribute, mixed $value, \Closure $fail) {
                if (! is_string($value) || trim($value) === '') {
                    return;
                }

                $imageUrl = trim($value);

                if (str_starts_with($imageUrl, '/storage/')) {
                    return;
                }

                if (filter_var($imageUrl, FILTER_VALIDATE_URL) !== false) {
                    return;
                }

                $fail('The image url field must be a valid URL or a local storage path.');
            },
        ];
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

    public function storeHomeHeroSlide(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('home_hero_slides')) {
            return back()->withErrors([
                'home_hero_slides' => 'Homepage slider storage is not ready yet. Please run php artisan migrate first.',
            ])->withInput();
        }

        if (HomeHeroSlide::count() >= 5) {
            return back()->withErrors([
                'home_hero_slides' => 'You can only keep up to 5 homepage hero images at a time.',
            ])->withInput();
        }

        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . self::HOME_HERO_SLIDE_MAX_KB],
            'display_order' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $slide = HomeHeroSlide::create([
            'image_path' => $request->file('image')->store('home-hero-slides', 'public'),
            'display_order' => HomeHeroSlide::count() + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->resequenceHomeHeroSlides($slide, (int) ($validated['display_order'] ?? HomeHeroSlide::count()));

        return back()->with('success', 'Homepage hero image added successfully.');
    }

    public function updateHomeHeroSlide(Request $request, HomeHeroSlide $homeHeroSlide): RedirectResponse
    {
        if (! Schema::hasTable('home_hero_slides')) {
            return back()->withErrors([
                'home_hero_slides' => 'Homepage slider storage is not ready yet. Please run php artisan migrate first.',
            ])->withInput();
        }

        $validated = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . self::HOME_HERO_SLIDE_MAX_KB],
            'display_order' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $updates = [
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($homeHeroSlide->image_path) {
                Storage::disk('public')->delete($homeHeroSlide->image_path);
            }

            $updates['image_path'] = $request->file('image')->store('home-hero-slides', 'public');
        }

        $homeHeroSlide->update($updates);
        $this->resequenceHomeHeroSlides($homeHeroSlide->fresh(), (int) $validated['display_order']);

        return back()->with('success', 'Homepage hero image updated successfully.');
    }

    public function destroyHomeHeroSlide(HomeHeroSlide $homeHeroSlide): RedirectResponse
    {
        if ($homeHeroSlide->image_path) {
            Storage::disk('public')->delete($homeHeroSlide->image_path);
        }

        $homeHeroSlide->delete();
        $this->resequenceHomeHeroSlides();

        return back()->with('success', 'Homepage hero image removed successfully.');
    }

    public function storeBlogPost(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('blog_posts')) {
            return back()->withErrors([
                'blog_posts' => 'Blog storage is not ready yet. Please run php artisan migrate first.',
            ])->withInput();
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'credits' => ['nullable', 'string', 'max:2000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'social_media_url' => ['nullable', 'url', 'max:2048'],
            'video_url' => ['nullable', 'url', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'sections' => ['nullable', 'array'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.description' => ['nullable', 'string', 'max:20000'],
            'sections.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $coverImagePath = $request->hasFile('cover_image')
            ? $request->file('cover_image')->store('blog-covers', 'public')
            : null;
        $descriptionHtml = $this->sanitizeBlogHtml($validated['description']);
        $sectionItems = $this->prepareBlogSections($request);

        BlogPost::create($this->filterBlogPostAttributes([
            'title' => $validated['title'],
            'slug' => BlogPost::generateUniqueSlug($validated['title']),
            'destination' => $validated['destination'] ?? null,
            'author_name' => $validated['author_name'] ?? null,
            'description' => $descriptionHtml,
            'credits' => $validated['credits'] ?? null,
            'sections' => $sectionItems,
            'excerpt' => Str::limit(strip_tags($descriptionHtml), 500, ''),
            'content' => $descriptionHtml,
            'cover_image_path' => $coverImagePath,
            'social_media_url' => $validated['social_media_url'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'published_at' => $validated['published_at'] ?? now(),
            'is_published' => $request->boolean('is_published'),
        ]));

        return back()->with('success', 'Blog post saved successfully.');
    }

    public function updateBlogPost(Request $request, BlogPost $blogPost): RedirectResponse
    {
        if (! Schema::hasTable('blog_posts')) {
            return back()->withErrors([
                'blog_posts' => 'Blog storage is not ready yet. Please run php artisan migrate first.',
            ])->withInput();
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'credits' => ['nullable', 'string', 'max:2000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'social_media_url' => ['nullable', 'url', 'max:2048'],
            'video_url' => ['nullable', 'url', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'sections' => ['nullable', 'array'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.description' => ['nullable', 'string', 'max:20000'],
            'sections.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sections.*.existing_image_path' => ['nullable', 'string', 'max:2048'],
        ]);
        $descriptionHtml = $this->sanitizeBlogHtml($validated['description']);
        $existingSectionImages = collect($blogPost->sections ?? [])
            ->pluck('image_path')
            ->filter()
            ->values()
            ->all();
        $sectionItems = $this->prepareBlogSections($request, $blogPost);

        $updates = $this->filterBlogPostAttributes([
            'title' => $validated['title'],
            'slug' => BlogPost::generateUniqueSlug($validated['title'], $blogPost->id),
            'destination' => $validated['destination'] ?? null,
            'author_name' => $validated['author_name'] ?? null,
            'description' => $descriptionHtml,
            'credits' => $validated['credits'] ?? null,
            'sections' => $sectionItems,
            'excerpt' => Str::limit(strip_tags($descriptionHtml), 500, ''),
            'content' => $descriptionHtml,
            'social_media_url' => $validated['social_media_url'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'published_at' => $validated['published_at'] ?? now(),
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('cover_image')) {
            if ($blogPost->cover_image_path) {
                Storage::disk('public')->delete($blogPost->cover_image_path);
            }

            $updates['cover_image_path'] = $request->file('cover_image')->store('blog-covers', 'public');
        }

        $activeSectionImages = collect($sectionItems)
            ->pluck('image_path')
            ->filter()
            ->values()
            ->all();

        collect($existingSectionImages)
            ->diff($activeSectionImages)
            ->each(fn ($path) => Storage::disk('public')->delete($path));

        $blogPost->update($updates);

        return back()->with('success', 'Blog post updated successfully.');
    }

    public function destroyBlogPost(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->cover_image_path) {
            Storage::disk('public')->delete($blogPost->cover_image_path);
        }

        collect($blogPost->sections ?? [])
            ->pluck('image_path')
            ->filter()
            ->each(fn ($path) => Storage::disk('public')->delete($path));

        $blogPost->delete();

        return back()->with('success', 'Blog post deleted successfully.');
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255', 'email:rfc,dns'],
            'location' => ['required', 'string', 'max:255'],
            'trip_name' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'display_location' => ['required', 'in:landing,package'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        if (($validated['display_location'] ?? null) === 'package') {
            $package = Package::find($validated['package_id'] ?? null);

            if (! $package) {
                return back()->withErrors([
                    'package_id' => 'Please choose a valid package for this testimonial.',
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
            'is_featured' => $request->boolean('is_featured'),
            'product_id' => null,
            'package_id' => ($validated['display_location'] ?? 'landing') === 'package'
                ? $validated['package_id']
                : null,
        ]);

        return back()->with('success', 'Testimonial saved successfully.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255', 'email:rfc,dns'],
            'location' => ['required', 'string', 'max:255'],
            'trip_name' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'display_location' => ['required', 'in:landing,package'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        if (($validated['display_location'] ?? null) === 'package') {
            $package = Package::find($validated['package_id'] ?? null);

            if (! $package) {
                return back()->withErrors([
                    'package_id' => 'Please choose a valid package for this testimonial.',
                ])->withInput();
            }
        }

        $updates = [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'location' => $validated['location'],
            'trip_name' => $validated['trip_name'],
            'quote' => $validated['quote'],
            'rating' => $validated['rating'],
            'display_location' => $validated['display_location'],
            'product_id' => null,
            'package_id' => $validated['display_location'] === 'package'
                ? $validated['package_id']
                : null,
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
        $packageProducts = Package::latest()->get();
        $fixedTransportNames = collect(self::FIXED_TRANSPORT_PRODUCTS)->pluck('name');

        return [
            'products' => $products,
            'transportProducts' => $products
                ->where('category', 'transport')
                ->sortBy(fn ($product) => ($fixedTransportNames->search($product->name) !== false ? $fixedTransportNames->search($product->name) : 9999))
                ->values(),
            'packageProducts' => $packageProducts->values(),
            'newsFeatures' => NewsFeature::latest()->get(),
            'homeHeroSlides' => Schema::hasTable('home_hero_slides')
                ? HomeHeroSlide::query()->orderBy('display_order')->orderBy('id')->get()
                : collect(),
            'blogPosts' => Schema::hasTable('blog_posts')
                ? BlogPost::latest('published_at')->latest()->get()
                : collect(),
            'testimonials' => Testimonial::with(['product', 'package'])->latest()->get(),
            'staffMembers' => Schema::hasTable('staff')
                ? Staff::query()->orderBy('sort_order')->orderBy('name')->get()
                : collect(),
            'companyCertifications' => Schema::hasTable('company_certifications')
                ? CompanyCertification::query()->orderBy('sort_order')->orderBy('title')->get()
                : collect(),
            'bookings' => Booking::activeBookings()->with(['user', 'product'])->latest()->get(),
            'enquiries' => Booking::enquiries()->with(['user', 'product'])->latest()->get(),
            'adminUser' => auth()->user(),
            'adminUsers' => User::where('role', 'admin')->orderBy('name')->get(),
            'stats' => [
                'products' => Product::count(),
                'bookings' => Booking::activeBookings()->count(),
                'pendingBookings' => Booking::activeBookings()->where('status', 'pending')->count(),
                'enquiries' => Booking::enquiries()->count(),
                'customers' => \App\Models\User::where('role', 'customer')->count(),
                'promos' => NewsFeature::count(),
                'blogPosts' => Schema::hasTable('blog_posts') ? BlogPost::count() : 0,
                'testimonials' => Testimonial::count(),
                'staff' => Schema::hasTable('staff') ? Staff::count() : 0,
            ],
        ];
    }

    private function validateStaff(Request $request, ?Staff $staff = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => [
                $staff ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:'.self::STAFF_PHOTO_MAX_KB,
            ],
        ]);
    }

    private function validateCompanyCertification(Request $request, ?CompanyCertification $companyCertification = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'certificate_source' => ['required', 'in:file,link'],
            'logo' => [
                $companyCertification?->logo_path ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'certificate' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:20480',
            ],
            'certificate_link' => ['nullable', 'url:http,https', 'max:2000'],
        ]);

        $needsFile = $validated['certificate_source'] === 'file'
            && ! $request->hasFile('certificate')
            && ! $companyCertification?->certificate_path;

        $needsLink = $validated['certificate_source'] === 'link'
            && blank($validated['certificate_link'] ?? null)
            && ! $companyCertification?->certificate_link;

        if ($needsFile || $needsLink) {
            throw ValidationException::withMessages([
                $needsFile ? 'certificate' : 'certificate_link' => $needsFile
                    ? 'Please upload a certificate file.'
                    : 'Please provide a certificate link.',
            ]);
        }

        return $validated;
    }

    private function resequenceHomeHeroSlides(?HomeHeroSlide $targetSlide = null, ?int $requestedPosition = null): void
    {
        if (! Schema::hasTable('home_hero_slides')) {
            return;
        }

        $slides = HomeHeroSlide::query()
            ->when($targetSlide, fn ($query) => $query->whereKeyNot($targetSlide->id))
            ->orderBy('display_order')
            ->orderBy('id')
            ->get()
            ->values();

        if ($targetSlide) {
            $position = max(1, min($requestedPosition ?? ($slides->count() + 1), $slides->count() + 1));
            $slides->splice($position - 1, 0, [$targetSlide]);
        }

        $slides
            ->values()
            ->each(function (HomeHeroSlide $slide, int $index) {
                $expectedOrder = $index + 1;

                if ((int) $slide->display_order !== $expectedOrder) {
                    $slide->updateQuietly([
                        'display_order' => $expectedOrder,
                    ]);
                }
            });
    }

    private function filterBlogPostAttributes(array $attributes): array
    {
        if (! Schema::hasTable('blog_posts')) {
            return [];
        }

        $availableColumns = array_flip(Schema::getColumnListing('blog_posts'));

        return collect($attributes)
            ->filter(fn ($value, $key) => array_key_exists($key, $availableColumns))
            ->all();
    }

    private function prepareBlogSections(Request $request, ?BlogPost $blogPost = null): array
    {
        $rawSections = $request->input('sections', []);

        if (! is_array($rawSections)) {
            return [];
        }

        $sections = [];

        foreach ($rawSections as $index => $section) {
            if (! is_array($section)) {
                continue;
            }

            $title = trim((string) ($section['title'] ?? ''));
            $description = $this->sanitizeBlogHtml((string) ($section['description'] ?? ''));
            $existingImagePath = is_string($section['existing_image_path'] ?? null)
                ? trim((string) $section['existing_image_path'])
                : null;

            $imagePath = $existingImagePath ?: null;

            if ($request->hasFile("sections.$index.image")) {
                if ($existingImagePath) {
                    Storage::disk('public')->delete($existingImagePath);
                }

                $imagePath = $request->file("sections.$index.image")->store('blog-sections', 'public');
            }

            if ($title === '' && $description === '' && blank($imagePath)) {
                continue;
            }

            $sections[] = [
                'title' => $title !== '' ? $title : null,
                'description' => $description !== '' ? $description : null,
                'image_path' => $imagePath,
            ];
        }

        return $sections;
    }

    private function sanitizeBlogHtml(?string $html): string
    {
        $clean = strip_tags((string) $html, '<p><br><strong><b><em><i><ul><ol><li><h2><h3><blockquote><a>');
        $clean = str_replace(["\r\n", "\r"], "\n", $clean);
        $clean = preg_replace("/\n{3,}/", "\n\n", $clean);

        return str_replace("\n", "<br>\n", trim($clean));
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
