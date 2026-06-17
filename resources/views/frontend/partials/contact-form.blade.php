<div class="glass-card p-6 sm:p-8">
    @if (session('contact_success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('contact_success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-700">Nome</span>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Seu nome completo"
                    required
                    @class([
                        'w-full rounded-2xl border bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--site-primary)] focus:ring-2 focus:ring-[color-mix(in_srgb,var(--site-primary)_20%,transparent)]',
                        'border-rose-400/40' => $errors->has('name'),
                        'border-slate-200' => ! $errors->has('name'),
                    ])
                />
                @error('name')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </label>

            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-700">E-mail</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="seuemail@exemplo.com"
                    required
                    @class([
                        'w-full rounded-2xl border bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--site-primary)] focus:ring-2 focus:ring-[color-mix(in_srgb,var(--site-primary)_20%,transparent)]',
                        'border-rose-400/40' => $errors->has('email'),
                        'border-slate-200' => ! $errors->has('email'),
                    ])
                />
                @error('email')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </label>

            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-700">Telefone</span>
                <input
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="(00) 00000-0000"
                    @class([
                        'w-full rounded-2xl border bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--site-primary)] focus:ring-2 focus:ring-[color-mix(in_srgb,var(--site-primary)_20%,transparent)]',
                        'border-rose-400/40' => $errors->has('phone'),
                        'border-slate-200' => ! $errors->has('phone'),
                    ])
                />
                @error('phone')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </label>

            <label class="space-y-2">
                <span class="text-sm font-semibold text-slate-700">Assunto</span>
                <input
                    type="text"
                    name="subject"
                    value="{{ old('subject') }}"
                    placeholder="Como podemos ajudar?"
                    required
                    @class([
                        'w-full rounded-2xl border bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--site-primary)] focus:ring-2 focus:ring-[color-mix(in_srgb,var(--site-primary)_20%,transparent)]',
                        'border-rose-400/40' => $errors->has('subject'),
                        'border-slate-200' => ! $errors->has('subject'),
                    ])
                />
                @error('subject')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </label>
        </div>

        <label class="block space-y-2">
            <span class="text-sm font-semibold text-slate-700">Mensagem</span>
            <textarea
                name="message"
                rows="6"
                placeholder="Escreva sua mensagem"
                required
                @class([
                    'w-full rounded-2xl border bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--site-primary)] focus:ring-2 focus:ring-[color-mix(in_srgb,var(--site-primary)_20%,transparent)]',
                    'border-rose-400/40' => $errors->has('message'),
                    'border-slate-200' => ! $errors->has('message'),
                ])
            >{{ old('message') }}</textarea>
            @error('message')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </label>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm leading-6 text-slate-600">
                Ao enviar a mensagem, sua solicitação chega ao time responsável para retorno.
            </p>
            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-300">
                Enviar mensagem
            </button>
        </div>
    </form>
</div>
