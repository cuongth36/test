<ul class="st2-submenu-item-ul">
     @if ($menu->multiChildMenu->count())
        @foreach ($menu->multiChildMenu as $item)
            <li class="st2-submenu-item-li">
            <a class="st2-submenu-item-link" href="{{$item->link}}">{{$item->title}}</a>
            </li>
                    @if ($item->multiChildMenu->count())
                        @include('pages/menu/child-menu' , ['menu' => $item])
                    @endif
        @endforeach
    @endif
</ul>