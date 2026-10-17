<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Show messages view for Parent.
     */
    public function parentIndex(Request $request): View
    {
        $currentUser = Auth::user();

        // Teachers who have bookings with this parent or seeded teachers
        $bookedTeacherIds = Booking::where('parent_id', $currentUser->id)
            ->pluck('teacher_id')
            ->unique();

        $teachers = User::where('role', 'teacher')
            ->with(['teacherProfile.subjects'])
            ->where(function ($query) use ($bookedTeacherIds) {
                $query->whereIn('id', $bookedTeacherIds)
                    ->orWhereHas('teacherProfile', fn ($q) => $q->where('is_verified', true));
            })
            ->take(10)
            ->get();

        $selectedTeacherId = $request->query('teacher_id')
            ?: $request->query('with')
            ?: ($teachers->first()?->id ?? null);

        $activeContact = $teachers->firstWhere('id', (int) $selectedTeacherId) ?? $teachers->first();

        $messages = [];
        if ($activeContact) {
            $messages = $this->getConversationMessages($currentUser->id, $activeContact->id);
            $this->markAsRead($activeContact->id, $currentUser->id);
        }

        return view('parent.messages', compact('teachers', 'activeContact', 'messages'));
    }

    /**
     * Show messages view for Teacher.
     */
    public function teacherIndex(Request $request): View
    {
        $currentUser = Auth::user();

        // Parents who have booked with this teacher or sent messages
        $bookedParentIds = Booking::where('teacher_id', $currentUser->id)
            ->pluck('parent_id')
            ->unique();

        $messageSenderIds = ChatMessage::where('receiver_id', $currentUser->id)
            ->orWhere('sender_id', $currentUser->id)
            ->pluck('sender_id')
            ->merge(ChatMessage::where('receiver_id', $currentUser->id)->pluck('receiver_id'))
            ->unique();

        $allParentIds = $bookedParentIds->merge($messageSenderIds)->unique()->filter(fn ($id) => $id !== $currentUser->id);

        $parents = User::where('role', 'parent')
            ->where(function ($query) use ($allParentIds) {
                if ($allParentIds->isNotEmpty()) {
                    $query->whereIn('id', $allParentIds);
                }
            })
            ->take(10)
            ->get();

        // Fallback to any parents if no bookings yet so teacher portal is not empty
        if ($parents->isEmpty()) {
            $parents = User::where('role', 'parent')->take(5)->get();
        }

        $selectedParentId = $request->query('parent_id')
            ?: $request->query('with')
            ?: ($parents->first()?->id ?? null);

        $activeContact = $parents->firstWhere('id', (int) $selectedParentId) ?? $parents->first();

        $messages = [];
        if ($activeContact) {
            $messages = $this->getConversationMessages($currentUser->id, $activeContact->id);
            $this->markAsRead($activeContact->id, $currentUser->id);
        }

        return view('teacher.messages', compact('parents', 'activeContact', 'messages'));
    }

    /**
     * Send a chat message.
     */
    public function sendMessage(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string', 'max:2000'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
        ]);

        $currentUser = Auth::user();

        $chatMessage = ChatMessage::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $validated['receiver_id'],
            'booking_id' => $validated['booking_id'] ?? null,
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $chatMessage->id,
                    'sender_id' => $chatMessage->sender_id,
                    'is_me' => true,
                    'text' => $chatMessage->message,
                    'time' => $chatMessage->created_at->format('H:i'),
                ],
            ]);
        }

        return back()->with('success', 'Pesan berhasil terkirim!');
    }

    /**
     * Get real-time messages via API.
     */
    public function getApiMessages(User $user): JsonResponse
    {
        $currentUser = Auth::user();
        $messages = $this->getConversationMessages($currentUser->id, $user->id);
        $this->markAsRead($user->id, $currentUser->id);

        $formatted = $messages->map(function ($msg) use ($currentUser) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'is_me' => $msg->sender_id === $currentUser->id,
                'sender' => $msg->sender_id === $currentUser->id ? 'me' : 'other',
                'text' => $msg->message,
                'time' => $msg->created_at->format('H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'contact' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
            'messages' => $formatted,
        ]);
    }

    /**
     * Retrieve messages between two users.
     */
    private function getConversationMessages(int $userId1, int $userId2)
    {
        return ChatMessage::where(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId2)->where('receiver_id', $userId1);
        })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Mark unread messages as read.
     */
    private function markAsRead(int $senderId, int $receiverId): void
    {
        ChatMessage::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}
