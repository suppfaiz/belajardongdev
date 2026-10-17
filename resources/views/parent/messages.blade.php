<x-layouts.dashboard title="Pesan — Belajar Dong">
    <div class="max-w-7xl mx-auto space-y-6" x-data="{
        activeChat: 'siti',
        messageText: '',
        messages: [
            { sender: 'teacher', text: 'Halo Pak Andi, untuk materi aljabar pertemuan besok sudah saya siapkan modul tambahannya ya.', time: '09:15' },
            { sender: 'parent', text: 'Terima kasih Bu Siti. Mohon Rian dibimbing lebih intensif di bagian pemfaktoran ya bu.', time: '09:20' },
            { sender: 'teacher', text: 'Baik pak, besok kita fokuskan latihan di persamaan kuadrat dan pemfaktoran.', time: '09:22' }
        ],
        sendMessage() {
            if (!this.messageText.trim()) return;
            this.messages.push({
                sender: 'parent',
                text: this.messageText,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
            this.messageText = '';
        }
    }">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                Pesan & Diskusi
            </h1>
            <p class="text-sm text-[#747B8C] mt-1">
                Komunikasi langsung dengan guru les seputar materi belajar anak
            </p>
        </div>

        <!-- Two Column Chat Layout: Left (Conversations List) + Right (Chat Window) -->
        <div class="bd-card overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[600px] border border-[#E5E8F0]">
            <!-- Left: Conversations List (4 cols) -->
            <div class="lg:col-span-4 border-r border-[#E5E8F0] bg-white flex flex-col">
                <div class="p-4 border-b border-[#E5E8F0]">
                    <input type="text" placeholder="Cari percakapan guru..." class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-full py-2 px-4 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                </div>

                <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                    <!-- Conversation 1: Siti Nurhaliza -->
                    <button type="button" 
                            @click="activeChat = 'siti'"
                            :class="activeChat === 'siti' ? 'bg-blue-50/70' : 'hover:bg-slate-50'"
                            class="w-full p-4 flex items-start gap-3 text-left transition">
                        <div class="relative shrink-0">
                            <img class="w-11 h-11 rounded-full object-cover" 
                                 src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80" 
                                 alt="Siti Nurhaliza">
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h4 class="font-bold text-xs text-[#171923] truncate">Siti Nurhaliza</h4>
                                <span class="text-[10px] text-slate-400">09:22</span>
                            </div>
                            <p class="text-[11px] text-[#080F63] font-semibold truncate">Matematika</p>
                            <p class="text-xs text-[#747B8C] truncate mt-0.5">Baik pak, besok kita fokuskan latihan...</p>
                        </div>
                    </button>

                    <!-- Conversation 2: Ahmad Rizky -->
                    <button type="button" 
                            @click="activeChat = 'ahmad'"
                            :class="activeChat === 'ahmad' ? 'bg-blue-50/70' : 'hover:bg-slate-50'"
                            class="w-full p-4 flex items-start gap-3 text-left transition">
                        <div class="relative shrink-0">
                            <img class="w-11 h-11 rounded-full object-cover" 
                                 src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80" 
                                 alt="Ahmad Rizky">
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h4 class="font-bold text-xs text-[#171923] truncate">Ahmad Rizky</h4>
                                <span class="text-[10px] text-slate-400">Kemarin</span>
                            </div>
                            <p class="text-[11px] text-emerald-700 font-semibold truncate">Bahasa Inggris</p>
                            <p class="text-xs text-[#747B8C] truncate mt-0.5">Materi vocabulary sudah dikirimkan via...</p>
                        </div>
                    </button>

                    <!-- Conversation 3: Rina Amelia -->
                    <button type="button" 
                            @click="activeChat = 'rina'"
                            :class="activeChat === 'rina' ? 'bg-blue-50/70' : 'hover:bg-slate-50'"
                            class="w-full p-4 flex items-start gap-3 text-left transition">
                        <div class="relative shrink-0">
                            <div class="w-11 h-11 rounded-full bg-sky-100 text-sky-800 font-bold text-sm flex items-center justify-center">
                                RA
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h4 class="font-bold text-xs text-[#171923] truncate">Rina Amelia</h4>
                                <span class="text-[10px] text-slate-400">14 Okt</span>
                            </div>
                            <p class="text-[11px] text-sky-800 font-semibold truncate">IPA Terpadu</p>
                            <p class="text-xs text-[#747B8C] truncate mt-0.5">Hasil kuis sistem pernapasan sangat baik.</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Right: Chat Window (8 cols) -->
            <div class="lg:col-span-8 flex flex-col bg-[#F8FAFC]">
                <!-- Chat Header -->
                <div class="p-4 bg-white border-b border-[#E5E8F0] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img class="w-10 h-10 rounded-full object-cover" 
                                 src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80" 
                                 alt="Siti Nurhaliza">
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-[#171923]">Siti Nurhaliza</h3>
                            <span class="text-[11px] text-emerald-600 font-semibold">Online &bull; Guru Matematika</span>
                        </div>
                    </div>

                    <!-- Shortcut Action: "Lihat Jadwal Les" -->
                    <a href="{{ route('parent.schedule') }}" class="btn-navy py-1.5 px-3.5 text-xs font-semibold shadow-xs">
                        Lihat Jadwal Les &rarr;
                    </a>
                </div>

                <!-- Messages Stream -->
                <div class="flex-1 p-6 overflow-y-auto space-y-4">
                    <template x-for="(msg, idx) in messages" :key="idx">
                        <div :class="msg.sender === 'parent' ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.sender === 'parent' ? 'bg-[#080F63] text-white rounded-2xl rounded-tr-xs' : 'bg-white text-[#171923] border border-[#E5E8F0] rounded-2xl rounded-tl-xs'"
                                 class="max-w-md p-3.5 shadow-xs">
                                <p class="text-xs leading-relaxed" x-text="msg.text"></p>
                                <span :class="msg.sender === 'parent' ? 'text-blue-200' : 'text-slate-400'"
                                      class="text-[10px] block text-right mt-1" 
                                      x-text="msg.time"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input Message Bar + Attachment Button -->
                <div class="p-4 bg-white border-t border-[#E5E8F0] flex items-center gap-2">
                    <button type="button" class="p-2.5 rounded-full text-slate-400 hover:text-[#080F63] hover:bg-slate-100 transition" title="Lampirkan foto PR atau file materi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>

                    <input type="text" 
                           x-model="messageText" 
                           @keydown.enter.prevent="sendMessage()"
                           placeholder="Ketik pesan Anda ke guru..." 
                           class="flex-1 bg-[#F3F5FA] border border-[#E5E8F0] rounded-full py-2.5 px-4 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">

                    <button type="button" 
                            @click="sendMessage()"
                            class="btn-navy py-2.5 px-5 text-xs font-bold shadow-xs">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
