<div class="row justify-content-center" wire:sortable="departmentOrder" wire:sortable-group="staffOrder"
    style="display: flex">
    <div class="col-md-12">
        @livewire('add-department')
    </div>
    @foreach ($departments as $department)
    <div class="col-md-6 p-2" wire:key="department-{{ $department->id }}" wire:sortable.item="{{ $department->id }}">
        <div class="border p-2">
            <div class="row" wire:sortable.handle>
                <div class="col-md-6">
                    <h4><span class="badge badge-secondary p-2">{{ $department->name }}</span></h4>
                </div>
                <div class="col-md-6">
                    <button class="float-right btn btn-danger btn-sm"
                        wire:click="removeDepartment({{ $department->id }})" wire:target="removeDepartment({{ $department->id }})" wire.loading.attr="disabled">
                        <span wire:loading.remove wire:target="removeDepartment({{ $department->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-trash" viewBox="0 0 16 16">
                                <path
                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd"
                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="removeDepartment({{ $department->id }})">
                            <span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...
                        </span>
                    </button>
                </div>
            </div>

            <ul class="list-group" wire:sortable-group.item-group="{{ $department->id }}">
                @forelse ($department->staffs()->orderBy('position', 'ASC')->get() as $staff)
                <li class="list-group-item" wire:key="staff-{{ $staff->id }}"
                    wire:sortable-group.item="{{ $staff->id }}">
                    <b>{{ $staff->position }}.</b>
                    {{ $staff->name }}
                    <button class="float-right btn btn-light btn-sm" wire:click="removeStaff({{ $staff->id }})" wire:target="removeStaff({{ $staff->id }})" wire.loading.attr="disabled">
                        <span wire:loading.remove wire:target="removeStaff({{ $staff->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-trash" viewBox="0 0 16 16">
                                <path
                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd"
                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="removeStaff({{ $staff->id }})">
                            <span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...
                        </span>
                    </button>
                </li>
                @empty
                <li class="list-group-item">No staff found</li>
                @endforelse
            </ul>

            @livewire('add-staff', ['department_id' => $department->id], key('staff-' . $department->id))
        </div>
    </div>
    @endforeach
</div>