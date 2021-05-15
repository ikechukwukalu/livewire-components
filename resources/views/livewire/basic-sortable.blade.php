<div class="row justify-content-center">
    <div class="col-md-12">
        <ul class="list-group" wire:sortable="updatePoliticalPosition">
            @forelse ($politicians as $politician)
            <li class="list-group-item" wire:sortable.item="{{ $politician->id }}" wire:key="politician-{{ $politician->id }}">
                <span wire:sortable.handle>
                    <b>NAME:</b>&nbsp;{{ $politician->name }},&nbsp;
                    <b>APPOINTMENT:</b>&nbsp;{{ $politician->position }},&nbsp;
                    <b>POSITION:</b>&nbsp;{{ $politician->political_position_id }}
                </span>
            </li>
            @empty
                <li class="list-group-item">No politician found</li>
            @endforelse
        </ul>
    </div>
</div>