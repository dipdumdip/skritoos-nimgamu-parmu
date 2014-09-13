 @if($stock_market) 	{{-- ----if it retuns a record then proceed --}} 
	<h4> Future Prediction for: <?php echo $company_name; ?></h4>
		<div style="margin:10px auto;float:none;width:50%;">
			<predict>Predicted Buy Level:</predict>
			<value>{{ $buy_level }}</value>
			<predict style="clear:both;" >Predicted Selling Level:</predict>
			<value>{{ $sell_level }} </value>
		</div>

@endif  {{-- -- stock data checks ends	--}} 
					
