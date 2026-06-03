<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewsService
{
    public function getPublicReviewData(): array
    {
        $apiKey = (string) config('services.google_places.api_key');
        $placeId = (string) config('services.google_places.place_id');

        if ($apiKey === '' || $placeId === '') {
            return $this->defaultPayload();
        }

        $cacheMinutes = (int) config('services.google_places.cache_minutes', 60);
        $cacheKey = sprintf('google_place_reviews:%s', md5($placeId));

        return Cache::remember($cacheKey, now()->addMinutes(max(1, $cacheMinutes)), function () use ($apiKey, $placeId) {
            try {
                $response = Http::timeout(10)
                    ->retry(2, 300)
                    ->get('https://maps.googleapis.com/maps/api/place/details/json', [
                        'place_id' => $placeId,
                        'fields' => 'name,rating,user_ratings_total,reviews,url',
                        'reviews_sort' => 'newest',
                        'key' => $apiKey,
                    ]);

                if (! $response->successful()) {
                    Log::warning('Google Places API request failed', [
                        'status' => $response->status(),
                        'place_id' => $placeId,
                    ]);

                    return $this->defaultPayload();
                }

                $payload = $response->json();
                $status = (string) ($payload['status'] ?? '');

                if ($status !== 'OK') {
                    Log::warning('Google Places API returned non-OK status', [
                        'api_status' => $status,
                        'place_id' => $placeId,
                    ]);

                    return $this->defaultPayload();
                }

                $result = $payload['result'] ?? [];
                $reviews = collect($result['reviews'] ?? [])
                    ->map(function ($review) {
                        return [
                            'author_name' => (string) ($review['author_name'] ?? 'Google User'),
                            'profile_photo_url' => (string) ($review['profile_photo_url'] ?? ''),
                            'rating' => (int) ($review['rating'] ?? 0),
                            'relative_time_description' => (string) ($review['relative_time_description'] ?? ''),
                            'text' => (string) ($review['text'] ?? ''),
                            'author_url' => (string) ($review['author_url'] ?? ''),
                        ];
                    })
                    ->filter(fn ($review) => $review['rating'] > 0)
                    ->take(5)
                    ->values()
                    ->all();

                return [
                    'place_name' => (string) ($result['name'] ?? ''),
                    'rating' => isset($result['rating']) ? (float) $result['rating'] : null,
                    'review_count' => isset($result['user_ratings_total']) ? (int) $result['user_ratings_total'] : null,
                    'reviews' => $reviews,
                    'maps_url' => (string) ($result['url'] ?? ''),
                    'write_review_url' => sprintf('https://search.google.com/local/writereview?placeid=%s', urlencode($placeId)),
                ];
            } catch (\Throwable $exception) {
                Log::warning('Failed to fetch Google place reviews', [
                    'message' => $exception->getMessage(),
                    'place_id' => $placeId,
                ]);

                return $this->defaultPayload();
            }
        });
    }

    private function defaultPayload(): array
    {
        return [
            'place_name' => '',
            'rating' => null,
            'review_count' => null,
            'reviews' => [],
            'maps_url' => '',
            'write_review_url' => '',
        ];
    }
}
