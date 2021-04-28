<div>
    <button wire:loading.attr="disabled" wire:target="imap_email_body('{{ $email['uid'] }}')"
        wire:click="imap_email_body('{{ $email['uid'] }}')" class="btn btn-primary" type="button"
        wire:offline.attr="disabled">
        <span wire:loading.remove wire:target="imap_email_body('{{ $email['uid'] }}')">Read</span>
        <span wire:loading wire:target="imap_email_body('{{ $email['uid'] }}')">Loading...</span>
    </button>
</div>