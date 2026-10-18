<x-admin-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-heading text-3xl font-bold text-primary italic">Horaires des messes</h1>
            <p class="text-sm text-muted-foreground mt-1.5">Gérez les horaires des messes hebdomadaires et des célébrations spéciales.</p>
        </div>

        <a href="{{ route('admin.schedules.create') }}"
            class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/15 hover:shadow-xl hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14" />
                <path d="M12 5v14" />
            </svg>
            Ajouter un horaire
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-black/[.04]">
                        @foreach(['Intitulé', 'Type', 'Jour(s)', 'Heure(s)', 'Statut'] as $header)
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">{{ $header }}</th>
                        @endforeach
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[.03]">
                    @forelse($schedules as $s)
                        @php
                            $typeLabels = [
                                'sunday' => 'Dimanche',
                                'weekday' => 'En semaine',
                                'saturday' => 'Samedi',
                                'anticipated' => 'Messe anticipée',
                                'special' => 'Célébration spéciale',
                            ];
                            $dayLabels = [
                                'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
                                'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi',
                                'Sunday' => 'Dimanche',
                                'monday' => 'Lundi', 'tuesday' => 'Mardi', 'wednesday' => 'Mercredi',
                                'thursday' => 'Jeudi', 'friday' => 'Vendredi', 'saturday' => 'Samedi',
                                'sunday' => 'Dimanche',
                            ];
                            $days = is_array($s->day_of_week) ? $s->day_of_week : ($s->day_of_week ? [$s->day_of_week] : []);
                            $times = is_array($s->time) ? $s->time : ($s->time ? [$s->time] : []);
                            $typeKey = strtolower(trim((string) $s->mass_type));
                            $formattedDays = array_map(function ($day) use ($dayLabels) {
                                $key = strtolower(trim((string) $day));
                                return $dayLabels[ucfirst($key)] ?? $dayLabels[$key] ?? ucfirst($key);
                            }, $days);
                            $formattedTimes = array_values(array_filter(array_map(function ($time) {
                                if ($time === null || trim((string) $time) === '') return '';
                                try { return \Carbon\Carbon::parse($time)->format('H:i'); }
                                catch (\Throwable $e) { return trim((string) $time); }
                            }, $times)));
                        @endphp
                        <tr class="hover:bg-[#F5F7FA]/60 transition-colors group">
                            <td class="px-6 py-4 font-bold text-primary text-[13px]">{{ $s->title ?: 'Horaire sans intitulé' }}</td>
                            <td class="px-6 py-4 text-xs capitalize font-medium text-muted-foreground/70">{{ $typeLabels[$typeKey] ?? ucfirst($typeKey) }}</td>
                            <td class="px-6 py-4 text-xs">{{ $formattedDays ? implode(', ', $formattedDays) : '—' }}</td>
                            <td class="px-6 py-4 text-xs font-medium">{{ $formattedTimes ? implode(', ', $formattedTimes) : '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-[.1em] border {{ $s->is_active ? 'bg-blue-50 text-blue-600 border-blue-200/80' : 'bg-gray-50 text-gray-500 border-gray-200/80' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60 mr-1.5"></span>
                                    {{ $s->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity" x-data>
                                    <a href="{{ route('admin.schedules.edit', $s->id) }}"
                                        class="p-2 rounded-xl hover:bg-[#F5F7FA] text-muted-foreground/40 hover:text-primary transition-all"
                                        title="Modifier" aria-label="Modifier cet horaire">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" /><path d="m15 5 4 4" /></svg>
                                    </a>
                                    <form :id="'delete-schedule-{{ $s->id }}'" action="{{ route('admin.schedules.destroy', $s->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            @click="$store.confirm.open({ title: 'Supprimer cet horaire', message: 'Voulez-vous vraiment supprimer définitivement cet horaire de messe ? Cette action est irréversible.', onConfirm: () => document.getElementById('delete-schedule-{{ $s->id }}').submit(), confirmText: 'Supprimer', cancelText: 'Annuler' })"
                                            class="p-2 rounded-xl hover:bg-red-50 text-muted-foreground/40 hover:text-red-500 transition-all"
                                            title="Supprimer" aria-label="Supprimer cet horaire">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F5F7FA] text-primary/40">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                </div>
                                <h2 class="font-heading text-lg font-bold text-primary">Aucun horaire pour le moment</h2>
                                <p class="mt-2 text-sm text-muted-foreground">Ajoutez un premier horaire de messe pour aider les paroissiens à organiser leur venue.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
