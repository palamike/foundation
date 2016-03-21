<ol class="breadcrumb">
    @foreach($items as $label => $item)
        <li @if($loop->last) class="active" @endif><a href="{{ $item == '#' ? '#' : url($item) }}">@if($loop->first)<i class="fa fa-{{$icon}}"></i> @endif{{ trans($label) }}</a></li>
    @endforeach
</ol>