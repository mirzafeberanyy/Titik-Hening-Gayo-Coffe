<?php

namespace App\Livewire;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatWidget extends Component
{
    public $isOpen = false;
    public $message = '';

    public function toggleChat()
    {
        // Fitur chat ini hanya untuk user yang sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $this->isOpen = !$this->isOpen;

        // Jika dibuka, tandai pesan dari admin sebagai 'sudah dibaca'
        if ($this->isOpen) {
            Chat::where('user_id', Auth::id())
                ->where('is_admin_reply', true)
                ->update(['is_read' => true]);
        }
    }

    public function sendMessage()
    {
        $this->validate(['message' => 'required|min:1']);

        Chat::create([
            'user_id' => Auth::id(),
            'message' => $this->message,
            'is_admin_reply' => false, // Ini pesan dari pelanggan
        ]);

        $this->message = '';
        
        $this->dispatch('swal', [
            'title' => 'Pesan Terkirim',
            'text' => 'Admin akan segera membalas pesan Anda.',
            'icon' => 'success',
            'timer' => 3000
        ]);

        // Emit event atau dispatch untuk scroll otomatis (opsional)
        $this->dispatch('scroll-bottom');
    }

    public function render()
    {
        $chats = Auth::check() 
            ? Chat::where('user_id', Auth::id())->oldest()->get() 
            : [];

        return view('livewire.chat-widget', [
            'chats' => $chats
        ]);
    }
}