<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_as_parent(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Budi Pratama',
            'email' => 'budi.parent@example.com',
            'phone' => '081234567890',
            'role' => 'parent',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('parent.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'budi.parent@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('parent', $user->role);
        $this->assertNotNull($user->uuid);
    }

    public function test_guest_can_register_as_teacher_and_teacher_profile_is_created(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Dewi Lestari, S.Pd',
            'email' => 'dewi.guru@example.com',
            'phone' => '081234567891',
            'role' => 'teacher',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'dewi.guru@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('teacher', $user->role);
        $this->assertNotNull($user->teacherProfile);
        $this->assertFalse($user->teacherProfile->is_verified);
    }

    public function test_parent_cannot_register_as_admin(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Hacker Admin',
            'email' => 'hacker@example.com',
            'phone' => '081234567892',
            'role' => 'admin',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'hacker@example.com']);
    }

    public function test_user_can_login_and_is_redirected_to_their_respective_dashboard(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $teacher->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertAuthenticatedAs($teacher);
    }

    public function test_role_middleware_blocks_parent_from_accessing_admin_dashboard(): void
    {
        $parent = User::factory()->create([
            'role' => 'parent',
        ]);

        $response = $this->actingAs($parent)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard_and_renders_successfully(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('Pusat Kendali Administrator');
        $response->assertSee('Guru Terverifikasi');
    }

    public function test_role_middleware_blocks_teacher_from_accessing_parent_dashboard(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $response = $this->actingAs($teacher)->get(route('parent.dashboard'));
        $response->assertForbidden();
    }

    public function test_inactive_user_is_logged_out_and_blocked(): void
    {
        $inactiveUser = User::factory()->create([
            'role' => 'parent',
            'is_active' => false,
        ]);

        $response = $this->actingAs($inactiveUser)->get(route('parent.dashboard'));
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_login_and_registration_endpoints_are_resilient_to_token_mismatch_for_lan_and_external_clients(): void
    {
        $user = User::factory()->create([
            'role' => 'parent',
            'password' => Hash::make('password123'),
        ]);

        // POST without CSRF token simulates cross-device or multi-subnet client
        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->post('/masuk', [
                'email' => $user->email,
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('parent.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
