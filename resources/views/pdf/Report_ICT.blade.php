<!DOCTYPE html>
<head>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" type="text/css"/>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<title> ICT REQUEST  {{ date('Y-m-d') }}</title>
<style>
#invoice{
    padding: 30px;
    size: A4;
    margin: 0;
}
p {
    font-weight: bold;
}

.invoice {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px
}

.invoice header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #3989c6
}

.invoice .company-details {
    text-align: right
}

.invoice .company-details .name {
    margin-top: 0;
    margin-bottom: 0
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

.invoice .invoice-details {
    text-align: right
}

.invoice .invoice-details .invoice-id {
    margin-top: 0;
    color: #3989c6
}

.invoice main {
    padding-bottom: 50px
}

.invoice main .thanks {
    margin-top: -100px;
    font-size: 2em;
    margin-bottom: 50px
}

.invoice main .notices {
    padding-left: 6px;
    border-left: 6px solid #3989c6
}

.invoice main .notices .notice {
    font-size: 1.5em
}

.invoice table {
    width: 100%;
    border:solid 2px;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
    table-layout:fixed;
}

.invoice table td,.invoice table th {
    padding: 15px;
    border-bottom: 1px solid #fff;
}

.invoice table th {
    /* white-space: nowrap; */
    font-weight: 400;
    overflow: hidden;
    font-size: 15px;
    table-layout:fixed;


}

.invoice table td h3 {
    margin: 0;
    overflow: hidden;
    font-weight: 400;
    max-height: 0.5px;
    color: #3989c6;
    font-size: 1.5em
}

.invoice table .qty,.invoice table .total,.invoice table .unit {
    text-align: left;
    font-size: 1.5em
}

.invoice table .no {
    color: #fff;
    font-size: 1.6em;
    /* background: #3989c6 */
}

.invoice table .unit {
    background: #ddd
}

.invoice table .total {
    background: #3989c6;
    color: #fff
}

.invoice table tbody tr:last-child td {
    border: none
}

.invoice table tfoot td {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: center;
    padding: 10px 20px;
    font-size: 1.5em;
    border-top: 1px solid #aaa
}

.invoice table tfoot tr:first-child td {
    border-top: none
}

.invoice table tfoot tr:last-child td {
    color: #3989c6;
    font-size: 1.4em;
    border-top: 1px solid #3989c6
}

.invoice table tfoot tr td:first-child {
    border: none;
}

.invoice footer {
    width: 100%;
    text-align: center;
    font-weight:bold;
    color: black;
    border-top: 1px solid #3989c6;
}
* {
  box-sizing: border-box;
}

.row {
  margin-left:-5px;
  margin-right:-5px;
}
  
.column {
  float: left;
  width: 50%;
  padding: 5px;
  margin-left:2px;
  margin-right:-10px;
}

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}

.table {
  border-collapse: collapse;
  border:solid 2px;
  width: 99%;
  max-width: 99%;
}
td {
  max-width:1px;
  vertical-align: top;
  text-align: center;
  max-height: 0.5px;
  padding: 0;
  border:solid 2px;
  overflow-x: scroll;
  text-overflow: ellipsis;
  font-weight:bold;
}
th{
  vertical-align: top;
  text-align: center;
  max-height: 0.5px;
  padding: 0;
  border:solid 2px;
  overflow-x: scroll;
  text-overflow: ellipsis;
  border:solid 2px;
  font-weight:bold;
}

