@extends('layout.main')

@section('content')
<div id="result_out_wrap">
	<div id="company_logo">
			<div><img height="40" width="100" src="{{ $meta_data['fb_image'] }}" /></div>
		<div><h2>Result Found For {{ $company_name }}</h2></div>
	</div>
	<div id="Record_getting">
		<div style="float:left;overflow:hidden;width:100%;overflow-x:auto;">
			@include('estimations.company_profile')
		</div>
	</div>
	<div id="predict_getting">
			@include('estimations.company_prediction')
	</div>
	<div id="graph_getting_parent">
		<iframe id="graph_getting" class="hidden" src=""></iframe> 
		<script>
				PLOT_the_Graph ( '{{ $company_symbol }}', '' );
		</script>
	</div>
				
			@if($compan_details)
			<div id="company_description">
				<div class="create_mid_buttton">Company Details:</div>
					<p>{{ $compan_details }}</p>
			</div>
			@endif
		<div style="float:left;overflow:hidden;width:100%;overflow-x:auto;">
			<div id="last_predict">
							 @include('stockdeals.lastpredictions')
			</div>
		</div>
</div>

@stop