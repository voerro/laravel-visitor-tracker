@extends('visitstats::layout')

@section('visitortracker_content')
<div class="row">
	<div class="col-md-12">
		<h5>{{ $visitortrackerSubtitle }}</h5>

		<table class="table table-sm table-striped fs-1">
			<thead>
				<th>URL</th>
				<th>Unique Visitors</th>
				<th>Visits</th>
				<th>Last Visit</th>
			</thead>

			<tbody>
				@foreach ($visits as $visit)
					<tr>
						<td>
							{{ $visit->url }}
						</td>
							
						<td>
							{{ $visit->visitors_count }}
						</td>

						<td>
							{{ $visit->visits_count }}
						</td>

						<td>
							{{ \Carbon\Carbon::parse($visit->created_at)->format($datetimeFormat) }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		{{ $visits->links() }}
	</div>
</div>
@endsection