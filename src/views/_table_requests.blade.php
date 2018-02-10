<table class="table table-sm table-striped fs-1">
    <thead>
        <th>Request</th>
        <th>Referrer</th>
        <th>Visitor</th>
    </thead>

    <tbody>
        @foreach ($visits as $visit)
            <tr>
                <td>
                    {{ $visit->created_at_timezoned }}
                    
                    <br>

                    {{ $visit->is_ajax ? 'AJAX' : '' }}
                    
                    @if ($visit->is_login_attempt)
                        <img class="visitortracker-icon"
                            src="{{ asset('/vendor/visitortracker/icons/login_attempt.png') }}"
                            title="Login attempt">
                    @endif

                    {{ $visit->method }} 
                    <a href="{{ $visit->url }}" target="_blank">{{ $visit->url }}</a>
                </td>

                <td>
                    {!!
                        $visit->referer
                        ? '<a href="' . $visit->referer . '" target="_blank">' . $visit->referer . '</a>'
                        : '-'
                    !!}
                </td>

                <td>@include('visitstats::_visitor')</td>
            </tr>
        @endforeach
    </tbody>
</table>