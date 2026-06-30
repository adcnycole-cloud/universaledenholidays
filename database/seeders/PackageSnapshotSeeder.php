<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use JsonException;

class PackageSnapshotSeeder extends Seeder
{
    /**
     * Seed the current package snapshot from versioned JSON.
     *
     * @throws JsonException
     */
    public function run(): void
    {
        $snapshotPath = database_path('seeders/data/packages_snapshot.json');
        $snapshot = (string) file_get_contents($snapshotPath);
        $snapshot = preg_replace('/^\xEF\xBB\xBF/', '', $snapshot) ?? $snapshot;
        $packages = json_decode($snapshot, true, 512, JSON_THROW_ON_ERROR);

        foreach ($packages as $attributes) {
            Package::query()->updateOrCreate(
                ['tour_code' => $attributes['tour_code']],
                $attributes,
            );
        }
    }
}
