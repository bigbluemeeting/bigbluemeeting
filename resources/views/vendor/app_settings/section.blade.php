<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="{{Arr::get($fields, 'title')}}">

            <a class="btn btn-link text-info" data-toggle="collapse" href="#{{Arr::get($fields, 'id')}}" role="button" aria-expanded="false" aria-controls="{{Arr::get($fields, 'id')}}">
                <i class="{{ Arr::get($fields, 'icon', 'glyphicon glyphicon-flash') }}"></i>
                {{ $fields['title'] }}
            </a>

        </div>
        <div id="{{Arr::get($fields, 'id')}}" class="collapse" >
            <div class="card-body bg-light">
                @if( $desc = Arr::get($fields, 'descriptions') )
                    <div class="pb-0 {{ config('app_settings.section_body_class', Arr::get($fields, 'section_body_class', 'card-body')) }}">
                        <p class="text-muted mb-0 ">{{ $desc }}</p>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </div>
    </div>
</div>


<!-- end card for {{ $fields['title'] }} -->
