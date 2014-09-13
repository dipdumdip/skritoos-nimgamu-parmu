@if($stock_market)  {{--  -if it retuns a record then proceed --}}
	<h4>Company Profile: <b>{{ ucwords($company_name)  }} </b></h4>
		<header>
				<wrap>Date:</wrap>
				<wrap>Open</wrap>
				<wrap>High</wrap>
				<wrap>Low</wrap>
				<wrap>Close</wrap>
				<wrap>Volume</wrap>
				<wrap>Adj Close</wrap>
		</header>
		@foreach ($stock_records as $record) {{--  --- record processing loop starts here --}}
				<each>
					<wrap>{{ date("dMy", strtotime($record['Date']))  }}</wrap>
					<wrap>{{ $record['Open']  }}</wrap>
					<wrap>{{  $record['High']  }}</wrap>
					<wrap>{{ $record['Low']  }}</wrap>
					<wrap>{{ $record['Close']  }}</wrap>
					<wrap>{{ $record['Volume']  }}</wrap>
					<wrap>{{ $record['Adj Close']  }}</wrap>
				</each>

		@endforeach	 {{--  --ends of loop --}}
							
@endif	  {{--  -- stock data cheks ends	--}}