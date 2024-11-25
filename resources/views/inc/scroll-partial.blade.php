@php
    $initialDate = date('Y-m-d H:i:s');    
@endphp
@foreach ($data as $item)
    @if($item->created_at != $initialDate)
        @php
            $initialDate = $item->created_at;
        @endphp
        <div class="date m-5">{{$item->created_at}}</div>
    @endif
    <div class="m-5">
        {{ $item->quote ?? '' }}{{$item->id}}
    </div>
@endforeach