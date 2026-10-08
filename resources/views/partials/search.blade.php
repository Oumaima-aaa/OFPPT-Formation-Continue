<form method="GET" class="search-bar">
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary btn-icon">
            <i class="bi bi-funnel"></i> Filtrer
        </button>
        @if(request('search'))
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Réinitialiser</a>
        @endif
    </div>
</form>
