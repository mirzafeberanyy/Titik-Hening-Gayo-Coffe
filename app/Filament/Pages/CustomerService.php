<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Chat;
use App\Models\User;
use Filament\Notifications\Notification;

class CustomerService extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Live Chat CS';
    protected static ?string $title = 'Customer Service Chat';
    protected static ?string $navigationGroup = 'Support';

    protected static string $view = 'filament.pages.customer-service';

    /**
     * BATAS AKSES PAGE
     * Hanya admin & staff_konten
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->role === 'admin' || $user->isKonten();
    }

    /**
     * Supaya MENU SIDEBAR ikut hilang
     * ⚠️ WAJIB public static (Filament v3)
     */
    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    // =========================
    // STATE
    // =========================
    public $selectedUserId = null;
    public $message = '';

    /**
     * Daftar user yang pernah chat
     */
    public function getChatUsersProperty()
    {
        return User::whereHas('chats')
            ->with(['chats' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(fn ($user) =>
                optional($user->chats->first())->created_at
            );
    }

    /**
     * Chat user terpilih
     */
    public function getSelectedChatsProperty()
    {
        if (! $this->selectedUserId) {
            return collect();
        }

        return Chat::where('user_id', $this->selectedUserId)
            ->oldest()
            ->get();
    }

    /**
     * Pilih user
     */
    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;

        Chat::where('user_id', $userId)
            ->where('is_admin_reply', false)
            ->update(['is_read' => true]);
    }

    /**
     * Kirim balasan admin
     */
    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|min:1',
        ]);

        if (! $this->selectedUserId) {
            return;
        }

        Chat::create([
            'user_id' => $this->selectedUserId,
            'message' => $this->message,
            'is_admin_reply' => true,
            'is_read' => false,
        ]);

        $this->message = '';

        Notification::make()
            ->title('Pesan terkirim')
            ->success()
            ->send();
    }
}
