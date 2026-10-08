<section>
    <h2 class="h5 mb-3 text-danger">Supprimer le compte</h2>
    <p class="text-muted">Une fois votre compte supprimé, toutes vos données seront définitivement effacées.</p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        Supprimer le compte
    </button>

    <div class="modal fade @if($errors->userDeletion->isNotEmpty()) show @endif" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" @if($errors->userDeletion->isEmpty()) aria-hidden="true" @endif @if($errors->userDeletion->isNotEmpty()) style="display: block;" @endif>
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionLabel">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Confirmez avec votre mot de passe">
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($errors->userDeletion->isNotEmpty())
        <div class="modal-backdrop fade show"></div>
    @endif
</section>
