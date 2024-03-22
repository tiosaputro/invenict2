<!DOCTYPE html>
<head>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" type="text/css"/>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<title> ICT REQUEST STATUS REPORT LIST ON {{date('d M Y')}}</title>
<style>
@media screen {
    .row {
        margin-left:-5px;
        margin-right:-5px;
    }
    .row::after {
        content: "";
        clear: both;
        display: table;
    }
    .invoice .contacts {
        margin-bottom: 20px
    }
    .invoice .invoice-to {
        text-align: left
    }
    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }
    .invoice .company-details {
        text-align: left;
    }
    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0;
		text-align: left
    }
    p {
        font-weight: bold;
    }
    .column {
        float: left;
        width: 50%;
        padding: 5px;
        margin-left:2px;
        margin-right:-10px;
    }
    /* #invoice{
        padding: 30px;
        size: A4;
        size: 7in 9.25in;
        margin: 27mm 16mm 27mm 16mm;
    } */
    #invoice{
        padding: 15px;
        size: A4;
        margin: 0;
    }
    .invoice table {
        width: 100%;
        border:solid 2px;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 1px;
    }

    .invoice table th {
        font-size: 16px;
        table-layout:fixed;
        overflow: hidden;
    }


    .invoice table tbody tr:last-child td {
        border:solid 2px;
        page-break-after: auto;
    }

    .invoice {
        font-size: 12px!important;
        overflow: hidden!important;
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        font-weight:bold;
        color: black;
        border-top: 1px solid;
        overflow: hidden;
        bottom:0;
    }

    .invoice>div:last-child {
        page-break-after: auto;
    }

    .invoice {
        background-color: #FFF;
        min-height: 680px;
        padding: 30px;
        size: A4;
        margin: 0;
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid'
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        border:solid 2px;
        overflow: hidden;
    }

    .invoice table th {
        overflow: hidden;
        font-weight: bold;
        font-size: 14px;
        table-layout:fixed;
        border:solid 2px;
        
    }

    .column {
        float: left;
        width: 50%;
        padding: 5px;
        margin-left:2px;
        margin-right:-10px;
    }
    .textareacss{
        resize: none; 
        border:solid 2px;
        font-weight: bold;
        overflow:hidden;
        margin: 1px;
        padding: 1px;
        min-width: 99%;
        min-height: 170px;
        outline: none;
    }
    .wrap {
        display: table;
        table-layout: fixed;
        padding:10px;
        width: 100%;
        height: 100%; /* need to set height for this to work in Chrome */
    }
    .cell-wrap {
        display: table-cell;
        table-layout: fixed;
        vertical-align: top;
        height: 100%;
    }
    .cell-wrap.left {
        width: 50%;
        padding-right: 5px;  
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
        table-layout: fixed;
        border:solid 2px;
        height: 100%;
        width: 100%;
        border:solid 2px;
    }
    table td {
        padding: 15px;
        height:20px;
        border:solid 2px;
		font-size:12px;
        text-align: center;
        
    }
    table th {
        border:solid 2px;
        font-weight:bold;
        text-align: center;
        padding: 5px;
        background-color:#807a6b;


    }
}
@media print {
    .invoice .contacts {
        margin-bottom: 20px
    }
    .invoice .invoice-to {
        text-align: left
    }
    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }
    p {
        font-weight: bold;
    }
    .row {
        margin-left:-5px;
        margin-right:-5px;
    }
    .row::after {
        clear: both;
        display: table;
    }
    .invoice .company-details {
        text-align: right
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }
    .column {
        float: left;
        width: 50%;
        padding: 5px;
        margin-left:2px;
        margin-right:-10px;
    }
    #invoice{
        padding: 15px;
        size: A4;
        margin: 0;
    }
    .csssolution{
        resize: none; 
        border:solid 2px;
        font-weight: bold;
        overflow:hidden;
        margin: 1px;
        padding: 1px;
        width: 470pt;
        min-height: 120px;
        text-align: left;
        outline: none;
    }
    .invoice table {
        width: 100%;
        border:solid 2px;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 1px;
    }

    .invoice table td,.invoice table th {
        padding: 15px;
        border: 2px solid;
    }

    .invoice table th {
        /* white-space: nowrap; */
        overflow: hidden;
    	font-weight: bold;
        font-size: 13px;

    }
    .invoice table td h3 {
        margin: 0;
        overflow: hidden;
        height:0.375pt;
        font-size: 12px;
        overflow: hidden;
    }

    .invoice table tbody tr:last-child td {
        border: 2px solid;
        page-break-after: auto;
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.5em;
        border: 2px solid;
    }

    .invoice table tfoot tr:last-child td {
        
        overflow: hidden;
        font-size: 1.4em;
        border: 2px solid;
        page-break-after: auto;
    }

    .invoice table tfoot tr td:first-child {
        border: 2px solid;
    }
    #printInvoice {
        display: none;
    }
    .invoice {
        font-size: 12px!important;
        overflow: hidden!important;
    } 
    .invoice footer {
        width: 100%;
        text-align: center;
        font-weight:bold;
        color: black;
       border-top: 1px solid;
        overflow: hidden;
        position:relative;
    }
    .invoice>div:last-child {
        /* page-break-before: always */
        page-break-after: auto;
    }
    .table {
        border-collapse: collapse;
        border:solid 2px;
        width: 199%;
        max-width: 100%;
    }
    .wrap {
        display: table;
        table-layout: fixed;
        padding:10px;
        width: 100%;
        height: 100% /* need to set height for this to work in Chrome */
    }
    .cell-wrap {
        display: table-cell;
        table-layout: fixed;
        vertical-align: top;
        height: 100%;
    }
    .cell-wrap.left {
        width: 50%;
        padding-right: 5px;  
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
        table-layout: fixed;
        border:solid 2px;
        height: 80%;
        width: 100%;
        border:solid 2px;
    }
    table td {
        padding:15px;
        height:20px;
        border:solid 2px;
        text-align: center;
    }
    table th {
        border:solid 2px;
        font-weight:bold;
        text-align: center;
        background-color:#807a6b;
        padding: 5px;
    }
    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 510pt;
        padding: 22pt;
        margin: 0;
    }
    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid;
    }
    .invoice main {
        padding-bottom: 50px
    }
    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        
        font-size: 1.4em;
       border-top: 1px solid;
        page-break-after: auto;
    }

    .invoice table tfoot tr td:first-child {
        border: none
    }
    .textareacss{
        resize: none; 
        border:solid 1.5pt;
        font-weight: bold;
        overflow:hidden;
        margin: 0.75pt;
        padding: 0.75pt;
        min-width:100pt;
        max-height: 100%;
        outline: none;
    }
}
</style>

