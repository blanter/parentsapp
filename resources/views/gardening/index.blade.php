@extends('layouts.app')

@section('title', 'Home Gardening - Lifebook Parents')

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="gn-container">
        <div class="db-header" style="margin-bottom: 20px;">
            <div class="db-brand-section">
                <a href="{{ route('dashboard') }}"
                    style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="chevron-left" style="width: 30px; height: 30px;"></i>
                    <h1 style="font-size: 24px; margin: 0;">Back</h1>
                </a>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section" style="width: 45px; height: 45px;">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user"></i>
                @endif
            </a>
        </div>

        <div class="gn-header">
            <h1>Home Gardening</h1>
            <p class="gn-subtitle">Mari bersama belajar merawat tanaman!</p>
        </div>

        <div class="gn-list">
            @forelse($plants as $plant)
                <a href="{{ route('gardening.show', $plant->id) }}" class="gn-item" style="display: flex;">
                    <div class="gn-item-img">
                        @if($plant->image)
                            <img src="{{ asset('gardening/' . $plant->image) }}" alt="{{ $plant->plant_name }}">
                        @else
                            <i data-lucide="{{ $plant->icon ?: 'sprout' }}"></i>
                        @endif
                    </div>
                    <div class="gn-item-info">
                        <div class="gn-item-method">{{ $plant->method }}</div>
                        <div class="gn-item-name">{{ $plant->plant_name }}</div>
                        <div style="font-size: 11px; opacity: 0.5; margin-top: 5px; font-weight: 600;">
                            Ditanam: {{ \Carbon\Carbon::parse($plant->planting_date)->format('d M Y') }}
                        </div>
                    </div>
                    <form action="{{ route('gardening.destroy', $plant->id) }}" method="POST"
                        style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="background: none; border: none; color: #ef4444; cursor: pointer; opacity: 0.3;"
                            onclick="event.stopPropagation(); return confirm('Hapus tanaman ini?')">
                            <i data-lucide="trash-2" style="width: 18px; height: 18px;"></i>
                        </button>
                    </form>
                </a>
            @empty
                <div
                    style="text-align: center; padding: 40px 20px; background: #fff; border-radius: 30px; border: 2px dashed #E5E7EB;">
                    <i data-lucide="sprout"
                        style="width: 50px; height: 50px; color: var(--db-secondary); opacity: 0.3; margin-bottom: 15px;"></i>
                    <p style="font-weight: 700; opacity: 0.5;">Belum ada tanaman.<br>Yuk mulai menanam!</p>
                </div>
            @endforelse
        </div>

        <button class="gn-add-btn" onclick="openModal()">
            <i data-lucide="plus-circle"></i>
            <span>Tambah Tanaman</span>
        </button>

        <div
            style="text-align: center; margin-top: 40px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>

    <!-- Modal Tambah Tanaman -->
    <div id="addPlantModal" class="gn-modal">
        <div class="gn-modal-content">
            <div class="gn-modal-header">
                <h2 class="gn-modal-title">Tambah Tanaman</h2>
                <button class="gn-close-modal" onclick="closeModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <form action="{{ route('gardening.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="gn-form-group">
                    <label class="gn-label">Pilih Metode</label>
                    <div class="gn-method-grid">
                        <label class="gn-method-opt active">
                            <input type="radio" name="method" value="Hidroponic" checked onchange="updateMethodUI(this)">
                            Hidroponic
                        </label>
                        <label class="gn-method-opt">
                            <input type="radio" name="method" value="Soil" onchange="updateMethodUI(this)">
                            Soil
                        </label>
                        <label class="gn-method-opt">
                            <input type="radio" name="method" value="Creative Method" onchange="updateMethodUI(this)">
                            Creative
                        </label>
                    </div>
                </div>

                <div class="gn-form-group">
                    <label class="gn-label">Nama Tanaman</label>
                    <input type="text" name="plant_name" class="gn-input" placeholder="Contoh: Tanaman Cabai" required>
                </div>

                <div class="gn-form-group">
                    <label class="gn-label">Tanggal Tanam</label>
                    <input type="date" name="planting_date" class="gn-input" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="gn-form-group">
                    <label class="gn-label">Pilih Gambar atau Icon</label>

                    <!-- Image Upload -->
                    <div style="margin-bottom: 15px;">
                        <input type="file" name="image" class="gn-input" id="plant-image" onchange="handleImageSelect(this)"
                            style="padding: 10px;">
                        <p style="font-size: 11px; margin-top: 5px; opacity: 0.5; font-weight: 600;">Upload foto jika
                            ada</p>
                    </div>

                    <!-- Icon Grid -->
                    <div class="gn-icon-grid">
                        @foreach(['sprout', 'leaf', 'flower-2', 'tree-pine', 'carrot'] as $icon)
                            <label class="gn-icon-opt {{ $icon === 'sprout' ? 'active' : '' }}">
                                <input type="radio" name="icon" value="{{ $icon }}" {{ $icon === 'sprout' ? 'checked' : '' }}
                                    onchange="updateIconUI(this)">
                                <i data-lucide="{{ $icon }}"></i>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="auth-btn-primary" style="margin-top: 10px; width: 100%;">
                    <span>Simpan Tanaman</span>
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openModal() {
            document.getElementById('addPlantModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('addPlantModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function updateMethodUI(radio) {
            const opts = document.querySelectorAll('.gn-method-opt');
            opts.forEach(opt => opt.classList.remove('active'));
            radio.parentElement.classList.add('active');
        }

        function handleImageSelect(input) {
            // Optional: disable icon select if image is selected
        }

        function updateIconUI(radio) {
            const opts = document.querySelectorAll('.gn-icon-opt');
            opts.forEach(opt => opt.classList.remove('active'));
            radio.parentElement.classList.add('active');
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('addPlantModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection