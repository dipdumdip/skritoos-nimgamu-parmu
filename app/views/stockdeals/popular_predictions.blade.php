	@if($populars)
			{? $i=1; ?}
	@foreach ($populars as $candle)
			<each>
						<wrap_no> {{ $i }}</wrap_no>
						<name><a href="{{ URL::route('home-estimate', array($candle->company_symbol, url_maker($candle->company_name))) }}">{{ $candle->company_name}} </a></name>
				</each>
	  {? $i++; ?}
	@endforeach		
@endif		