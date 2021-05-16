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
    <form class="form-inline" wire:submit.prevent="addDepartment">
        <input type="text" class="form-control pr-2 @error('name') is-invalid @enderror" wire:model="name"
            placeholder="Add department" />&nbsp;
        <button type="submit" class="btn btn-primary" wire:target="addDepartment" wire.loading.attr="disabled">
            <span wire:loading wire:target="addDepartment">
                <span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...
            </span>
            <span wire:loading.remove wire:target="addDepartment">Add Department</span>
        </button>
    </form>
    <div class="w-100 mt-1">
        @error('name') <span class="error">{{ $message }}</span> @enderror
    </div>
</div>