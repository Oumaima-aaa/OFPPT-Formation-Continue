@extends('layouts.app')



@section('title', 'Modifier le domaine')



@php

    $header = 'Modifier le domaine';

    use App\Models\Domaine;

@endphp



@section('content')

    <div class="card">

        <div class="card-body">

            <form action="{{ route('domaines.update', $domaine) }}" method="POST">

                @csrf

                @method('PUT')



                <div class="mb-3">

                    <label for="nom_domaine" class="form-label">Nom du domaine <span class="text-danger">*</span></label>

                    <input type="text" name="nom_domaine" id="nom_domaine" class="form-control @error('nom_domaine') is-invalid @enderror" value="{{ old('nom_domaine', $domaine->nom_domaine) }}" required>

                    @error('nom_domaine')

                        <div class="invalid-feedback">{{ $message }}</div>

                    @enderror

                </div>



                <div class="mb-3">

                    <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>

                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>

                        @foreach(Domaine::activeStatusLabels() as $value => $label)

                            <option value="{{ $value }}" @selected((int) old('status', $domaine->status) === $value)>{{ $label }}</option>

                        @endforeach

                    </select>

                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror

                </div>



                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">Mettre à jour</button>

                    <a href="{{ route('domaines.index') }}" class="btn btn-secondary">Annuler</a>

                </div>

            </form>

        </div>

    </div>

@endsection


