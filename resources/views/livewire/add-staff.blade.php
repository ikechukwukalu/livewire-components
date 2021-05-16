<div>
    <div class="w-100 mt-3">
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{ session('success') }}
        </div>
        @endif
        @if (session()->has('fail'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Fail!</strong> {{ session('fail') }}
        </div>
        @endif
    </div>
    <form class="form-inline mt-2" wire:submit.prevent="addStaff">
        <input type="text" class="form-control pr-2 @error('name') is-invalid @enderror" wire:model="name"
            placeholder="Add Staff" />&nbsp;
        <button type="submit" class="btn btn-success" wire:target="addStaff" wire.loading.attr="disabled">
            <span wire:loading wire:target="addStaff">
                <span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...
            </span>
            <span wire:loading.remove wire:target="addStaff">Add Staff</span>
        </button>
    </form>
    <div class="w-100 mt-1">
        @error('name') <span class="error">{{ $message }}</span> @enderror
    </div>
</div>