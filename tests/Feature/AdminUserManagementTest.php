<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_another_admin_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Second Admin',
            'email' => 'secondadmin@gmail.com',
            'phone' => '+6088123456',
            'password' => 'AdminPass123!',
            'password_confirmation' => 'AdminPass123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Second Admin',
            'email' => 'secondadmin@gmail.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_remove_another_admin_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $otherAdmin = User::factory()->create([
            'role' => 'admin',
            'email' => 'otheradmin@gmail.com',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $otherAdmin));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $otherAdmin->id,
        ]);
    }

    public function test_admin_cannot_remove_own_admin_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertStatus(422);
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
