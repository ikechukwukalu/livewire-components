<div id="search-email-root" class="row justify-content-center">
    <div class="col-md-10">
        <div class="w-100">
            @if (session()->has('info'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Notice!</strong> {{ session('info') }}
            </div>
            @endif
        </div>
        <div class="form-group w-100">
            <label wire:loading wire:target="imap_email_body">Email body is loading...</label>
            <label wire:loading wire:target="search_for_emails, no_more_emails">Searching for more emails...</label>
            <label wire:loading.remove wire:target="imap_email_body, search_for_emails, no_more_emails"
                wire:click="imap_email_body">Search:</label>
            <div class="input-group w-100">
                <input type="text" name="search" class="form-control" id="search-email"
                    placeholder="Search email subject and body..." wire:model.defer="text" autocomplete="off" />
                <input type="hidden" id="search-scroll" value="{{ $scroll }}" />
                <div class="input-group-append">
                    @if ($display == 'none')
                    <button class="btn btn-primary" wire:loading.attr="disabled" wire:click="show_dropdown"
                        wire:offline.attr="disabled" type="button">
                        <span wire:loading wire:target="show_dropdown" class="spinner-border spinner-border-sm"></span>
                        <span wire:loading.remove wire:target="show_dropdown">Search</span>
                    </button>
                    @else
                    <button class="btn btn-danger" wire:loading.attr="disabled" wire:click="close_display"
                        wire:offline.attr="disabled" type="button">
                        <span wire:loading wire:target="close_display" class="spinner-border spinner-border-sm"></span>
                        <span wire:loading.remove wire:target="close_display">Reset</span>
                    </button>
                    @endif
                </div>
            </div>
            <div class="dropdown-search">
                <div id="dropdown-backdrop" wire:loading.class="mail-list-loading custom-show"
                    wire:target="search_for_emails, imap_email_body, no_more_emails">
                </div>
                <div id="search-dropdown-menu" class="dropdown-search-menu shadow-sm w-100 px-3 pt-3"
                    style="display: {{ $display }}">
                    @forelse ($results as $result)
                    @if($loop->first)
                    <a class="dropdown-item text-center" style="display: none"></a>
                    <a class="dropdown-item text-center pt-1"
                        wire:click.prevent="imap_email_body('{{ trim($result['uid']) }}')">
                        {{ trim($result['getSubject']) }}
                    </a>
                    @else
                    <a class="dropdown-item text-center pt-1"
                        wire:click.prevent="imap_email_body('{{ trim($result['uid']) }}')">
                        {{ trim($result['getSubject']) }}
                    </a>
                    @endif
                    @empty
                    <a class="dropdown-item text-center pt-1" href="javascript:void(0)">
                        No matching emails
                    </a>
                    @endforelse
                </div>
            </div>
        </div>
        <script>
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.on("searchEmailInfinityScroll", (text) => {
                @this.search_for_emails(text); //This is the conventional code.
                // window.livewire.find(document.getElementById('search-email-root').getAttribute('wire:id')).search_for_emails(text); // This is a dirty hack
            });
            Livewire.on("NoMoreEmails", () => {
                @this.no_more_emails(); //This is the conventional code.
                // window.livewire.find(document.getElementById('search-email-root').getAttribute('wire:id')).no_more_emails(); // This is a dirty hack
            });
        });
        </script>
    </div>
</div>