@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="{{ asset('images/upemor.png') }}" alt="{{ config('app.name') }}" style="height: 75px;">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
