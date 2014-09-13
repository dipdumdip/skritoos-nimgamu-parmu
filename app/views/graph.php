@extends('layout.main')

@section('content')
	<h2>Free Online Tool For Stock Market Buy/Selling Prediction: </h2>
				<div id="index_out_wrap">
					<div id="last_predict">
						 @include('stockdeals.lastpredictions')
					</div>
				</div>
						 @include('advertisement.facebook')
	
					<div id="popular_predict">
						 @include('stockdeals.popular_predictions')
					</div>
 
						 @include('advertisement.add_sqr')

@stop