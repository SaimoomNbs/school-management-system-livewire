<div class="tb-notification" x-data="{ notifyOpen: false }" @click.outside="notifyOpen = false" style="position:relative;">
    <button class="tb-icon-btn" @click="notifyOpen = !notifyOpen" style="position:relative;background:none;border:none;cursor:pointer;padding:8px;color:var(--color-text-2);">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if ($unreadCount > 0)
            <span style="position:absolute;top:4px;right:6px;width:16px;height:16px;background:var(--color-danger);color:#fff;font-size:10px;font-weight:700;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid var(--color-surface);">{{ $unreadCount }}</span>
        @endif
    </button>

    <div x-show="notifyOpen" x-cloak style="position:absolute;top:100%;right:0;width:320px;background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--r-md);box-shadow:var(--shadow-lg);z-index:90;margin-top:8px;">
        <div style="padding:16px;border-bottom:1px solid var(--color-border-lgt);display:flex;justify-content:space-between;align-items:center;">
            <h4 style="font-size:14px;font-weight:700;color:var(--color-text-1);margin:0;">Notifications</h4>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" style="font-size:11px;color:var(--color-accent);background:none;border:none;cursor:pointer;font-weight:600;">Mark all read</button>
            @endif
        </div>
        
        <div style="max-height:360px;overflow-y:auto;">
            @forelse ($notifications as $note)
                <div style="padding:14px 16px;border-bottom:1px solid var(--color-border-lgt);display:flex;gap:12px;background:{{ $note->is_read ? 'transparent' : 'var(--color-surface-2)' }};transition:background 0.2sease;">
                    <div style="width:32px;height:32px;border-radius:50%;background:{{ $note->type === 'fee_due' ? 'var(--color-danger-dim)' : 'var(--color-accent-dim)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:{{ $note->type === 'fee_due' ? 'var(--color-danger)' : 'var(--color-accent)' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--color-text-1);margin-bottom:2px;">{{ $note->title }}</div>
                        <div style="font-size:12px;color:var(--color-text-3);line-height:1.4;">{{ $note->message }}</div>
                        <div style="font-size:10px;color:var(--color-text-4);margin-top:6px;font-weight:600;">{{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}</div>
                        @if (!$note->is_read)
                            <button wire:click="markAsRead({{ $note->id }})" style="font-size:11px;color:var(--color-accent);background:none;border:none;cursor:pointer;margin-top:6px;padding:0;">Mark as read</button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding:24px 16px;text-align:center;color:var(--color-text-4);">
                    <p style="font-size:13px;">No notifications found.</p>
                </div>
            @endforelse
        </div>
        
        <div style="padding:10px;text-align:center;border-top:1px solid var(--color-border-lgt);">
            <a href="{{ route('admin.notifications.index') }}" wire:navigate style="font-size:12.5px;font-weight:600;color:var(--color-text-2);text-decoration:none;">View all notifications</a>
        </div>
    </div>
</div>
