<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="form-group w-100">
            <label wire:loading wire:target="imap_email_body">Email body is loading...</label>
            <label wire:loading wire:target="search_for_emails">Searching for more emails...</label>
            <label wire:loading.remove wire:target="imap_email_body, search_for_emails"
                wire:click="imap_email_body">Search:</label>
            <div class="input-group mb-3 w-100">
                <input type="text" name="search" class="form-control" id="search-email"
                    placeholder="Search email subject and body..." wire:model.defer="text" autocomplete="off" />
                <input type="hidden" id="search-scroll" value="{{ $scroll }}">
                <div class="input-group-append">
                    @if ($display == 'none')
                    <button class="btn btn-primary" wire:loading wire:target="show_dropdown" type="button" disabled>
                        <span class="spinner-border spinner-border-sm"></span>
                    </button>
                    <button class="btn btn-primary" wire:loading.remove wire:target="show_dropdown"
                        wire:click="show_dropdown" wire:offline.attr="disabled" type="button">
                        <span>Search</span>
                    </button>
                    @else
                    <button class="btn btn-danger" wire:loading wire:target="close_display" type="button" disabled>
                        <span class="spinner-border spinner-border-sm"></span>
                    </button>
                    <button class="btn btn-danger" wire:loading.remove wire:target="close_display"
                        wire:click="close_display" wire:offline.attr="disabled" type="button">
                        <span>Reset</span>
                    </button>
                    @endif
                </div>
            </div>
            <div class="dropdown-search">
                <div id="search-dropdown-menu" class="dropdown-search-menu shadow-sm w-100 px-3 pt-3"
                    style="display: {{ $display }}">
                    @foreach ($results as $result)
                    <a class="dropdown-item text-center pt-1" href="#"
                        wire:click.prevent="imap_email_body('{{ $result['uid'] }}')">
                        {{ $result['getSubject'] }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <a class="dropdown-item text-center">
    <span class="text-danger">No result</span>
</a>
<a wire:loading wire:target="search_for_emails" class="dropdown-item text-center">
    <span class="spinner-border spinner-border-sm"></span>
</a> -->
<script>
document.querySelector('.dropdown-search-menu').addEventListener("scroll", (e) => {
    var val = document.getElementById('search-scroll').value;
    var element = e.target;
    if ((parseFloat(element.scrollTop) + parseFloat(element.offsetHeight)) >= element.scrollHeight) {
        if (val == 2)
            Livewire.emit('searchEmailInfinityScroll', val);
    }
});
document.addEventListener("DOMContentLoaded", () => {
    Livewire.on("searchEmailInfinityScroll", (text) => {
        @this.search_for_emails(text);
    });
});
</script>