@foreach ($tickers as $ticker)
    <span class="stockbox">
      <a href="http://finance.yahoo.com/q?s={{ $ticker['symbol'] }}"> {{ $ticker['symbol'] }} </a>
              {{ $ticker['curr_rate'] }}
      <span style="{{ $ticker['font_color'] }}">
         {{ $ticker['direction'] }}  {{ $ticker['chng_rate'] }}
      </span>
    </span> 
@endforeach