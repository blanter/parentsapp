@extends('layouts.admin')

@section('title', 'Edit Score')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'Edit Parents Score')

@section('content')
    <div class="container">
        <div class="content">
            <!-- Form Section -->
            <div class="form-section">
                <form method="POST" action="{{ route('score.update', $score->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row">

                        {{-- Parent Name (Readonly) --}}
                        <div class="form-group full-width">
                            <label class="form-label">Parents Name</label>
                            <input type="text" class="form-control"
                                value="{{ $score->user->name ?? ($score->parent->name ?? 'Deleted User') }}" readonly>
                        </div>

                        {{-- Activity --}}
                        <div class="form-group">
                            <label class="form-label">Edit Activity</label>
                            <select class="form-control" name="activity" required>
                                <option value="">Select Activity</option>
                                <option value="Journaling Parents" {{ $score->activity == 'Journaling Parents' ? 'selected' : '' }}>Journaling Parents</option>
                                <option value="Support/Kerjasama" {{ $score->activity == 'Support/Kerjasama' ? 'selected' : '' }}>Support/Kerjasama</option>
                                <option value="Home Gardening" {{ $score->activity == 'Home Gardening' ? 'selected' : '' }}>
                                    Home Gardening</option>
                                <option value="Administrasi" {{ $score->activity == 'Administrasi' ? 'selected' : '' }}>
                                    Administrasi</option>
                                <option value="Lifebook Journey" {{ $score->activity == 'Lifebook Journey' ? 'selected' : '' }}>Lifebook Journey</option>
                            </select>
                        </div>

                        {{-- Score --}}
                        <div class="form-group">
                            <label class="form-label">Edit Score</label>
                            <input type="number" class="form-control" name="score" min="1" max="100"
                                value="{{ $score->score }}" required>
                        </div>

                        {{-- Description --}}
                        <div class="form-group full-width">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="deskripsi" value="{{ $score->deskripsi }}">
                        </div>

                    </div>
                    <button type="submit" class="auth-btn-primary" style="margin-top: 10px; width: 100%;">
                        <i data-lucide="save"></i>
                        <span>Update Score</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection