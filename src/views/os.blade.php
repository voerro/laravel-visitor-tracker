@extends('visitstats::layout')

@section('visitortracker_content')
<div class="row">
	<div class="col-md-12">
		<h5>{{ $visitortrackerSubtitle }}</h5>

		<table class="table table-sm table-striped fs-1">
			<thead>
				<th>OS</th>
				<th>Unique Visitors</th>
				<th>Visits</th>
			</thead>

			<tbody>
				@foreach ($visits as $visit)
					<tr>
						<td>
							@if ($visit->os_family)
                                <img class="visitortracker-icon"
                                    src="{{ asset('/vendor/visitortracker/icons/os/'.$visit->os_family.'.png') }}"
                                    title="{{ $visit->os_family }}"
                                    alt="{{ $visit->os_family }}">
                            @else
                                <span>Unknown</span>
                            @endif
						</td>
							
						<td>
							{{ $visit->visitors_count }}
						</td>

						<td>
							{{ $visit->visits_count }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		{{ $visits->links() }}
	</div>
</div>
@endsection