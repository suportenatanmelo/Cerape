<div class="glass-card p-6 sm:p-8">
    @if (session('contact_success'))
        <div class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-medium text-emerald-100">
            {{ session('contact_success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-200">Nome</span>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Seu nome completo"
                    required
                    @class([
                        'w-full rounded-2xl border bg-slate-950/70 px-4 py-3 text-slate-100 outline-none transition placeholder:text-slate-500 focus:border-amber-300 focus:ring-2 focus:ring-amber-300/20',
                        'border-rose-400/40' => $errors->has('name'),
                        'border-white/10' => ! $errors->has('name'),
                    ])
                />
                @error('name')
                    <p class="text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </label>

            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-200">E-mail</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="seuemail@exemplo.com"
                    required
                    @class([
                        'w-full rounded-2xl border bg-slate-950/70 px-4 py-3 text-slate-100 outline-none transition placeholder:text-slate-500 focus:border-amber-300 focus:ring-2 focus:ring-amber-300/20',
                        'border-rose-400/40' => $errors->has('email'),
                        'border-white/10' => ! $errors->has('email'),
                    ])
                />
                @error('email')
                    <p class="text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </label>

            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-200">Telefone</span>
                <input
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="(00) 00000-0000"
                    @class([
                        'w-full rounded-2xl border bg-slate-950/70 px-4 py-3 text-slate-100 outline-none transition placeholder:text-slate-500 focus:border-amber-300 focus:ring-2 focus:ring-amber-300/20',
                        'border-rose-400/40' => $errors->has('phone'),
                        'border-white/10' => ! $errors->has('phone'),
                    ])
                />
                @error('phone')
                    <p class="text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </label>

            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-200">Assunto</span>
                <input
                    type="text"
                    name="subject"
                    value="{{ old('subject') }}"
                    placeholder="Como podemos ajudar?"
                    required
                    @class([
                        'w-full rounded-2xl border bg-slate-950/70 px-4 py-3 text-slate-100 outline-none transition placeholder:text-slate-500 focus:border-amber-300 focus:ring-2 focus:ring-amber-300/20',
                        'border-rose-400/40' => $errors->has('subject'),
                        'border-white/10' => ! $errors->has('subject'),
                    ])
                />
                @error('subject')
                    <p class="text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </label>
        </div>

        <label class="block space-y-2">
            <span class="text-sm font-semibold text-slate-200">Mensagem</span>
            <textarea
                name="message"
                rows="6"
                placeholder="Escreva sua mensagem"
                required
                @class([
                    'w-full rounded-2xl border bg-slate-950/70 px-4 py-3 text-slate-100 outline-none transition placeholder:text-slate-500 focus:border-amber-300 focus:ring-2 focus:ring-amber-300/20',
                    'border-rose-400/40' => $errors->has('message'),
                    'border-white/10' => ! $errors->has('message'),
                ])
            >{{ old('message') }}</textarea>
            @error('message')
                <p class="text-sm text-rose-300">{{ $message }}</p>
            @enderror
        </label>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm leading-6 text-slate-400">
                Ao enviar a mensagem, sua solicitação chega ao time responsável para retorno.
            </p>
            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-300">
                Enviar mensagem
            </button>
        </div>
    </form>
</div>
