@if ($items->count())
    <ul>
        @foreach($items as $item)
            <li>
                <a href="{{ route('blog.category', ['category' => $item->slug]) }}">{{ $item->name }}</a>
                @if ($item->children->count())
                    <ul>
                        @foreach($item->children as $child)
                            <li>
                                <a href="{{ route('blog.category', ['category' => $child->slug]) }}">
                                    {{ $child->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif
