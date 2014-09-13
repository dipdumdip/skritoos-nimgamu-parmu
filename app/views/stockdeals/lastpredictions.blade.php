	@if($records)
				<header>
						<wrap_no>No:</wrap_no>
						<name>Name</name>
						<wrap>Buy @</wrap>
						<wrap>Sell @</wrap>
						<wrap>Date</wrap>
						<wrap>Time</wrap>
						<host>Area</host>
				</header>
			{? $i=1; ?}
	@foreach ($records as $candle)
		<each>
			<wrap_no>{{ $i }}</wrap_no>
			<name><a href="{{ URL::route('home-estimate', array($candle->company_symbol, url_maker($candle->company_name))) }}">{{ $candle->company_name }}</a></name>
			<wrap>{{$candle->buy_level }}</wrap>
			<wrap>{{ $candle->sell_level }}</wrap>
			<wrap>{{ date("d-MY", strtotime($candle->updated)) }} </wrap>
			<wrap>{{ date("H:i:s a", strtotime($candle->updated)) }} </wrap>
			<host>{{ $candle->host }}</host>
		</each>
	  {? $i++; ?}
	@endforeach		
@endif		