<div id="invoice">
    <div class="col invoice-to">
        <div class="text-right">
            <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a>
                          <img src="{{asset('assets/layout/images/logo-emp.png')}}" width="200" data-holder-rendered="true" />
                        </a>
                    </div> 
					
				<div class="text-center">
						<span style="font-weight:bold;font-size:25px;">ICT REQUEST STATUS REPORT LIST ON {{date('d M Y')}}</span>
					</div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                  <div class="col invoice-to">
                    <p></p>
                      <table> 
                            <tr>
								<th>No Request</th>
								<th>No Detail</th>
								<th>Request Type</th>
								<th>Items</th>
								<th>Qty</th>
								<th>Remark</th>
								<th>Request Date</th>
								<th>Requestor</th>
								<th>User</th>
								<th>User Division</th>
								<th>ICT Personnel</th>
								<th>Status</th>
								<th>Reason</th>
                            </tr>
                            @if(sizeof($ict))
							@foreach($ict as $i)
								<tr>
									<td>{{$i->ireq_no}}</td>
									<td>{{$i->ireqd_id}}</td>
									<td>{{$i->ireq_type}}</td>
									<td>{{$i->kategori}}</td>
									<td>{{$i->ireq_qty}}</td>
									<td>{{$i->ireq_remark}}</td>
									<td>{{$i->ireq_date}}</td>
									<td>{{$i->ireq_requestor}}</td>
									<td>{{$i->ireq_user}}</td>
									<td>{{$i->div_name}}</td>
                                    <td>{{$i->ireq_assigned_to}}</td>
                                    <td>{{$i->ireq_status}}</td>
                                    <td>{{$i->ireq_assigned_to1_reason}}</td>
								</tr>	
							@endforeach
                            @else	
                            <tr>
                                <td colspan="12" style="font-weight:bold;font-size:16px;">Data not found...</td>
                            </tr>
                            @endif
                      </table>
                  </div>
                </div>

            </main>
            <!-- <footer>
                <strong>This PDF was created on a system and is valid without the signature and seal.</strong>
            </footer> -->
        </div>
        <!-- <div></div> -->
    </div>
</div>
 
<script>
     $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data) 
            {
                window.print();
                return true;
            }
        });
</script>