@extends('layouts.app')
@section('title', 'Fish Counts')

@section('content')
<style>
    .fc-page {
        padding: 1.75rem 2rem;
        background: #f0f4f8;
        min-height: 100vh;
    }

    .dashboard-content { display: block !important; padding: 0 !important; }

    /* ── Page banner ── */
    .fc-banner {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 60%, #4DB8D5 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
        box-shadow: 0 8px 32px rgba(0,77,115,0.2);
        position: relative;
        overflow: hidden;
    }

    .fc-banner::after {
        content: '';
        position: absolute;
        right: -50px; top: -50px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }

    .fc-banner-text h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.25rem 0;
    }

    .fc-banner-text p {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .fc-delete-all-btn {
        background: linear-gradient(135deg, #e53935, #c62828);
        border: none;
        color: #fff;
        padding: 0.65rem 1.4rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(198,40,40,0.35);
    }

    .fc-delete-all-btn:hover {
        background: linear-gradient(135deg, #c62828, #b71c1c);
        box-shadow: 0 6px 18px rgba(198,40,40,0.5);
        transform: translateY(-1px);
    }

    .fc-delete-all-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(198,40,40,0.3);
    }

    /* ── Flash messages ── */
    .fc-alert {
        padding: 0.9rem 1.2rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .fc-alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #4caf50;
    }

    .fc-alert-error {
        background: #ffebee;
        color: #c62828;
        border-left: 4px solid #f44336;
    }

    /* ── Empty state ── */
    .fc-empty {
        background: #fff;
        border-radius: 14px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        color: #aaa;
    }

    .fc-empty i { font-size: 3rem; margin-bottom: 1rem; display: block; }
    .fc-empty p { font-size: 1rem; margin: 0; }

    /* ── Group card ── */
    .fc-group {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        border: 1px solid rgba(0,136,170,0.08);
        margin-bottom: 1.25rem;
        overflow: hidden;
        transition: box-shadow 0.25s;
    }

    .fc-group:hover {
        box-shadow: 0 6px 24px rgba(0,77,115,0.12);
    }

    .fc-group-head {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 100%);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .fc-group-head:hover {
        background: linear-gradient(135deg, #003D5C 0%, #006B8F 100%);
    }

    .fc-group-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .fc-group-title i { font-size: 1rem; opacity: 0.85; }

    .fc-group-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .fc-group-pill {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.35);
        color: #fff;
    }

    .fc-chevron {
        color: rgba(255,255,255,0.8);
        font-size: 0.85rem;
        transition: transform 0.3s;
    }

    .fc-chevron.open { transform: rotate(180deg); }

    /* ── Group body ── */
    .fc-group-body {
        overflow: hidden;
        transition: max-height 0.35s ease;
    }

    .fc-group-body.collapsed { max-height: 0 !important; }

    /* ── Stats strip ── */
    .fc-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: #e8f4f8;
        border-bottom: 1px solid #e8f4f8;
    }

    .fc-stat {
        background: #f7fbfc;
        padding: 1rem 1.5rem;
        text-align: center;
    }

    .fc-stat-lbl {
        font-size: 0.72rem;
        font-weight: 700;
        color: #0088AA;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.3rem;
    }

    .fc-stat-val {
        font-size: 1.6rem;
        font-weight: 700;
        color: #004D73;
        line-height: 1;
    }

    /* ── Table ── */
    .fc-table-wrap {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 520px;
    }

    .fc-table-wrap::-webkit-scrollbar { width: 6px; height: 6px; }
    .fc-table-wrap::-webkit-scrollbar-track { background: #f1f1f1; }
    .fc-table-wrap::-webkit-scrollbar-thumb { background: #0088AA; border-radius: 3px; }

    .fc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .fc-table thead th {
        background: #edf6f9;
        color: #004D73;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0.85rem 1.25rem;
        border-bottom: 2px solid #b8dce8;
        position: sticky;
        top: 0;
        z-index: 5;
        white-space: nowrap;
    }

    .fc-table thead th i {
        color: #0088AA;
        margin-right: 0.35rem;
    }

    .fc-table tbody td {
        padding: 0.8rem 1.25rem;
        border-bottom: 1px solid #f0f7f9;
        color: #333;
        vertical-align: middle;
    }

    .fc-table tbody tr:last-child td { border-bottom: none; }

    .fc-table tbody tr:hover td { background: #f5fbfd; }

    .fc-table .tc { text-align: center; }

    /* ── Thumbnail ── */
    .fc-thumb {
        width: 72px;
        height: 52px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        border: 2px solid #e8f4f8;
        transition: border-color 0.2s, transform 0.2s;
        display: block;
        margin: 0 auto;
    }

    .fc-thumb:hover {
        border-color: #0088AA;
        transform: scale(1.05);
    }

    /* ── Count badge ── */
    .fc-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #004D73, #0088AA);
        color: #fff;
        font-weight: 700;
        font-size: 0.85rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
    }

    /* ── Delete btn ── */
    .fc-del-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.4rem;
        border-radius: 6px;
        transition: background 0.2s, transform 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .fc-del-btn:hover {
        background: #ffebee;
        transform: scale(1.1);
    }

    .fc-del-btn i { color: #e57373; font-size: 0.95rem; }

    /* ── Modal ── */
    .fc-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.75);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(3px);
    }

    .fc-modal.open { display: flex; }

    .fc-modal-box {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        max-width: 88vw;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: relative;
        min-width: 320px;
    }

    .fc-modal-head {
        background: linear-gradient(135deg, #004D73, #0088AA);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .fc-modal-head h3 {
        margin: 0;
        color: #fff;
        font-size: 1rem;
        font-weight: 600;
    }

    .fc-modal-close {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .fc-modal-close:hover { background: rgba(255,255,255,0.3); }

    .fc-modal-img-wrap {
        flex: 1;
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: auto;
        background: #f7fbfc;
    }

    .fc-modal-img {
        max-width: 100%;
        max-height: 65vh;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .fc-modal-foot {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e8f4f8;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .fc-nav-btn {
        background: linear-gradient(135deg, #004D73, #0088AA);
        color: #fff;
        border: none;
        padding: 0.55rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .fc-nav-btn:hover { opacity: 0.88; transform: translateY(-1px); }

    .fc-counter {
        font-size: 0.88rem;
        font-weight: 600;
        color: #004D73;
        min-width: 70px;
        text-align: center;
    }
</style>

<div class="fc-page">

    {{-- Banner --}}
    <div class="fc-banner">
        <div class="fc-banner-text">
            <h1><i class="fas fa-fish" style="margin-right:0.5rem;opacity:0.85;"></i>Fish Counts</h1>
            <p>All recorded detections grouped by date and session</p>
        </div>
        <form method="POST" action="{{ route('fish-counts.delete-all') }}"
              onsubmit="return confirm('Delete ALL data? This cannot be undone.');">
            @csrf
            <button type="submit" class="fc-delete-all-btn">
                <i class="fas fa-trash-alt"></i> Delete All Data
            </button>
        </form>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="fc-alert fc-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fc-alert fc-alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Groups --}}
    @forelse($groups as $key => $group)
        @php $imgCount = count($group['images']); @endphp
        <div class="fc-group">

            <div class="fc-group-head" onclick="toggleGroup(this)">
                <div class="fc-group-title">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $group['date'] }}
                    <span style="opacity:0.75; font-weight:400; font-size:0.85rem;">— Session #{{ $group['group_id'] }}</span>
                </div>
                <div class="fc-group-meta">
                    <span class="fc-group-pill">{{ $imgCount }} {{ Str::plural('image', $imgCount) }}</span>
                    <span class="fc-group-pill">{{ $group['total_count'] }} fish</span>
                    <i class="fas fa-chevron-down fc-chevron open"></i>
                </div>
            </div>

            <div class="fc-group-body" style="max-height: 2000px;">

                <div class="fc-stats">
                    <div class="fc-stat">
                        <div class="fc-stat-lbl">Total Images</div>
                        <div class="fc-stat-val">{{ $imgCount }}</div>
                    </div>
                    <div class="fc-stat">
                        <div class="fc-stat-lbl">Total Fish</div>
                        <div class="fc-stat-val">{{ $group['total_count'] }}</div>
                    </div>
                    <div class="fc-stat">
                        <div class="fc-stat-lbl">Avg per Image</div>
                        <div class="fc-stat-val">{{ round($group['total_count'] / max($imgCount, 1), 1) }}</div>
                    </div>
                </div>

                <div class="fc-table-wrap">
                    <table class="fc-table">
                        <thead>
                            <tr>
                                <th class="tc"><i class="fas fa-hashtag"></i>ID</th>
                                <th class="tc"><i class="fas fa-image"></i>Image</th>
                                <th class="tc"><i class="fas fa-box"></i>Cage</th>
                                <th class="tc"><i class="fas fa-fish"></i>Count</th>
                                <th class="tc"><i class="fas fa-clock"></i>Time</th>
                                <th class="tc"><i class="fas fa-cog"></i>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group['images'] as $img)
                                <tr>
                                    <td class="tc" style="font-weight:600; color:#004D73;">#{{ $img->id }}</td>
                                    <td class="tc">
                                        <img
                                            src="{{ asset($img->file_path) }}"
                                            class="fc-thumb clickable-image"
                                            data-path="{{ asset($img->file_path) }}"
                                            data-name="Image #{{ $img->id }}"
                                            alt="Capture #{{ $img->id }}"
                                        >
                                    </td>
                                    <td class="tc">
                                        <span style="background:#e8f4f8; color:#004D73; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.8rem; font-weight:600;">
                                            {{ $img->cage }}
                                        </span>
                                    </td>
                                    <td class="tc">
                                        <span class="fc-count-badge">{{ $img->count }}</span>
                                    </td>
                                    <td class="tc" style="color:#666; font-size:0.85rem;">
                                        {{ $img->created_at->format('H:i:s') }}
                                    </td>
                                    <td class="tc">
                                        <form method="POST" action="{{ route('fish-counts.delete', $img->id) }}"
                                              style="display:inline;"
                                              onsubmit="return confirm('Delete this capture?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="fc-del-btn" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @empty
        <div class="fc-empty">
            <i class="fas fa-inbox"></i>
            <p>No fish count data recorded yet.</p>
        </div>
    @endforelse

</div>

{{-- Image Modal --}}
<div id="fcModal" class="fc-modal">
    <div class="fc-modal-box">
        <div class="fc-modal-head">
            <h3 id="fcModalTitle">Image</h3>
            <button class="fc-modal-close" id="fcModalClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="fc-modal-img-wrap">
            <img id="fcModalImg" src="" alt="Capture" class="fc-modal-img">
        </div>
        <div class="fc-modal-foot">
            <button class="fc-nav-btn" id="fcPrev"><i class="fas fa-chevron-left"></i> Prev</button>
            <span class="fc-counter" id="fcCounter">1 / 1</span>
            <button class="fc-nav-btn" id="fcNext">Next <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
    // ── Collapse/expand groups ──
    window.toggleGroup = function(head) {
        var body    = head.nextElementSibling;
        var chevron = head.querySelector('.fc-chevron');
        var isOpen  = !body.classList.contains('collapsed');
        if (isOpen) {
            body.style.maxHeight = body.scrollHeight + 'px';
            requestAnimationFrame(function() {
                body.style.maxHeight = '0';
                body.classList.add('collapsed');
                chevron.classList.remove('open');
            });
        } else {
            body.classList.remove('collapsed');
            body.style.maxHeight = body.scrollHeight + 'px';
            chevron.classList.add('open');
            setTimeout(function() { body.style.maxHeight = '2000px'; }, 350);
        }
    };

    // ── Image modal ──
    var modal    = document.getElementById('fcModal');
    var modalImg = document.getElementById('fcModalImg');
    var modalTtl = document.getElementById('fcModalTitle');
    var counter  = document.getElementById('fcCounter');
    var images   = Array.from(document.querySelectorAll('.clickable-image'));
    var current  = 0;

    function openModal(idx) {
        current = idx;
        var img = images[current];
        modalImg.src      = img.dataset.path;
        modalTtl.textContent = img.dataset.name;
        counter.textContent  = (current + 1) + ' / ' + images.length;
        modal.classList.add('open');
    }

    images.forEach(function(img, i) {
        img.addEventListener('click', function() { openModal(i); });
    });

    document.getElementById('fcModalClose').addEventListener('click', function() {
        modal.classList.remove('open');
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.classList.remove('open');
    });

    document.getElementById('fcPrev').addEventListener('click', function() {
        openModal((current - 1 + images.length) % images.length);
    });

    document.getElementById('fcNext').addEventListener('click', function() {
        openModal((current + 1) % images.length);
    });

    document.addEventListener('keydown', function(e) {
        if (!modal.classList.contains('open')) return;
        if (e.key === 'ArrowLeft')  document.getElementById('fcPrev').click();
        if (e.key === 'ArrowRight') document.getElementById('fcNext').click();
        if (e.key === 'Escape')     modal.classList.remove('open');
    });
})();
</script>
@endsection
