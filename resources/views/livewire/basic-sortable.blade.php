<div class="row justify-content-center">
    <div class="col-md-12">
        <ul class="list-group" wire:sortable="updatePoliticalPosition">
            @forelse ($politicians as $politician)
            <li class="list-group-item" wire:sortable.item="{{ $politician->id }}" wire:key="politician-{{ $politician->id }}">
                <span wire:sortable.handle>
                    <span class="badge badge-secondary p-2">POSITION:</span>&nbsp;{{ $politician->political_position_id }},&nbsp;
                    <b>NAME:</b>&nbsp;{{ $politician->name }},&nbsp;
                    <b>APPOINTMENT:</b>&nbsp;{{ $politician->position }}
                </span>
            </li>
            @empty
                <li class="list-group-item">No politician found</li>
            @endforelse
        </ul>
    </div>
</div>