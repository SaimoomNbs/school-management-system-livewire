<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">All Notifications</h1>
            <p class="page-desc">View your historical application alerts and announcements.</p>
        </div>
        <select wire:model.live="filterType" class="tbl-filter-select">
            <option value="">All Types</option>
            <option value="fee_due">Fee Dues</option>
            <option value="result_published">Results</option>
        </select>
    </div>

    <div class="card">
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:40px;"></th>
                        <th>Type</th>
                        <th style="max-width:500px;">Content</th>
                        <th>Date Caught</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifications as $note)
                        <tr wire:key="note-{{ $note->id }}" style="background:{{ $note->is_read ? 'transparent' : 'var(--color-surface-2)' }};">
                            <td>
                                @if (!$note->is_read)
                                    <div style="width:8px;height:8px;border-radius:50%;background:var(--color-accent);margin:0 auto;"></div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $note->type === 'fee_due' ? 'badge-danger' : 'badge-accent' }}" style="font-size:10px;">{{ strtoupper(str_replace('_', ' ', $note->type)) }}</span>
                            </td>
                            <td>
                                <div style="font-weight:700;font-size:13px;color:var(--color-text-1);">{{ $note->title }}</div>
                                <div style="font-size:12.5px;color:var(--color-text-3);margin-top:2px;">{{ $note->message }}</div>
                            </td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($note->created_at)->format('d M Y, h:i A') }}</td>
                            <td style="text-align:right;">
                                @if (!$note->is_read)
                                    <button wire:click="markAsRead({{ $note->id }})" class="btn-outline" style="padding:4px 8px;font-size:11px;">Mark Read</button>
                                @else
                                    <span style="font-size:11px;color:var(--color-text-4);font-weight:600;">READ</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="tbl-empty" style="padding:40px;">No notifications historically match this filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($notifications->hasPages()) <div class="mt-4">{{ $notifications->links() }}</div> @endif
    </div>
</div>
