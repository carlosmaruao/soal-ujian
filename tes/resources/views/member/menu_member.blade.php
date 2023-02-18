{{-- @include('member.menu_member')   --}}
<ul class="list-group list-group-flush list-menu-member">
    @can('member-area')
    @if (Auth::user()->status > 1)
        <a href="{{ route('member.data.sukses') }}">
            <li class="list-group-item border mb-3 rounded{{ request()->is('member/page/data-success') ? ' list-group-item-info font-weight-bold' : ''}}">
                Hasil Minat Bakat
            </li>
        </a>
    @endif 
    <!-- <a href="{{ route('member.view.question') }}">
        <li class="list-group-item border mb-3 rounded{{ request()->is('member/page/q-n-a') ? ' list-group-item-info font-weight-bold' : ''}}">
            Q & A
        </li>
    </a> -->
    @endcan

    <a href="{{ route('password.edit') }}">
        <li class="list-group-item border mb-3 rounded{{ request()->is('account/password') ? ' list-group-item-info font-weight-bold' : ''}}">
            Ganti Password
        </li>
    </a>
</ul>