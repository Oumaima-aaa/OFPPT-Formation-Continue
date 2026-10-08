@props(['theme' => null, 'domaines'])

@php use App\Models\Theme; @endphp

<div class="row g-3">
    <div class="col-md-8">
        <label for="intitule_theme" class="form-label">Intitulé <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-book"></i></span>
            <input type="text" name="intitule_theme" id="intitule_theme" class="form-control @error('intitule_theme') is-invalid @enderror"
                   value="{{ old('intitule_theme', $theme?->intitule_theme) }}" required placeholder="Ex. Développement Web Full Stack">
        </div>
        @error('intitule_theme')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="duree_formation" class="form-label">Durée (jours) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-clock"></i></span>
            <input type="number" name="duree_formation" id="duree_formation" class="form-control @error('duree_formation') is-invalid @enderror"
                   value="{{ old('duree_formation', $theme?->duree_formation) }}" min="1" required>
        </div>
        @error('duree_formation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="nbparticipantmaxi" class="form-label">Participants max / groupe <span class="text-danger">*</span></label>
        <input type="number" name="nbparticipantmaxi" id="nbparticipantmaxi" class="form-control @error('nbparticipantmaxi') is-invalid @enderror"
               value="{{ old('nbparticipantmaxi', $theme?->nbparticipantmaxi ?? 15) }}" min="1" required>
        @error('nbparticipantmaxi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="domaines_id" class="form-label">Domaine <span class="text-danger">*</span></label>
        <select name="domaines_id" id="domaines_id" class="form-select @error('domaines_id') is-invalid @enderror" required>
            <option value="">— Sélectionner un domaine —</option>
            @foreach($domaines as $domaine)
                <option value="{{ $domaine->id }}" @selected(old('domaines_id', $theme?->domaines_id) == $domaine->id)>{{ $domaine->nom_domaine }}</option>
            @endforeach
        </select>
        @error('domaines_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(Theme::activeStatusLabels() as $value => $label)
                <option value="{{ $value }}" @selected((int) old('status', $theme?->status ?? Theme::STATUS_ACTIF) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="alert alert-light border mt-4 mb-0 small">
    <i class="bi bi-info-circle text-primary me-1"></i>
    Un thème <strong>actif</strong> déclenche une notification aux entreprises lors de sa création ou réactivation.
</div>
