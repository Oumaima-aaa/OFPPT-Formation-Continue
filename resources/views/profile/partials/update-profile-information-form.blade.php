<section>
    <h2 class="h5 mb-3">Informations du profil</h2>
    <p class="text-muted">Mettez à jour les informations de votre compte et votre adresse e-mail.</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-3">
        @csrf
        @method('patch')

        <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">Prénom</label>
                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required autofocus>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Nom</label>
                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="address" class="form-label">Adresse</label>
                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}">
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="poste" class="form-label">Poste</label>
                <input type="text" name="poste" id="poste" class="form-control @error('poste') is-invalid @enderror" value="{{ old('poste', $user->poste) }}">
                @error('poste')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="departement" class="form-label">Département</label>
                <input type="text" name="departement" id="departement" class="form-control @error('departement') is-invalid @enderror" value="{{ old('departement', $user->departement) }}">
                @error('departement')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3">
                <p class="text-muted small mb-1">Votre adresse e-mail n'est pas vérifiée.</p>
                <button form="send-verification" class="btn btn-link btn-sm p-0">Cliquez ici pour renvoyer l'e-mail de vérification.</button>
                @if (session('status') === 'verification-link-sent')
                    <p class="text-success small mt-2 mb-0">Un nouveau lien de vérification a été envoyé.</p>
                @endif
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 mt-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            @if (session('status') === 'profile-updated')
                <span class="text-success small">Enregistré.</span>
            @endif
        </div>
    </form>
</section>
