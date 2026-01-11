@if($filled)
    @if($replied)
        <div class="ct-badge-replied">
            <i data-lucide="message-square" class="ct-badge-icon"></i>
            <span>Replied</span>
        </div>
    @else
        <div class="ct-badge-filled">
            <i data-lucide="check" class="ct-badge-icon-stroke"></i>
            <span>Filled</span>
        </div>
    @endif
@else
    <div class="ct-badge-empty">
        <i data-lucide="minus" class="ct-badge-icon"></i>
        <span>Empty</span>
    </div>
@endif