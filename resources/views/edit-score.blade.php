<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon'/>
    <title>Edit Score - Lifebook Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=6" rel="stylesheet"/>
    <script src="{{asset('/file/jquery.min.js')}}"></script>
</head>
<body>
    <div class="header">
        <a href="/parents-score" title="Parents Score App">
            <h1>Parents Score App</h1>
        </a>
        <p>Lifebook Academy Parents Management App</p>
    </div>
        
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
                            <input type="text" class="form-control" value="{{ $score->parent->name }}" readonly>
                        </div>
            
                        {{-- Activity --}}
                        <div class="form-group">
                            <label class="form-label">Edit Activity</label>
                            <select class="form-control" name="activity" required>
                                <option value="">Select Activity</option>
                                <option value="Journaling Parents" {{ $score->activity == 'Journaling Parents' ? 'selected' : '' }}>Journaling Parents</option>
                                <option value="Support/Kerjasama" {{ $score->activity == 'Support/Kerjasama' ? 'selected' : '' }}>Support/Kerjasama</option>
                                <option value="Home Gardening" {{ $score->activity == 'Home Gardening' ? 'selected' : '' }}>Home Gardening</option>
                                <option value="Administrasi" {{ $score->activity == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                                <option value="Lifebook Journey" {{ $score->activity == 'Lifebook Journey' ? 'selected' : '' }}>Lifebook Journey</option>
                            </select>
                        </div>
            
                        {{-- Score --}}
                        <div class="form-group">
                            <label class="form-label">Edit Score</label>
                            <input type="number" class="form-control" name="score" 
                                   min="1" max="100" value="{{ $score->score }}" required>
                        </div>
            
                        {{-- Description --}}
                        <div class="form-group full-width">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="deskripsi" 
                                   value="{{ $score->deskripsi }}">
                        </div>
            
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                {{-- Pesan sukses --}}
                @if(session('success'))
                    <div class="success-message mb-4">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>