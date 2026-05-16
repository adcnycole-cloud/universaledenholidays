import './bootstrap';

const currencyRates = {
    MYR: 1,
    KRW: 308.5,
    USD: 0.21,
    SGD: 0.28,
    CNY: 1.716,
};

const currencySymbols = {
    MYR: 'RM ',
    KRW: 'KRW ',
    USD: '$',
    SGD: 'S$',
    CNY: 'CNY ',
};

const formatPrice = (amount, currency) => {
    return `${currencySymbols[currency] ?? ''}${new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount)}`;
};

const updatePrices = (currency) => {
    document.querySelectorAll('.currency-price').forEach((element) => {
        const myr = Number(element.dataset.myr || 0);
        const converted = myr * (currencyRates[currency] ?? 1);
        element.textContent = formatPrice(converted, currency);
    });
};

const currencySelector = document.querySelector('#currency-selector');

if (currencySelector) {
    updatePrices(currencySelector.value);
    currencySelector.addEventListener('change', (event) => {
        updatePrices(event.target.value);
    });
}

const productSelector = document.querySelector('#product_id');
const serviceTypeSelector = document.querySelector('#service_type');
const bookingCurrencySelector = document.querySelector('#currency_code');
const bookingPriceTitle = document.querySelector('#booking-price-title');
const bookingMalaysiaAdult = document.querySelector('#booking-malaysia-adult');
const bookingMalaysiaChild = document.querySelector('#booking-malaysia-child');
const bookingInternationalAdult = document.querySelector('#booking-international-adult');
const bookingInternationalChild = document.querySelector('#booking-international-child');
const bookingMalaysiaAdultsInput = document.querySelector('#malaysian_adults');
const bookingMalaysiaKidsInput = document.querySelector('#malaysian_kids');
const bookingInternationalAdultsInput = document.querySelector('#international_adults');
const bookingInternationalKidsInput = document.querySelector('#international_kids');
const bookingMalaysiaTotal = document.querySelector('#booking-malaysia-total');
const bookingMalaysiaCount = document.querySelector('#booking-malaysia-count');
const bookingInternationalTotal = document.querySelector('#booking-international-total');
const bookingInternationalCount = document.querySelector('#booking-international-count');
const bookingGrandTotal = document.querySelector('#booking-grand-total');
const bookingGrandTotalMyr = document.querySelector('#booking-grand-total-myr');

const formatMyr = (amount) => {
    return amount ? `RM ${new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(amount))}` : '--';
};

const updateBookingMarketPrices = () => {
    if (!productSelector || !bookingPriceTitle) {
        return;
    }

    const selectedOption = productSelector.options[productSelector.selectedIndex];

    if (!selectedOption || !selectedOption.value) {
        bookingPriceTitle.textContent = 'Select a product to view pricing';
        bookingMalaysiaAdult.textContent = '--';
        bookingMalaysiaChild.textContent = '--';
        bookingInternationalAdult.textContent = '--';
        bookingInternationalChild.textContent = '--';
        return;
    }

    bookingPriceTitle.textContent = `${selectedOption.dataset.name} pricing`;
    bookingMalaysiaAdult.textContent = formatMyr(selectedOption.dataset.malaysiaAdult);
    bookingMalaysiaChild.textContent = formatMyr(selectedOption.dataset.malaysiaChild);
    bookingInternationalAdult.textContent = formatMyr(selectedOption.dataset.internationalAdult);
    bookingInternationalChild.textContent = formatMyr(selectedOption.dataset.internationalChild);
};

const formatGuestLabel = (adultCount, childCount) => {
    const totalGuests = adultCount + childCount;

    if (totalGuests === 1) {
        return '1 guest';
    }

    return `${totalGuests} guests`;
};

const getCount = (input) => {
    return Math.max(Number(input?.value || 0), 0);
};

const updateBookingEstimate = () => {
    if (
        !productSelector
        || !bookingCurrencySelector
        || !bookingMalaysiaTotal
        || !bookingMalaysiaCount
        || !bookingInternationalTotal
        || !bookingInternationalCount
        || !bookingGrandTotal
        || !bookingGrandTotalMyr
    ) {
        return;
    }

    const selectedOption = productSelector.options[productSelector.selectedIndex];
    const currency = bookingCurrencySelector.value || 'MYR';

    if (!selectedOption || !selectedOption.value) {
        bookingMalaysiaTotal.textContent = '--';
        bookingMalaysiaCount.textContent = '0 guests';
        bookingInternationalTotal.textContent = '--';
        bookingInternationalCount.textContent = '0 guests';
        bookingGrandTotal.textContent = '--';
        bookingGrandTotalMyr.textContent = 'Base MYR total: RM 0.00';
        return;
    }

    const malaysiaAdults = getCount(bookingMalaysiaAdultsInput);
    const malaysiaKids = getCount(bookingMalaysiaKidsInput);
    const internationalAdults = getCount(bookingInternationalAdultsInput);
    const internationalKids = getCount(bookingInternationalKidsInput);

    const malaysiaSubtotalMyr = (malaysiaAdults * Number(selectedOption.dataset.malaysiaAdult || 0))
        + (malaysiaKids * Number(selectedOption.dataset.malaysiaChild || 0));
    const internationalSubtotalMyr = (internationalAdults * Number(selectedOption.dataset.internationalAdult || 0))
        + (internationalKids * Number(selectedOption.dataset.internationalChild || 0));
    const grandTotalMyr = malaysiaSubtotalMyr + internationalSubtotalMyr;
    const rate = currencyRates[currency] ?? 1;

    bookingMalaysiaTotal.textContent = formatPrice(malaysiaSubtotalMyr * rate, currency);
    bookingMalaysiaCount.textContent = formatGuestLabel(malaysiaAdults, malaysiaKids);
    bookingInternationalTotal.textContent = formatPrice(internationalSubtotalMyr * rate, currency);
    bookingInternationalCount.textContent = formatGuestLabel(internationalAdults, internationalKids);
    bookingGrandTotal.textContent = formatPrice(grandTotalMyr * rate, currency);
    bookingGrandTotalMyr.textContent = `Base MYR total: ${formatPrice(grandTotalMyr, 'MYR')}`;
};

if (productSelector && serviceTypeSelector) {
    const syncProductDetails = () => {
        const selectedOption = productSelector.options[productSelector.selectedIndex];
        const category = selectedOption?.dataset?.category;

        if (category) {
            serviceTypeSelector.value = category;
        }

        updateBookingMarketPrices();
        updateBookingEstimate();
    };

    productSelector.addEventListener('change', syncProductDetails);
    syncProductDetails();
}

[
    bookingMalaysiaAdultsInput,
    bookingMalaysiaKidsInput,
    bookingInternationalAdultsInput,
    bookingInternationalKidsInput,
].forEach((input) => {
    if (!input) {
        return;
    }

    input.addEventListener('input', updateBookingEstimate);
});

if (bookingCurrencySelector) {
    bookingCurrencySelector.addEventListener('change', updateBookingEstimate);
}

updateBookingEstimate();
