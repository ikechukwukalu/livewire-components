@if($edit)
<tr>
    <form wire:submit.prevent='update_user'>
        @csrf
        <input type="hidden" wire:model="user_id" />
        <td>
            <input class="form-control @error('name') is-invalid @enderror" type="text" wire:model="name" />
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <input class="form-control @error('email') is-invalid @enderror" type="text" wire:model="email" />
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <input class="form-control @error('phone') is-invalid @enderror" type="tel" wire:model="phone" />
            @error('phone') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <select class="form-control @error('gender') is-invalid @enderror" type="text" wire:model="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            @error('gender') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <input class="form-control @error('country') is-invalid @enderror" type="text" wire:model="country" />
            @error('country') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <input class="form-control @error('state') is-invalid @enderror" type="text" wire:model="state" />
            @error('state') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <input class="form-control @error('city') is-invalid @enderror" type="text" wire:model="city" />
            @error('city') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <textarea rows="3" class="form-control @error('address') is-invalid @enderror"
                wire:model="address"></textarea>
            @error('address') <span class="error">{{ $message }}</span> @enderror
        </td>
        <td>
            <div class="btn-group">
                <button wire:loading wire:target="update_user" type="button" class="btn btn-primary" disabled><span
                        class="spinner-border spinner-border-sm"></span></button>
                <button wire:loading.remove wire:target="update_user" type="submit"
                    class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-light" wire:click="$toggle('edit')">Cancel</button>
            </div>
        </td>
    </form>
</tr>
@else
<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->phone }}</td>
    <td>{{ $user->gender }}</td>
    <td>{{ $user->country }}</td>
    <td>{{ $user->state }}</td>
    <td>{{ $user->city }}</td>
    <td>{{ $user->address }}</td>
    <td>
        <div class="dropdown">
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">Click Me</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0)" wire:click="$toggle('edit')">Edit</a>
                <a class="dropdown-item text-danger" href="javascript:void(0)" onClick="delete_user('{{ $user->id }}')">Delete</a>
            </div>
        </div>
    </td>
</tr>
@endif