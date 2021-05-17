<div class="modal fade @if($display) show @endif" id="myModal" @if($display)
    style="padding-right: 17px; display: block;" @endif>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-light">
                <h4 class="modal-title">Edit User - {{ $name }}</h4>
            </div>

            <div class="modal-body">
                <form wire:submit.prevent='update_user'>
                    @csrf
                    <input type="hidden" wire:model="user_id" />
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Success!</strong> {{ session('success') }}
                            </div>
                            @endif
                            @if (session()->has('fail'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Failed!</strong> {{ session('fail') }}
                            </div>
                            @endif
                        </div>
                        @foreach ($inputs as $input)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="{{ ucfirst($input['sort']) }}">{{ ucfirst($input['sort']) }}:</label>
                                    <input class="form-control @error($input['sort']) is-invalid @enderror" type="text"
                                        wire:model="{{ $input['sort'] }}" />
                                    @error($input['sort']) <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-6 mx-auto">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block" wire:target="update_user" wire.loading.attr="disabled">
                                    <span wire:loading wire:target="update_user">
                                        <span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...
                                    </span>
                                    <span wire:loading.remove wire:target="update_user">Update</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" wire:click="close_modal" wire:target="close_modal" wire.loading.attr="disabled">
                    <span wire:loading wire:target="close_modal">
                        <span class="spinner-border spinner-border-sm"></span>&nbsp;Removing...
                    </span>
                    <span wire:loading.remove wire:target="close_modal">Close</span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("turbolinks:load", () => {
    Livewire.on("editUser", (obj) => {
        var modalBackDrop = document.createElement("div");
        modalBackDrop.setAttribute('id', 'modal-backdrop');
        modalBackDrop.setAttribute('class', 'modal-backdrop fade show');

        var modalBackDropLoader = document.createElement("div");
        modalBackDropLoader.setAttribute('id', 'modal-backdrop-loader');
        modalBackDropLoader.setAttribute('class', 'w-100 h-100');

        modalBackDrop.appendChild(modalBackDropLoader);

        @this.edit_user(obj);
        document.querySelector('body').appendChild(modalBackDrop);
    });
    Livewire.on("closeModal", () => {
        document.getElementById('modal-backdrop').remove();
    });
});
</script>