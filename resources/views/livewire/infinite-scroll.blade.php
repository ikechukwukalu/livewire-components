<div>
    <!-- The Livewire Component -->
    <input type="hidden" id="user-scroll" wire:model="no_user" />
</div>
    <!-- The HTML to be updated on scroll -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <ul class="list-group" id="users-list">
            @forelse ($users as $user)
            <li class="list-group-item">
                <b>NAME:</b>&nbsp;{!! isset($user->name) ? $user->name : $user['name'] !!}&nbsp;
                <b>EMAIL:</b>&nbsp;{!! isset($user->email) ? $user->email : $user['email'] !!}&nbsp;
                <b>PHONE:</b>&nbsp;{!! isset($user->phone) ? $user->phone : $user['phone'] !!}
            </li>
            @empty
            <li class="list-group-item">No users found</li>
            @endforelse
        </ul>
        <div class="w-100 p-3" id="loader" style="display: none">
            <p align="center"><span class="spinner-border spinner-border"></p>
        </div>
        <div id="no_users" class="p-3" style="display: none">
            <div class="p-3">
                <div class="w-100">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Notice!</strong> {{ $message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    window.addEventListener("scroll", (e) => {
        var val = document.getElementById('user-scroll').value;
        if ((parseFloat(window.pageYOffset) + parseFloat(window.innerHeight) + parseInt(24)) >= document
            .documentElement.scrollHeight) {
            if (val == 2)
                Livewire.emit('usersInfinityScroll', val);
            else
                Livewire.emit('noMoreUsers');
        }
    });
    Livewire.on("usersInfinityScroll", (num) => {
        document.getElementById('loader').style.display = 'block';
        @this.fetch_users(num);
    });
    Livewire.on("appendUsers", (ary) => {
        document.getElementById('loader').style.display = 'none';
        var ul = document.getElementById('users-list');
        ary = JSON.parse(JSON.stringify(ary));
        ary['users'].map((ele, inx) => {
            var b1 = document.createElement("b");
            b1.innerHTML = 'NAME: ';
            var b2 = document.createElement("b");
            b2.innerHTML = 'EMAIL: ';
            var b3 = document.createElement("b");
            b3.innerHTML = 'PHONE: ';

            var li = document.createElement("li");
            li.classList.add("list-group-item");
            li.appendChild(b1);
            li.innerHTML += ele.name + ' ';
            li.appendChild(b2);
            li.innerHTML += ele.email + ' ';
            li.appendChild(b3);
            li.innerHTML += ele.phone;

            ul.appendChild(li);
        });
    });
    Livewire.on("noMoreUsers", () => {
        document.getElementById('no_users').style.display = 'block';
    });
});
</script>