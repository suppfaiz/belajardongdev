<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    private function createTeacher(): User
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        TeacherProfile::create([
            'user_id' => $teacher->id,
            'headline' => 'Guru Matematika SMP & SMA',
            'bio' => 'Berpengalaman mengajar matematika.',
            'education' => 'S1 Pendidikan Matematika UNJ',
            'hourly_rate' => 100000,
            'session_duration_minutes' => 90,
            'is_verified' => true,
            'verification_status' => 'verified',
            'service_city' => 'Jakarta Selatan',
            'latitude' => -6.2442,
            'longitude' => 106.8006,
            'max_travel_distance_km' => 10,
        ]);

        return $teacher;
    }

    public function test_parent_can_view_messages_page(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $this->createTeacher();

        $response = $this->actingAs($parent)->get(route('parent.messages'));

        $response->assertStatus(200);
        $response->assertSee('Pesan & Diskusi Belajar', false);
    }

    public function test_teacher_can_view_messages_page(): void
    {
        $teacher = $this->createTeacher();
        User::factory()->create(['role' => 'parent']);

        $response = $this->actingAs($teacher)->get(route('teacher.messages'));

        $response->assertStatus(200);
        $response->assertSee('Pesan & Diskusi Orang Tua', false);
    }

    public function test_user_can_send_chat_message(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $teacher = $this->createTeacher();

        $response = $this->actingAs($parent)->postJson(route('chat.send'), [
            'receiver_id' => $teacher->id,
            'message' => 'Halo Ibu guru, apakah bisa les jam 4 sore?',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'sender_id' => $parent->id,
            'receiver_id' => $teacher->id,
            'message' => 'Halo Ibu guru, apakah bisa les jam 4 sore?',
            'is_read' => false,
        ]);
    }

    public function test_user_can_fetch_messages_stream_via_api(): void
    {
        $parent = User::factory()->create(['role' => 'parent']);
        $teacher = $this->createTeacher();

        ChatMessage::create([
            'sender_id' => $teacher->id,
            'receiver_id' => $parent->id,
            'message' => 'Bisa pak, jadwal jam 4 sore tersedia.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($parent)->getJson(route('chat.api', $teacher->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonFragment([
            'text' => 'Bisa pak, jadwal jam 4 sore tersedia.',
        ]);

        // Message should be marked as read
        $this->assertDatabaseHas('chat_messages', [
            'sender_id' => $teacher->id,
            'receiver_id' => $parent->id,
            'is_read' => true,
        ]);
    }
}
