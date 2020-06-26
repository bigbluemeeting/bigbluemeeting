<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="{{Arr::get($fields, 'title')}}">

            <a class="btn btn-link text-info" data-toggle="collapse" href="#{{Arr::get($fields, 'id')}}" role="button" aria-expanded="false" aria-controls="{{Arr::get($fields, 'id')}}">
{{--            <a class="btn btn-primary" data-toggle="collapse" href="#{{Arr::get($fields, 'id')}}" role="button" aria-expanded="false" aria-controls="{{Arr::get($fields, 'id')}}">--}}
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
{{--<div class="accordion" id="accordionExample">--}}
{{--    <div class="{{ Arr::get($fields, 'section_class', config('app_settings.section_class', 'card')) }} section-{{ Str::slug($fields['title']) }}">--}}
{{--        <button  type="button" class="{{ Arr::get($fields, 'section_heading_class', config('app_settings.section_heading_class', 'card-header')) }} btn btn-link text-danger text-dark" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
{{--            <i class="{{ Arr::get($fields, 'icon', 'glyphicon glyphicon-flash') }}"></i>--}}
{{--            {{ $fields['title'] }}--}}
{{--        </button>--}}


{{--        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">--}}
{{--            <div class="card-body">--}}
{{--               --}}
{{--            </div>--}}

{{--        </div>--}}


{{--    </div>--}}
{{--</div>--}}

<!-- end card for {{ $fields['title'] }} -->
