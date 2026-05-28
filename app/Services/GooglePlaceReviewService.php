<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePlaceReviewService
{
    public function getPlaceReviews(): array
    {
        if (! config('services.google_places.reviews_enabled')) {
            return $this->emptyPayload();
        }

        $apiKey = (string) config('services.google_places.api_key');
        $placeId = (string) config('services.google_places.place_id');
        $placeQuery = trim((string) config('services.google_places.place_query'));

        if ($apiKey === '') {
            return $this->emptyPayload();
        }

        if ($placeId === '' && $placeQuery !== '') {
            $placeId = $this->resolvePlaceIdFromQuery($apiKey, $placeQuery);
        }

        if ($placeId === '') {
            return $this->emptyPayload();
        }

        $cacheKey = 'google-place-reviews:'.$placeId;
        $cacheTtl = now()->addMinutes((int) config('services.google_places.cache_minutes', 360));

        return Cache::remember($cacheKey, $cacheTtl, function () use ($apiKey, $placeId) {
            try {
                $response = Http::timeout(8)
                    ->acceptJson()
                    ->withHeaders([
                        'X-Goog-Api-Key' => $apiKey,
                        'X-Goog-FieldMask' => 'displayName,rating,userRatingCount,reviews,googleMapsUri,photos',
                    ])
                    ->get('https://places.googleapis.com/v1/places/'.$placeId, [
                        'languageCode' => app()->getLocale(),
                    ])
                    ->throw()
                    ->json();
            } catch (\Throwable $exception) {
                Log::warning('Unable to load Google place reviews.', [
                    'place_id' => $placeId,
                    'message' => $exception->getMessage(),
                ]);

                return $this->emptyPayload();
            }

            $reviews = collect($response['reviews'] ?? [])
                ->map(function (array $review) use ($response) {
                    $authorName = trim((string) data_get($review, 'authorAttribution.displayName', ''));
                    $quote = trim((string) (data_get($review, 'text.text') ?: data_get($review, 'originalText.text', '')));
                    $rating = (int) ($review['rating'] ?? 0);

                    if ($authorName === '' || $quote === '' || $rating < 1) {
                        return null;
                    }

                    return [
                        'source' => 'google',
                        'source_label' => 'Google review',
                        'name' => $authorName,
                        'location' => 'Google Maps',
                        'trip_name' => (string) data_get($response, 'displayName.text', ''),
                        'quote' => $quote,
                        'rating' => min($rating, 5),
                        'profile_photo_url' => data_get($review, 'authorAttribution.photoUri')
                            ?: 'https://ui-avatars.com/api/?name='.urlencode($authorName).'&background=dc2626&color=ffffff&size=128&bold=true',
                        'published_label' => (string) ($review['relativePublishTimeDescription'] ?? ''),
                        'review_url' => data_get($review, 'authorAttribution.uri')
                            ?: ($review['googleMapsUri'] ?? ($response['googleMapsUri'] ?? null)),
                    ];
                })
                ->filter()
                ->take((int) config('services.google_places.reviews_limit', 3))
                ->values()
                ->all();

            $landscapePhoto = $this->resolveLandscapePhoto($apiKey, $response['photos'] ?? []);

            return [
                'place_name' => (string) data_get($response, 'displayName.text', ''),
                'rating' => isset($response['rating']) ? round((float) $response['rating'], 1) : null,
                'reviews_count' => (int) ($response['userRatingCount'] ?? 0),
                'place_url' => $response['googleMapsUri'] ?? null,
                'landscape_photo_url' => $landscapePhoto['photo_url'],
                'landscape_photo_attribution' => $landscapePhoto['attribution'],
                'reviews' => $reviews,
            ];
        });
    }

    private function emptyPayload(): array
    {
        return [
            'place_name' => '',
            'rating' => null,
            'reviews_count' => 0,
            'place_url' => null,
            'landscape_photo_url' => null,
            'landscape_photo_attribution' => null,
            'reviews' => [],
        ];
    }

    private function resolvePlaceIdFromQuery(string $apiKey, string $placeQuery): string
    {
        $cacheKey = 'google-place-query:'.md5($placeQuery);
        $cacheTtl = now()->addMinutes((int) config('services.google_places.cache_minutes', 360));

        return Cache::remember($cacheKey, $cacheTtl, function () use ($apiKey, $placeQuery) {
            try {
                $response = Http::timeout(8)
                    ->acceptJson()
                    ->withHeaders([
                        'X-Goog-Api-Key' => $apiKey,
                        'X-Goog-FieldMask' => 'places.id',
                    ])
                    ->post('https://places.googleapis.com/v1/places:searchText', [
                        'textQuery' => $placeQuery,
                        'languageCode' => app()->getLocale(),
                        'maxResultCount' => 1,
                    ])
                    ->throw()
                    ->json();
            } catch (\Throwable $exception) {
                Log::warning('Unable to resolve Google place ID from query.', [
                    'place_query' => $placeQuery,
                    'message' => $exception->getMessage(),
                ]);

                return '';
            }

            return (string) data_get($response, 'places.0.id', '');
        });
    }

    private function resolveLandscapePhoto(string $apiKey, array $photos): array
    {
        $selectedPhoto = collect($photos)
            ->first(fn (array $photo) => (int) ($photo['widthPx'] ?? 0) > (int) ($photo['heightPx'] ?? 0))
            ?? collect($photos)->first();

        if (! is_array($selectedPhoto)) {
            return [
                'photo_url' => null,
                'attribution' => null,
            ];
        }

        $photoName = (string) ($selectedPhoto['name'] ?? '');

        if ($photoName === '') {
            return [
                'photo_url' => null,
                'attribution' => null,
            ];
        }

        try {
            $photoResponse = Http::timeout(8)
                ->acceptJson()
                ->get('https://places.googleapis.com/v1/'.$photoName.'/media', [
                    'key' => $apiKey,
                    'maxWidthPx' => 1600,
                    'skipHttpRedirect' => 'true',
                ])
                ->throw()
                ->json();
        } catch (\Throwable $exception) {
            Log::warning('Unable to resolve Google place photo URI.', [
                'photo_name' => $photoName,
                'message' => $exception->getMessage(),
            ]);

            return [
                'photo_url' => null,
                'attribution' => null,
            ];
        }

        $attribution = collect($selectedPhoto['authorAttributions'] ?? [])
            ->map(function (array $author) {
                $name = trim((string) ($author['displayName'] ?? ''));
                $uri = trim((string) ($author['uri'] ?? ''));

                if ($name === '') {
                    return null;
                }

                return [
                    'name' => $name,
                    'uri' => $uri !== '' ? $uri : null,
                ];
            })
            ->filter()
            ->values()
            ->all();

        return [
            'photo_url' => $photoResponse['photoUri'] ?? null,
            'attribution' => $attribution,
        ];
    }
}
