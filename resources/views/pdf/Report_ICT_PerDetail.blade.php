<!DOCTYPE html>
<head>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" type="text/css"/>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<title> ICT REQUEST REPORT  {{ date('d M Y') }}</title>
<style>
@media screen {
    .row {
        margin-left:-5px;
        margin-right:-5px;
        pad
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
        text-align: right
    }
    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
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
        padding: 20px;
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
        width: 550pt;
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
        border-top: 1px solid #3989c6;
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
        border-bottom: 1px solid #3989c6
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
        font-weight: 400;
        font-size: 16px;
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
        height: 80%;
        width: 100%;
        border:solid 2px;
    }
    table td {
        padding: 10px;
        height:15px;
        border:solid 2px;
        font-weight:bold;
        text-align: center;
        background-color:#7CB9E8;
    }
    table th {
        border:solid 2px;
        font-weight:bold;
        text-align: center;
        padding: 5px;

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
        padding: 20px;
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
        font-weight: 400;
        font-size: 16px;
        table-layout:fixed;

    }

    .invoice table td h3 {
        margin: 0;
        overflow: hidden;
        height:0.375pt;
        color: #3989c6;
        font-size: 16px;
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
        color: #3989c6;
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
        border-top: 1px solid #3989c6;
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
        padding: 10px;
        height:15px;
        border:solid 2px;
        font-weight:bold;
        text-align: center;
        background-color:#7CB9E8;
    }
    table th {
        border:solid 2px;
        font-weight:bold;
        text-align: center;
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
        border-bottom: 1px solid #3989c6
    }
    .invoice main {
        padding-bottom: 50px
    }
    .invoice table td,.invoice table th {
        padding: 15px;
        border-bottom: 1px solid #fff
    }
    .invoice table th {
        /* white-space: nowrap; */
        /* font-weight: 400; */
        overflow: hidden;
        font-size: 16px;
        table-layout:fixed;
    }
    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 1.4em;
        border-top: 1px solid #3989c6;
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
                          <img src="{{asset('assets/layout/images/logo_emp_new.png')}}" width="200" data-holder-rendered="true" />
                        </a>
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            <a>
                                <p>ICT SERVICE REQUEST </p>
                            </a>
                        </h2>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <p style="text-align:left;font-size:16px;">Please submit to Helpdesk Supervisor (27th Floor) Or  Call Hotline Number Extension 511
                            <span style="float:right;font-size:18px;">
                                No.request : {{$detail[0]->ireq_no}}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="row contacts" contenteditable="false">
                    <div class="col invoice-to">
                        <div class="address" style="font-weight:bold;font-size:16px;">I. Description Of Request/Problem Experienced</div>
                            <textarea class="textareacss" cols="100" rows="8" style="font-weight: bold" readonly> @php $no = 1 @endphp 
