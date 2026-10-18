<x-layouts.dashboard title="Pesan & Diskusi Orang Tua — Portal Guru BelajarYuk">
    <div class="max-w-7xl mx-auto space-y-6" x-data="{
        activeContactId: {{ $activeContact?->id ?? 'null' }},
        activeContactName: '{{ addslashes($activeContact?->name ?? 'Pilih Orang Tua') }}',
        activeContactHeadline: '{{ addslashes($activeContact?->phone ?? 'Orang Tua Murid') }}',
        activeContactAvatar: '{{ $activeContact?->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80' }}',
        messageText: '',
        isSending: false,
        searchQuery: '',
        messages: [
            @if(isset($messages) && count($messages) > 0)
                @foreach($messages as $msg)
                {
                    id: {{ $msg->id }},
                    is_me: {{ $msg->sender_id === auth()->id() ? 'true' : 'false' }},
                    text: '{{ addslashes($msg->message) }}',
                    time: '{{ $msg->created_at->format('H:i') }}'
                },
                @endforeach
            @else
                {
                    id: 0,
                    is_me: false,
                    text: 'Halo Bu/Pak Guru! Terima kasih sudah bersedia membimbing anak kami.',
                    time: '{{ now()->format('H:i') }}'
                }
            @endif
        ],
        init() {
            this.scrollToBottom();
            setInterval(() => {
                if (this.activeContactId) {
                    this.fetchNewMessages();
                }
            }, 3500);
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatStream;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },
        async selectContact(id, name, headline, avatar) {
            this.activeContactId = id;
            this.activeContactName = name;
            this.activeContactHeadline = headline;
            this.activeContactAvatar = avatar;
            await this.fetchNewMessages();
        },
        async fetchNewMessages() {
            if (!this.activeContactId) return;
            try {
                const res = await fetch(`/chat/api/${this.activeContactId}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data.success && data.messages.length > 0) {
                        this.messages = data.messages;
                        this.scrollToBottom();
                    }
                }
            } catch (e) {
                // Ignore network glitches
            }
        },
        async sendMessage() {
            const text = this.messageText.trim();
            if (!text || !this.activeContactId || this.isSending) return;

            this.isSending = true;
            const optimisticId = Date.now();
            const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            this.messages.push({
                id: optimisticId,
                is_me: true,
                text: text,
                time: nowTime
            });
            this.messageText = '';
            this.scrollToBottom();

            try {
                const res = await fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        receiver_id: this.activeContactId,
                        message: text
                    })
                });

                if (res.ok) {
                    const data = await res.json();
                    if (data.success && data.message) {
                        const last = this.messages.find(m => m.id === optimisticId);
                        if (last) last.id = data.message.id;
                    }
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isSending = false;
            }
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                    Pesan & Diskusi Orang Tua
                </h1>
                <p class="text-xs sm:text-sm text-[#747B8C] mt-1">
                    Koordinasi materi les, evaluasi pemahaman murid, dan konfirmasi jadwal sesi
                </p>
            </div>
            <div>
                <a href="{{ route('teacher.schedules') }}" class="btn-navy py-2 px-4 text-xs font-semibold shadow-xs inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span>Atur Jadwal Mengajar</span>
                </a>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bd-card overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[620px] max-h-[750px] border border-[#E5E8F0]">
            <!-- Left: Contact List (4 cols) -->
            <div class="lg:col-span-4 border-r border-[#E5E8F0] bg-white flex flex-col">
                <div class="p-4 border-b border-[#E5E8F0] bg-slate-50/50">
                    <div class="relative">
                        <input type="text" 
                               x-model="searchQuery"
                               placeholder="Cari nama orang tua..." 
                               class="w-full bg-white border border-[#E5E8F0] rounded-full py-2 pl-9 pr-4 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                    @forelse($parents as $parent)
                        @php
                            $avatarUrl = $parent->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80';
                        @endphp
                        <button type="button" 
                                @click="selectContact({{ $parent->id }}, '{{ addslashes($parent->name) }}', 'Orang Tua Murid', '{{ $avatarUrl }}')"
                                :class="activeContactId === {{ $parent->id }} ? 'bg-blue-50/80 border-r-4 border-[#080F63]' : 'hover:bg-slate-50'"
                                class="w-full p-4 flex items-start gap-3 text-left transition relative">
                            <div class="relative shrink-0">
                                <img class="w-11 h-11 rounded-full object-cover ring-2 ring-slate-100" 
                                     src="{{ $avatarUrl }}" 
                                     alt="{{ $parent->name }}">
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-xs text-[#171923] truncate">{{ $parent->name }}</h4>
                                    <span class="text-[10px] text-slate-400">Aktif</span>
                                </div>
                                <p class="text-[11px] text-[#080F63] font-semibold truncate">Orang Tua Murid</p>
                                <p class="text-xs text-[#747B8C] truncate mt-0.5">Klik untuk merespon pertanyaan orang tua...</p>
                            </div>
                        </button>
                    @empty
                        <div class="p-8 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p class="text-xs">Belum ada daftar pesan masuk</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Active Chat Window (8 cols) -->
            <div class="lg:col-span-8 flex flex-col bg-[#F8FAFC]">
                <!-- Chat Header -->
                <div class="p-4 bg-white border-b border-[#E5E8F0] flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100" 
                                 :src="activeContactAvatar" 
                                 :alt="activeContactName">
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-[#171923]" x-text="activeContactName"></h3>
                            <span class="text-[11px] text-emerald-600 font-semibold" x-text="'Online • ' + activeContactHeadline"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('teacher.bookings') }}" class="btn-navy py-1.5 px-3.5 text-xs font-semibold shadow-xs hidden sm:inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>Pesanan Les</span>
                        </a>
                    </div>
                </div>

                <!-- Messages Stream -->
                <div class="flex-1 p-6 overflow-y-auto space-y-3.5" x-ref="chatStream">
                    <div class="text-center my-2">
                        <span class="bg-slate-200/60 text-slate-500 text-[10px] font-semibold px-3 py-1 rounded-full">
                            Pesan terenkripsi & dipantau untuk keamanan anak
                        </span>
                    </div>

                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.is_me ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.is_me ? 'bg-[#080F63] text-white rounded-2xl rounded-tr-xs shadow-xs' : 'bg-white text-[#171923] border border-[#E5E8F0] rounded-2xl rounded-tl-xs shadow-xs'"
                                 class="max-w-md p-3.5">
                                <p class="text-xs leading-relaxed whitespace-pre-wrap break-words" x-text="msg.text"></p>
                                <span :class="msg.is_me ? 'text-blue-200' : 'text-slate-400'"
                                      class="text-[10px] block text-right mt-1 font-medium" 
                                      x-text="msg.time"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input Message Bar -->
                <div class="p-4 bg-white border-t border-[#E5E8F0] flex items-center gap-2">
                    <input type="text" 
                           x-model="messageText" 
                           @keydown.enter.prevent="sendMessage()"
                           placeholder="Ketik balasan Anda ke orang tua..." 
                           class="flex-1 bg-[#F3F5FA] border border-[#E5E8F0] rounded-full py-2.5 px-4 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">

                    <button type="button" 
                            @click="sendMessage()"
                            :disabled="isSending || !messageText.trim()"
                            class="btn-navy py-2.5 px-5 text-xs font-bold shadow-xs inline-flex items-center gap-1.5 disabled:opacity-50 cursor-pointer">
                        <span>Kirim</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
