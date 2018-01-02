@extends($visitortrackerLayout)

@section($visitortrackerSectionContent)
<link rel="stylesheet"
    property="stylesheet"
    href="/vendor/visitortracker/css/visitortracker.css">

<h1>Statistics</h1>

<div class="row">
	<div class="col-md-12">
		<h5>Summary</h5>

		<table class="table table-sm table-striped fs-1">
			<thead>
				<th>Period</th>
				<th>Unique Visitors</th>
				<th>Visits</th>
			</thead>

			<tbody>
                <tr>
                    <td>24 hours</td>

                    <td>{{ $unique24h }}</td>

                    <td>{{ $visits24h }}</td>
                </tr>

                <tr>
                    <td>1 week</td>

                    <td>{{ $unique1w }}</td>

                    <td>{{ $visits1w }}</td>
                </tr>

                <tr>
                    <td>1 month</td>

                    <td>{{ $unique1m }}</td>

                    <td>{{ $visits1m }}</td>
                </tr>

                <tr>
                    <td>1 year</td>

                    <td>{{ $unique1y }}</td>

                    <td>{{ $visits1y }}</td>
                </tr>

                <tr>
                    <td>All time</td>

                    <td>{{ $uniqueTotal }}</td>

                    <td>{{ $visitsTotal }}</td>
                </tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h5>Last 10 Requests</h5>

		<table class="table table-sm table-striped fs-1">
			<thead>
				<th>Datetime</th>
				<th>IP</th>
				<th>Request</th>
				<th>Agent</th>
				<th>Language</th>
				<th>Location</th>
			</thead>

			<tbody>
				@foreach ($lastVisits as $visit)
					<tr>
                        <td>{{ \Carbon\Carbon::parse($visit->created_at)->format($datetimeFormat) }}</td>

						<td>
                            @if ($visit->user_id)
                                <img class="visitortracker-icon"
                                    src="{{ asset('/vendor/visitortracker/icons/user.png') }}"
                                    title="Authenticated user: {{ $visit->user_email }}">
                            @endif

                            {{ $visit->ip }}
                        </td>

						<td>
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
                        </td>

						<td>
                            {{ $visit->browser_language ?: '-' }}
                        </td>

						<td>
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

                            {{ $visit->city ?: '' }}

                            <br>

                            @if ($visit->lat && $visit->long)
                                {{ $visit->lat }}, {{ $visit->long }}
                            @endif
                        </td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection