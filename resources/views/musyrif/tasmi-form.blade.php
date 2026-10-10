@extends('layouts.app')

@section('title', 'Edit Tasmi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit Tasmi</h3>
    <a href="{{ route('musyrif.tasmi.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('musyrif.tasmi.update', $tasmi->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Santri</label>
                    <input type="text" class="form-control" value="{{ $tasmi->santri->user->nama ?? '-' }}" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Juz <span class="text-danger">*</span></label>
                    <input type="number" name="juz" class="form-control" min="1" max="30" value="{{ $tasmi->juz }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Surah <span class="text-danger">*</span></label>
                    <input type="text" name="surah" class="form-control" maxlength="50" value="{{ $tasmi->surah }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">Ayat Awal</label>
                    <input type="number" name="ayat_awal" class="form-control" min="0" value="{{ $tasmi->ayat_awal }}">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Ayat Akhir</label>
                    <input type="number" name="ayat_akhir" class="form-control" min="0" value="{{ $tasmi->ayat_akhir }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Nilai</label>
                    <select name="nilai" class="form-select">
                        <option value="lancar" {{ $tasmi->nilai === 'lancar' ? 'selected' : '' }}>Lancar</option>
                        <option value="cukup_lancar" {{ $tasmi->nilai === 'cukup_lancar' ? 'selected' : '' }}>Cukup Lancar</option>
                        <option value="perlu_ulang" {{ $tasmi->nilai === 'perlu_ulang' ? 'selected' : '' }}>Perlu Ulang</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Tasmi</label>
                    <input type="date" name="tanggal_tasmi" class="form-control" value="{{ $tasmi->tanggal_tasmi ? \Carbon\Carbon::parse($tasmi->tanggal_tasmi)->format('Y-m-d') : '' }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3">{{ $tasmi->catatan }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection