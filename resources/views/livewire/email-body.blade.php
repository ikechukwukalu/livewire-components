<div>
    <button wire:loading wire:target="imap_email_body('{{ $email['uid'] }}')" class="btn btn-primary" type="button"
        disabled>Loading...</button>
    <button wire:loading.remove wire:target="imap_email_body('{{ $email['uid'] }}')"
        wire:click="imap_email_body('{{ $email['uid'] }}')" class="btn btn-primary" type="button" wire:offline.attr="disabled">Read</button>
</div>