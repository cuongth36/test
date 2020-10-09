<ul class="menu-shop-style">
    @if ($menu->multiChildMenu->count())
        @foreach ($menu->multiChildMenu as $item)
            <li class="style-item"><a class="afont200" href="{{$item->link}}">{{$item->title}}</a></li>
            </li>
                    @if ($item->multiChildMenu->count())
                        @include('pages/menu/child-menu-mobile' , ['menu' => $item])
                    @endif
        @endforeach
    @endif
</ul>

