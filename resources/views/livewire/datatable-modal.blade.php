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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Name:</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    wire:model="name" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="text"
                                    wire:model="email" />
                                @error('email') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Phone:</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="tel"
                                    wire:model="phone" />
                                @error('phone') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Gender:</label>
                                <select class="form-control @error('gender') is-invalid @enderror" type="text"
                                    wire:model="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">County:</label>
                                <input class="form-control @error('country') is-invalid @enderror" type="text"
                                    wire:model="country" />
                                @error('country') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">State:</label>
                                <input class="form-control @error('state') is-invalid @enderror" type="text"
                                    wire:model="state" />
                                @error('state') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">City:</label>
                                <input class="form-control @error('city') is-invalid @enderror" type="text"
                                    wire:model="city" />
                                @error('city') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Address:</label>
                                <textarea rows="3" class="form-control @error('address') is-invalid @enderror"
                                    wire:model="address"></textarea>
                                @error('address') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mx-auto">
                            <div class="form-group">
                                <button wire:loading wire:target="update_user" type="button"
                                    class="btn btn-primary btn-block" disabled>
                                    <span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...
                                </button>
                                <button wire:loading.remove wire:target="update_user" type="submit"
                                    class="btn btn-primary btn-block">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button wire:loading wire:target="clode_modal" type="button" class="btn btn-danger" disabled>
                    <span class="spinner-border spinner-border-sm"></span>&nbsp;Removing...
                </button>
                <button wire:loading.remove wire:target="clode_modal" type="button" class="btn btn-danger"
                    wire:click="clode_modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
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
</script>