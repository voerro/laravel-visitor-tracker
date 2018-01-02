<table class="table table-sm table-striped fs-1">
    <thead>
        <th>Request</th>
        <th>Visitor</th>
    </thead>

    <tbody>
        @foreach ($lastVisits as $visit)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($visit->created_at)->format($datetimeFormat) }}
                    
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
                    @if ($visit->user_id)
                        <img class="visitortracker-icon"
                            src="{{ asset('/vendor/visitortracker/icons/user.png') }}"
                            title="Authenticated user: {{ $visit->user_email }}">
                    @endif

                    {{ $visit->ip }}

                    <br>

                    @if ($visit->os_family)
                        <img class="visitortracker-icon"
                            src="{{ asset('/vendor/visitortracker/icons/os/'.$visit->os_family.'.png') }}"
                            title="{{ $visit->os }}"
                            alt="{{ $visit->os }}">
                    @else
                        <span>{{ $visit->os }}</span>
                    @endif

                    @if ($visit->browser_family)
                        <img class="visitortracker-icon"
                            src="{{ asset('/vendor/visitortracker/icons/browsers/'.$visit->browser_family.'.png') }}"
                            title="{{ $visit->browser }}"
                            alt="{{ $visit->browser }}">
                    @else
                        <span>{{ $visit->browser }}</span>
                    @endif

                    @if ($visit->is_mobile)
                        <img class="visitortracker-icon"
                            src="{{ asset('/vendor/visitortracker/icons/mobile.png') }}"
                            title="Mobile device">
                    @endif

                    @if ($visit->is_bot)
                        <img class="visitortracker-icon"
                            src="{{ asset('/vendor/visitortracker/icons/spider.png') }}"
                            title="{{ $visit->bot }}">
                    @endif

                    {{ $visit->browser_language ?: '-' }}

                    <br>

                    @if ($visit->country_code)
                        @if (file_exists('vendor/visitortracker/icons/flags/'.$visit->country_code.'.png'))
                            <img class="visitortracker-icon"
                                src="{{ asset('/vendor/visitortracker/icons/flags/'.$visit->country_code.'.png') }}"
                                title="{{ $visit->country }}">
                        @else
                            <img class="visitortracker-icon"
                                src="{{ asset('/vendor/visitortracker/icons/flags/unknown.png') }}"
                                title="{{ $visit->bot }}">
                        @endif
                    @endif

                    {{ $visit->city ?: '' }}{{ $visit->lat && $visit->long ? ',' : '' }}

                    @if ($visit->lat && $visit->long)
                        {{ $visit->lat }}, {{ $visit->long }}
                    @endif
                </td>

                <td>
                    
                </td>
            </tr>
        @endforeach
    </tbody>
</table>