tr:nth-child(even) {
  background-color:#7CB9E8;
  font-weight: bold;
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
    text-align: left;
    outline: none;
}
.csssolution{
    resize: none; 
    border:solid 2px;
    font-weight: bold;
    overflow:hidden;
    margin: 1px;
    padding: 1px;
    min-width: 99%;
    min-height: 150px;
    text-align: left;
    outline: none;
}
@media screen {
    #invoice{
        padding: 30px;
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
        width: 70em;
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
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        /* white-space: nowrap; */
        font-weight: 400;
        font-size: 14px;
        table-layout:fixed;
        overflow: hidden;

    }

    .invoice table td h3 {
        margin: 0;
        overflow: hidden;
        font-weight: 400;
        color: #3989c6;
        font-size: 1.5em;
        overflow: hidden;
    }

    .invoice table .qty,.invoice table .total,.invoice table .unit {
        text-align: right;
        font-size: 1.5em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #3989c6;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none;
        page-break-after: auto;
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.5em;
        border-top: 1px solid #aaa
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
    td {
        vertical-align: top;
        max-width:1px;
        text-align: center;
        max-height: 0.5px;
        padding: 0;
        border:solid 2px;
        overflow-x: scroll;
        font-weight:bold;
        text-overflow: ellipsis;
    }
    th{
        vertical-align: top;
        text-align: center;
        max-height: 0.5px;
        padding: 0;
        border:solid 2px;
        overflow-x: scroll;
        text-overflow: ellipsis;
        font-weight:bold;
        border:solid 2px;
    }
    p {
        font-weight: bold;
    }

    .invoice {
        position: relative;
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

    .invoice .company-details {
        text-align: right
        
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .contacts {
        margin-bottom: 20px
        /* border: solid #000 !important; */

    }

    .invoice .invoice-to {
        /* border: solid #000 !important; */
        text-align: left
    }

    .invoice .invoice-to .to {
        /* border: solid #000 !important; */
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right
    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }

    .invoice main .notices .notice {
        font-size: 1.5em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table td,.invoice table th {
        padding: 15px;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        /* white-space: nowrap; */
        overflow: hidden;
        font-weight: 400;
        font-size: 14px;
        table-layout:fixed;

    }

    .invoice table td h3 {
        margin: 0;
        overflow: hidden;
        font-weight: 400;
        color: #3989c6;
        font-size: 1.5em
    }

    .invoice table .qty,.invoice table .total,.invoice table .unit {
        text-align: right;
        font-size: 1.5em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #3989c6;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none;
        page-break-after: auto;
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.5em;
        border-top: 1px solid #aaa
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        overflow: hidden;
        font-size: 1.4em;
        border-top: 1px solid #3989c6;
        page-break-after: auto;
    }

    .invoice table tfoot tr td:first-child {
        border: none
    }
    .column {
    float: left;
    width: 50%;
    padding: 5px;
    margin-left:2px;
    margin-right:-10px;
    /* border:solid 1px; */
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
}
@media print {
    
    #invoice{
        padding: 30px;
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
        min-width: 99%;
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
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        /* white-space: nowrap; */
        overflow: hidden;
        font-weight: 400;
        font-size: 14px;
        table-layout:fixed;

    }

    .invoice table td h3 {
        margin: 0;
        overflow: hidden;
        font-weight: 400;
        color: #3989c6;
        font-size: 1.5em
    }

    .invoice table .qty,.invoice table .total,.invoice table .unit {
        text-align: right;
        font-size: 1.5em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #3989c6;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none;
        page-break-after: auto;
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.5em;
        border-top: 1px solid #aaa
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        overflow: hidden;
        font-size: 1.4em;
        border-top: 1px solid #3989c6;
        page-break-after: auto;
    }

    .invoice table tfoot tr td:first-child {
        border: none
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
    td {
        vertical-align: top;
        max-width:1px;
        text-align: center;
        max-height: 0.5px;
        padding: 0;
        border:solid 2px;
        overflow-x: scroll;
        font-weight:bold;
        text-overflow: ellipsis;
    }
    th{
    vertical-align: top;
    text-align: center;
    max-height: 0.5px;
    padding: 0;
    border:solid 2px;
    overflow-x: scroll;
    text-overflow: ellipsis;
    font-weight:bold;
    border:solid 2px;
    }
        p {
        font-weight: bold;
    }

    .invoice {
        position: relative;
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

    .invoice .company-details {
        text-align: right
        
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .contacts {
        margin-bottom: 20px
        /* border: solid #000 !important; */

    }

    .invoice .invoice-to {
        /* border: solid #000 !important; */
        text-align: left
    }

    .invoice .invoice-to .to {
        /* border: solid #000 !important; */
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right
    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }

    .invoice main .notices .notice {
        font-size: 1.5em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table td,.invoice table th {
        padding: 15px;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        /* white-space: nowrap; */
        font-weight: 400;
        overflow: hidden;
        font-size: 14px;
        table-layout:fixed;

    }

    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        overflow: hidden;
        color: #3989c6;
        font-size: 1.5em
    }

    .invoice table .qty,.invoice table .total,.invoice table .unit {
        text-align: right;
        font-size: 1.5em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #3989c6;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none;
        page-break-after: auto;
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.5em;
        border-top: 1px solid #aaa
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
    .column {
    float: left;
    width: 50%;
    padding: 5px;
    margin-left:2px;
    margin-right:-10px;
    /* border:solid 1px; */
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
                            <span style="float:right;">
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
                            <textarea rows="8" cols="25" class="textareacss" readonly>{{$detail[0]->prio_level}}  </textarea>
                        </div>
                </div>
                @php 
                    $linkRequester = "http://localhost:8000/legality-qrcode-requester/{$detail[0]->ireq_id}"; 
                    $linkIctManager = "http://localhost:8000/legality-qrcode-ict-manager/{$detail[0]->ireq_id}"; 
                    $linkHigherLevel = "http://localhost:8000/legality-qrcode-higher-level/{$detail[0]->ireq_id}"; 
                @endphp

                <div class="row">
                    <div class="column">
                        <table> <p style="font-size:16px;"> II. Requester / Reported By : </p>
                        <tr>
                            <th>{{$detail[0]->ireq_requestor}}</th>
                            <th>{{$detail[0]->div_name}}</th>
                            <th rowspan="2" style="border:solid 2px;font-weight:bold;">{!! QrCode::errorCorrection('M')->size(100)->generate($linkRequester); !!}</th>
                        </tr>
                        <tr>
                            <td style="border:solid 2px;overflow: hidden;height:1px;">Name</td>
                            <td style="border:solid 2px;overflow: hidden;">Divison</td>
                        </tr>
                        </table>
                    </div>
                    <div class="column">
                        <table> <p style="font-size:16px;"> Approved By : (For new installation/software loan) </p>
                            @if ($detail[0]->date_approver1)
                            <tr>
                                <th>{{$detail[0]->usr_fullname}}</th>
                                <th>Manager {{$detail[0]->div_name}}</th>
                                 @if($detail[0]->status == "RA1")
                                    <th rowspan="2" style="border:solid 2px;font-weight:bold;">Rejected on {{$detail[0]->date_approver1}} and verified by the system</th>
                                 @else
                                    <th rowspan="2" style="border:solid 2px;font-weight:bold;">{!! QrCode::errorCorrection('M')->size(100)->generate($linkHigherLevel); !!}</th>
                                 @endif
                            </tr>
                            @else
                            <tr>
                                <th style="height:87px;"></ths>
                                <th></th>
                                <th rowspan="2" style="border:solid 2px;"></th>
                            </tr>
                            @endif
                            <tr>
                                <td style="border:solid 2px;height:1px;overflow: hidden;">Name</td>
                                <td style="border:solid 2px;overflow: hidden;">Position</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row contacts">
                  <div class="col invoice-to"><br>
                    <div class="text-gray-light"> <p style="font-size:16px;"> III. IT Use Only</p> </div>
                <div class="row">
                  <br> 
                    <table> <p style="font-size:16px;"> Approved By :   (Note : Sr. Manager approval needed for new equipment/software/tools)</p>
                        @if($detail[0]->date_approver2)
                        <tr>
                            <th>Arifin Tahir</th>
                            <th>ICT Manager</th>
                            @if($detail[0]->status=='RA2')
                             <th rowspan="2" style="border:solid 2px;font-weight:bold;">Rejected on {{$detail[0]->date_approver2}} and verified by the system</th>
                            @else
                             <th rowspan="2" style="border:solid 2px;font-weight:bold;">{!! QrCode::errorCorrection('M')->size(100)->generate($linkIctManager); !!}</th>
                            @endif
                            <th></th>
                        </tr>
                      @else
                      <tr>
                            <th height="50"></th>
                            <th></th>
                            <th rowspan="2" style="border:solid 2px;"></th>
                            <th></th>
                      </tr>
                      @endif
                      <tr>
                        <td style="border:solid 2px;overflow: hidden;">Name</td>
                        <td style="border:solid 2px;overflow: hidden;">Position</td>
                        <td style="border:solid 2px;height:1px;overflow: hidden;">Remarks(Including OE if required)</td>
                      </tr>
                    </table>
                </div>
                <br>
                <div class="row">
                  <br> 
                    <table>
                      <tr> 
                        <th>{{$detail[0]->ireq_verificator}}</th>
                        <th>{{$detail[0]->ireq_assigned_to}}</th>
                        <th>{{$detail[0]->date_assigned}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                      <tr>
                        <td style="border:solid 2px;height:1px;overflow: hidden;">Received By</td>
                        <td style="border:solid 2px;overflow: hidden;">Assigned To</td>
                        <td style="border:solid 2px;overflow: hidden;">Date Assigned</td>
                        <td style="border:solid 2px;overflow: hidden;">Date Completed</td>
                        <td style="border:solid 2px;overflow: hidden;">Manpower Effort</td>
                        <td style="border:solid 2px;overflow: hidden;">Costs</td>
                        <td style="border:solid 2px;overflow: hidden;">Category *(A/I/N/S)</td>
                        <td style="border:solid 2px;overflow: hidden;">Problem Area</td>
                      </tr>
                    </table>
                </div>
                  </div>
                </div>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="address" style="font-weight:bold;">Solution/Action Implemented/Analysis</div>
                            <textarea cols="100" class="csssolution" readonly> </textarea>
                    </div>
                    
                    <div class="col invoice-to">
                      <div class="row">
                        <div class="address" style="font-weight:bold;">*(A)ccident/(I)ncident/I(N)stall/Assi(S)t</div>
                          <table >
                            <tr> 
                                <th style="height:70px;"> </th>
                            </tr>
                            <tr>
                                <td style="height:30px;border:solid 2px;overflow: hidden;">Further Action By</td>
                            </tr>
                          </table>
                          </div>
                    </div>
                </div>
                <!-- <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light"><p> IV. User Acceptance </p></div>
                          <br> 
                            <table>
                            <tr> 
                                <th style="height:50px; width:200px;"></th>
                                <th style="width:200px; border:solid 2px;"></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td style="border:solid 2px;height:0.5px;">User/Signature</td>
                                <td style="border:solid 2px;">Date & Time</td>
                                <td style="text-align: center; border:solid 2px; ">Remarks</td>
                            </tr>
                            </table>
                    </div>
                </div> -->
            </main>
            <footer>
                <strong>This PDF was created on a system and is valid without the signature and seal.</strong>
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
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