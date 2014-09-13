@extends('layout.main')

@section('content')
	<h2>Free Online Tool For Stock Market Buy/Selling Prediction: </h2>
	 	<div id="index_out_wrap" style="margin: 0 0 20px 0;">
			<div style="float:left;overflow:hidden;width:95%;overflow-x:auto; border:1px solid #999;">
				<div id="last_predict">
								 @include('stockdeals.lastpredictions')
				</div>
			</div>
		</div>
						{{-- @include('advertisement.add_sqr') --}}
						 @include('advertisement.facebook')
	
					<div id="popular_predict">
						 @include('stockdeals.popular_predictions')
					</div>
 
						 @include('advertisement.add_sqr')

@stop