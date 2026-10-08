@extends('layouts.app')



@section('title', 'Nouveau plan de formation')



@php

    $header = 'Nouveau plan de formation';

    use App\Models\Plan;

@endphp



@section('content')

    <div class="card">

        <div class="card-body">

            <form action="{{ route('plans.store') }}" method="POST">

                @csrf



                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label for="exercice" class="form-label">Exercice (année) <span class="text-danger">*</span></label>

                        <input type="number" name="exercice" id="exercice" class="form-control @error('exercice') is-invalid @enderror" value="{{ old('exercice', date('Y')) }}" min="2000" max="2100" required>

                        @error('exercice')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label for="etablissements_id" class="form-label">Établissement <span class="text-danger">*</span></label>

                        <select name="etablissements_id" id="etablissements_id" class="form-select @error('etablissements_id') is-invalid @enderror" required>

                            <option value="">— Sélectionner —</option>

                            @foreach($etablissements as $etablissement)

                                <option value="{{ $etablissement->id }}" @selected(old('etablissements_id') == $etablissement->id)>{{ $etablissement->nom_efp }} ({{ $etablissement->region->nom_region ?? '' }})</option>

                            @endforeach

                        </select>

                        @error('etablissements_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label for="themes_id" class="form-label">Thème <span class="text-danger">*</span></label>

                        <select name="themes_id" id="themes_id" class="form-select @error('themes_id') is-invalid @enderror" required>

                            <option value="">— Sélectionner —</option>

                            @foreach($themes as $theme)

                                <option value="{{ $theme->id }}" @selected(old('themes_id') == $theme->id)>{{ $theme->intitule_theme }}</option>

                            @endforeach

                        </select>

                        @error('themes_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                </div>



                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label for="nbjours" class="form-label">Nombre de jours <span class="text-danger">*</span></label>

                        <input type="number" name="nbjours" id="nbjours" class="form-control @error('nbjours') is-invalid @enderror" value="{{ old('nbjours') }}" min="1" required>

                        @error('nbjours')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="nbparticipantmaxi" class="form-label">Participants max <span class="text-danger">*</span></label>

                        <input type="number" name="nbparticipantmaxi" id="nbparticipantmaxi" class="form-control @error('nbparticipantmaxi') is-invalid @enderror" value="{{ old('nbparticipantmaxi') }}" min="1" required>

                        @error('nbparticipantmaxi')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="nb_groupes" class="form-label">Nombre de groupes <span class="text-danger">*</span></label>

                        <input type="number" name="nb_groupes" id="nb_groupes" class="form-control @error('nb_groupes') is-invalid @enderror" value="{{ old('nb_groupes', 1) }}" min="1" required>

                        @error('nb_groupes')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="date_debut_previsionnelle" class="form-label">Début prévisionnel</label>

                        <input type="date" name="date_debut_previsionnelle" id="date_debut_previsionnelle" class="form-control @error('date_debut_previsionnelle') is-invalid @enderror" value="{{ old('date_debut_previsionnelle') }}">

                        @error('date_debut_previsionnelle')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="cout_previsionnel" class="form-label">Coût prévisionnel (DH) <span class="text-danger">*</span></label>

                        <input type="number" name="cout_previsionnel" id="cout_previsionnel" class="form-control @error('cout_previsionnel') is-invalid @enderror" value="{{ old('cout_previsionnel') }}" min="0" step="0.01" required>

                        @error('cout_previsionnel')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    @if(auth()->user()->isRegionalManager() || auth()->user()->isLocalManager())
                    <div class="col-md-3 mb-3">

                        <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>

                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>

                            @foreach(Plan::statusLabels() as $value => $label)

                                <option value="{{ $value }}" @selected((int) old('status', Plan::STATUS_BROUILLON) === $value)>{{ $label }}</option>

                            @endforeach

                        </select>

                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>
                    @endif

                </div>



                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">Enregistrer</button>

                    <a href="{{ route('plans.index') }}" class="btn btn-secondary">Annuler</a>

                </div>

            </form>

        </div>

    </div>

@endsection


