@php
    $statusFlow = $order->getStatusFlow();
    $currentIndex = array_search($order->status, array_keys($statusFlow));
    $nextStatus = $order->getNextStatus();
    $statusLabels = \App\Models\Order::getStatusLabels();

    // Debug info
    $debug = [
        'current_status' => $order->status,
        'is_active' => $order->isActive(),
        'next_status' => $nextStatus,
        'status_flow' => $statusFlow,
        'current_index' => $currentIndex
    ];
@endphp

{{-- Debug (remove in production) --}}
{{-- <div class="text-xs text-gray-500 mb-2">
    <strong>Debug:</strong> {{ json_encode($debug) }}
</div> --}}

<div class="space-y-3">
    {{-- Progress Steps --}}
    <div class="flex items-center gap-2">
        @foreach($statusFlow as $statusKey => $statusLabel)
            @php
                $stepIndex = array_search($statusKey, array_keys($statusFlow));
                $isCompleted = $stepIndex < $currentIndex;
                $isCurrent = $stepIndex === $currentIndex;
                $isPending = $stepIndex > $currentIndex;
            @endphp
            <div class="flex items-center gap-1">
                <div class="flex h-2 w-2 items-center justify-center rounded-full
                    @if($isCompleted) bg-green-500
                    @elseif($isCurrent) bg-blue-500 animate-pulse
                    @else bg-slate-300
                    @endif">
                </div>
                @if($stepIndex < count($statusFlow) - 1)
                    <div class="h-px w-4
                        @if($isCompleted) bg-green-500
                        @else bg-slate-300
                        @endif">
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Action Button --}}
    @if($order->status !== 'selesai' && $order->status !== 'dibatalkan' && $nextStatus)
        <button type="button"
                class="order-status-btn inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium transition-all
                       bg-blue-50 text-blue-700 hover:bg-blue-100 hover:text-blue-800
                       disabled:opacity-50 disabled:cursor-not-allowed"
                data-order-id="{{ $order->id }}"
                data-current-status="{{ $order->status }}"
                data-next-status="{{ $nextStatus }}"
                data-next-label="{{ $statusLabels[$nextStatus] ?? $nextStatus }}">
            <i class="fas fa-arrow-right"></i>
            <span>{{ $statusLabels[$nextStatus] ?? $nextStatus }}</span>
            <div class="status-spinner hidden">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </button>
    @elseif($order->status === 'selesai')
        <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700">
            <i class="fas fa-check-circle"></i>
            Selesai
        </span>
    @elseif($order->status === 'dibatalkan')
        <span class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700">
            <i class="fas fa-times-circle"></i>
            Dibatalkan
        </span>
    @else
        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 px-3 py-2 text-xs font-medium text-slate-500">
            <i class="fas fa-clock"></i>
            Menunggu
        </span>
    @endif
</div>