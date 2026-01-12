@extends('layouts.admin')

@section('title', 'Gardening Management')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'Home Gardening Activities')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="adm-card">
                <div class="adm-card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <h3 style="font-weight: 800; font-size: 18px; color: var(--db-text-dark); margin: 0;">Parent
                            Gardening Activities</h3>
                        <div class="adm-gn-stats">
                            <i data-lucide="sprout" style="width: 16px;"></i>
                            <span>Total Plants: {{ $plants->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="adm-table-wrapper">
                        <table class="adm-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">User & Plant Name</th>
                                    <th>Method</th>
                                    <th>Last Activity</th>
                                    <th class="text-center">Reports</th>
                                    <th class="pe-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plants as $plant)
                                    <tr>
                                        <td class="ps-4">
                                            <div style="display: flex; align-items: center; gap: 15px;">
                                                <div class="adm-gn-img-box">
                                                    @if($plant->image)
                                                        <img src="{{ asset('gardening/' . $plant->image) }}">
                                                    @else
                                                        <i data-lucide="{{ $plant->icon ?: 'sprout' }}" style="color: var(--db-secondary); width: 22px;"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div style="font-weight: 800; color: var(--db-text-dark); font-size: 14px; line-height: 1.2;">{{ $plant->plant_name }}</div>
                                                    <div style="font-size: 12px; font-weight: 600; color: var(--db-purple); margin-top: 4px;">{{ $plant->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="adm-gn-method-badge">{{ $plant->method }}</span>
                                        </td>
                                        <td>
                                            @if($plant->progress->first())
                                                <div style="font-size: 13px; font-weight: 700; color: var(--db-text-dark);">
                                                    {{ \Carbon\Carbon::parse($plant->progress->first()->report_date)->format('d M Y') }}
                                                </div>
                                                <div style="font-size: 11px; color: #9CA3AF; font-weight: 600; margin-top: 2px;">
                                                    {{ Str::limit($plant->progress->first()->description, 35) }}
                                                </div>
                                            @else
                                                <span style="font-size: 12px; font-weight: 600; color: #9CA3AF;">No activities yet</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div style="display: inline-flex; align-items: center; gap: 6px; font-weight: 800; font-size: 14px; background: #F3F4F6; padding: 4px 10px; border-radius: 8px;">
                                                <i data-lucide="file-text" style="width: 14px; color: #6B7280;"></i>
                                                {{ $plant->progress->count() }}
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <button class="btn-warning" onclick="viewDetail('{{ $plant->id }}')"
                                                style="border-radius: 12px; padding: 10px 20px; font-weight: 800; font-size: 12px; border: none; box-shadow: 0 4px 0px #e6a51d; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; justify-content: center;">
                                                <i data-lucide="star" style="width: 14px; stroke-width: 3px;"></i> 
                                                <span>Score Activity</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Progress -->
    <div id="plantDetailModal" class="ct-modal-overlay">
        <div class="ct-modal-card" style="max-width: 800px;">
            <div class="ct-modal-header">
                <div class="ct-modal-title-group">
                    <div class="ct-modal-header-icon">
                        <i data-lucide="sprout"></i>
                    </div>
                    <div>
                        <h3 class="ct-modal-title">Gardening Activity Reports</h3>
                        <p class="ct-modal-subtitle" id="modalPlantInfo">Plant Data</p>
                    </div>
                </div>
                <button class="ct-modal-close" onclick="closePlantDetailModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="ct-modal-body" id="progressList" style="max-height: 70vh; overflow-y: auto;">
                <!-- Progress items will be injected here -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const plantsData = @json($plants);

        function viewDetail(plantId) {
            const plant = plantsData.find(p => p.id == plantId);
            if (!plant) return;

            $('#modalPlantInfo').text(`${plant.plant_name} • Parent: ${plant.user.name}`);

            const progressList = $('#progressList');
            progressList.html('');

            if (plant.progress.length === 0) {
                progressList.html(`
                    <div class="ct-premium-empty-wrapper">
                        <div class="ct-premium-empty-content">
                            <i data-lucide="frown" class="ct-premium-empty-icon"></i>
                            <p class="ct-premium-empty-text">This parent hasn't submitted progress reports yet.</p>
                        </div>
                    </div>
                `);
            } else {
                plant.progress.forEach(p => {
                    let imgHtml = '';
                    if (p.image) {
                        imgHtml = `<img src="/gardening/progress/${p.image}" class="adm-gn-progress-img">`;
                    }

                    const itemHtml = `
                        <div class="adm-gn-progress-item shadow-sm">
                            ${imgHtml}
                            <div class="adm-gn-progress-content">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap;">
                                    <div style="flex: 1; min-width: 200px;">
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                            <i data-lucide="calendar" style="width: 14px; color: var(--db-purple);"></i>
                                            <span style="font-weight: 800; color: var(--db-text-dark); font-size: 14px;">
                                                ${new Date(p.report_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                                            </span>
                                        </div>
                                        <p style="font-size: 13px; color: #4B5563; font-weight: 500; line-height: 1.6; margin: 0;">${p.description || 'No description provided.'}</p>
                                    </div>
                                    <div class="adm-gn-score-box">
                                        <div class="adm-gn-score-label">Current Score</div>
                                        <div class="adm-gn-score-value">${p.score || 0}</div>
                                    </div>
                                </div>

                                <form action="{{ route('admin.gardening.update-score') }}" method="POST" class="adm-gn-score-form">
                                    @csrf
                                    <input type="hidden" name="progress_id" value="${p.id}">
                                    <div style="flex-grow: 1;">
                                        <label style="display: block; font-size: 11px; font-weight: 800; color: #9CA3AF; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Update Assessment Score</label>
                                        <input type="number" name="score" value="${p.score || 0}" class="auth-form-control" style="height: 48px; background: #fff; border: 2px solid #F3F4F6;" required>
                                    </div>
                                    <button type="submit" class="auth-btn-primary" style="height: 48px; padding: 0 25px;">
                                        <i data-lucide="check-circle" style="width: 18px;"></i> <span>Update Score</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    `;
                    progressList.append(itemHtml);
                });
            }

            $('#plantDetailModal').fadeIn(300).css('display', 'flex');
            lucide.createIcons();
        }

        function closePlantDetailModal() {
            $('#plantDetailModal').fadeOut(300);
            document.body.style.overflow = 'auto';
        }

        $('#plantDetailModal').on('click', function(e) {
            if ($(e.target).hasClass('ct-modal-overlay')) {
                closePlantDetailModal();
            }
        });
    </script>
@endsection