@foreach($detail as $d)  {{$no++}}.{{$d->ireq_remark}}
@endforeach
                                    </textarea>
                        </div>
                        <div class="col invoice-to">
                          <div class="address" style="font-weight:bold;font-size:16px;">Priority Level</div>
                            <textarea rows="8" cols="26" class="textareacss" readonly>{{$detail[0]->prio_level}}  </textarea>
                        </div>
                </div>
                @php 
                    $linkRequester = "http://localhost:8000/check-legality-qrcode-requester/{$link->link_id}"; 
                    $linkIctManager = "http://localhost:8000/check-legality-qrcode-ict-manager/{$link->link_id}"; 
                    $linkHigherLevel = "http://localhost:8000/check-legality-qrcode-higher-level/{$link->link_id}"; 
                @endphp
                <div class="wrap">
                    <div class="cell-wrap left">
                        <table class="left">
                          <p style="font-size:16px;"> II. Requester / Reported By : </p>
                            <tr>
                                <th>{{$detail[0]->ireq_requestor}}</th>
                                <th>{{$detail[0]->div_name}}</th>
                                <th rowspan="2" style="font-size:10pt;">{!! QrCode::errorCorrection('M')->size(80)->generate($linkRequester); !!}<br>{{$detail[0]->date_request}}</th>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>Divison</td>
                            </tr>
                        </table>
                    </div>
                    <div class="cell-wrap right">
                        <table class="right">
                            <p style="font-size:16px;"> Approved By : (For new installation/software loan) </p>
                             @if ($detail[0]->cekstatus == 'NA1' || $detail[0]->cekstatus == 'A1' )
                                <tr>
                                    <th>{{$detail[0]->usr_fullname}}</th>
                                    <th>{{$detail[0]->div_name}} Manager</th>
                                  @if($detail[0]->status == "RA1")
                                    <th rowspan="2" style="font-size:10pt;">{!! QrCode::errorCorrection('M')->size(80)->generate($linkHigherLevel); !!}<br><strong>Rejected</strong> on {{$detail[0]->date_approver1}}</th>
                                  @elseif($detail[0]->status == "A1")
                                    <th rowspan="2" style="font-size:10pt;">{!! QrCode::errorCorrection('M')->size(80)->generate($linkHigherLevel); !!}<br><strong>Approved</strong> on {{$detail[0]->date_approver1}}</th>
                                  @elseif($detail[0]->status == "NA1")
                                    <th rowspan="2" style="font-size:12pt; font-weight:bold;">Waiting Approval</th>
                                  @endif

                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>Position</td>
                                </tr>
                             @else
                                <tr>
                                @if($detail[0]->cekstatus == 'P' )
                                    <th rowspan="2" style="font-weight:bold;">This request is not yet in higher level approval stage</th>
                                    @else
                                    <th rowspan="2" style="font-weight:bold;">This request does not require approval from higher level</th>
                                    @endif
                                </tr>
                                <tr>
                                    <!-- <td></td> -->
                                </tr>
                             @endif
                        </table>
                    </div>
                </div> <!-- wrap --> 
                <div class="row contacts">
                    <div class="col invoice-to">
                      <p style="font-size:16px;"> III. IT Use Only</p>
                         <table> <p style="font-size:16px;"> Approved By :   (Note : Sr. Manager approval needed for new equipment/software/tools)</p>
                             @if($detail[0]->cekstatus == 'NA2' || $detail[0]->cekstatus == 'A2' )
                                <tr>
                                    <th>Arifin Tahir</th>
                                    <th>ICT Manager</th>
                                    @if($detail[0]->status=='RA2')
                                    <th rowspan="2" style="font-size:10pt;">{!! QrCode::errorCorrection('M')->size(80)->generate($linkIctManager); !!} <br> <strong>Rejected</strong> On {{$detail[0]->date_approver2}}</th>
                                    @elseif($detail[0]->status=='A2')
                                    <th rowspan="2" style="font-size:10pt;">{!! QrCode::errorCorrection('M')->size(80)->generate($linkIctManager); !!} <br><strong>Approved</strong> On {{$detail[0]->date_approver2}}</th>
                                    @elseif($detail[0]->status=='NA2') 
                                    <th rowspan="2" style="font-size:10pt;"> Waiting Approval</th>
                                    @endif
                                    <th>{{$detail[0]->ireq_approver2_remark}}</th>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>Position</td>
                                    <td>Remarks(Including OE if required)</td>
                                </tr>
                             @else
                                <tr>
                                    @if($detail[0]->cekstatus == 'P' || $detail[0]->cekstatus == 'NA1' || $detail[0]->cekstatus == 'A1' )
                                    <th rowspan="2" style="font-weight:bold;">This request is not yet in ICT Manager approval stage</th>
                                    @else
                                    <th rowspan="2" style="font-weight:bold;">This request does not require approval from ICT Manager</th>
                                    @endif
                                </tr>
                                <tr>

                                </tr>
                             @endif
                         </table>
                    </div>
                </div>
                </div>
                <div class="row contacts">
                  <div class="col invoice-to">
                    <p style="font-size:16px;">IV. Request Review</p>
                        <table>
                          <tr> 
                            <th width="200px" >{{$detail[0]->ireq_verificator}}</th>
                            <th>{{$detail[0]->ireq_verificator_remark}}</th>
                            <th width="200px">{{$detail[0]->ireq_loc}}</th>
                          </tr>
                          <tr>
                            <td width="200px" >Received By</td>
                            <td>Remark</td>
                            <td width="200px">Problem Area</td>
                          </tr>
                        </table>
                  </div>
                </div>
                <div class="row contacts">
                  <div class="col invoice-to">
                    <p></p>
                      <table> 
                        @if($detail[0]->date_assigned_detail)
                        @foreach($detail as $det)
                            <tr>
                                <th width="90px" >{{$det->ireqd_id}}</th>
                                <!-- <th>{{$det->ireq_remark}}</th> -->
                                <th>{{$det->ireq_assigned_to_detail}}</th>
                                <th width="150px">{{$det->date_assigned_detail}}</th>
                                <th width="150px">{{$det->date_closing_detail}}</th>
                                <th>{{$det->assigned_remark_detail}}</th>
                                <th>{{$det->ireq_status}}</th>
                            </tr>
                            @endforeach
                            <tr>
                                <td width="90px">No Detail</td>
                                <!-- <td>Description </td> -->
                                <td>Assigned To</td>
                                <td width="150px">Date Assigned</td>
                                <td width="150px">Date Completed</td>
                                <td>Remark</td>
                                <td>Status</td>
                            </tr>
                        @else
                            <tr>
                                <th rowspan="2" style="font-weight:bold">This request is not yet in the assignment stage</th>
                            </tr>
                            <tr>

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