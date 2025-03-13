<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Sample Estimate Sheet</title>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		</style>
	</head>

	<body>
        @php
            $projectTotal = 0;
        @endphp
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
                    <td class="title">
                        <img
                            src="img/logo.png"
                            style="width: 100%; max-width: 300px; border-radius: 0.375rem;"
							alt="company logo"
                        />
                    </td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td style="text-align: right">
                        Job #{{$job->number}}<br />
                        Created: {{date_format($job->created_at, 'M d, Y')}}<br />
                    </td>
				</tr>

				<tr class="information">
                    <td>
                        ABC Enterprises<br />
                        123 Main St<br />
                        Laravel City<br />
                        MO
                    </td>

                    <td></td>
                    <td></td>
                    <td></td>

                    <td style="text-align: right; padding: 14px">
                        Site Address:<br />
                        {{$job->address}}<br />
                        {{$job->city}}<br />
                        {{$job->state->abbr }}
                    </td>
				</tr>

                @foreach($scopes as $scope)
                @php
                    $scopeTotal = 0;
                @endphp
                    <tr class="heading">
                        <td>@if($scope->name) {{$scope->name}} @endif</td>
                        <td style="text-align: right;" colspan="4">@if($scope->area)Area: {{$scope->area}} sf @endif</td>
                    </tr>
                    @foreach($scope->lines as $line)
                    <tr style="font-size: 14px;">
                        <td>{{$line->description}}</td>
                        <td>{{$line->unit_of_measurement->UOM}}</td>
                        <td>${{number_format($line->price, 2)}}</td>
                        <td>{{number_format($line->quantity, 2)}}</td>
                        <td style="text-align: right;">${{number_format($line->price * $line->quantity, 2)}}</td>
                    </tr>
                    @php
                        $scopeTotal += $line->price*$line->quantity;
                    @endphp
                    @endforeach
                    <tr>
                        <td style="font-weight: bold;">Scope Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold; text-align: right;">${{number_format($scopeTotal, 2)}}</td>
                    </tr>
                    @php
                        $projectTotal += $scopeTotal;
                    @endphp
                @endforeach

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right; font-weight: bold;">PROJECT TOTAL:</td>
                    <td style="text-align: right; font-weight: bold">${{number_format($projectTotal, 2)}}</td>
                </tr>
			</table>
		</div>
	</body>
</html>