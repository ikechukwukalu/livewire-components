<div class="card m-3 shadow-sm" wire:init="imap_emails">
    <div class="card-header">
        <h3>Emails from:&nbsp;<span class="text-info" id="email-address">{{ $email }}</span></h3>
    </div>
    <div class="card-body">
        <livewire:search-email :results="[]" :email="$email" :root="$root" />
        <ul class="list-group mail-list w-100 h-100" wire:loading.class="mail-list-loading"
            wire:target="imap_emails, gotoPage, previousPage, nextPage">
            @forelse($webmails as $webmail)
            <li class="list-group-item html-list">
                <span class="float-left">{{ $webmail['getSubject'] }}</span>
                <span class="float-right">
                    <livewire:email-body :email="$webmail" :key="$webmail['uid']" :root="$root" />
                </span>
            </li>
            @empty
            <li class="html-list" style="list-style: none">
                <p align="center" class="text-danger">{{ $load_state }}</p>
            </li>
            @endforelse
        </ul>
    </div>
    <div class="card-footer">
        <div class="row justify-content-center m-1">
            {!! $paginators !!}
        </div>
    </div>
</div>

<script>
function makeIframe(html) {
    $('#iframe1').contents().find('html').html(html);
}
var decodeEntities = (function() {
    // this prevents any overhead from creating the object each time
    var element = document.createElement('div');

    function decodeHTMLEntities(str) {
        if (str && typeof str === 'string') {
            // strip script/html tags
            str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
            str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
            element.innerHTML = str;
            str = element.textContent;
            element.textContent = '';
        }

        return str;
    }

    return decodeHTMLEntities;
})();
document.addEventListener("DOMContentLoaded", () => {
    Livewire.on('emailBody', body => {
        document.getElementById('email-subject').innerHTML = body['getSubject'];
        makeIframe(decodeEntities(body['getHTMLBody']));
    });
});
</script>