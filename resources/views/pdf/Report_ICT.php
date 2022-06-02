<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<title> TES</title>
<style>
#invoice{
    padding: 30px;
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
    font-size: 1.2em
}

/* .invoice table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px
}

.invoice table td,.invoice table th {
    padding: 15px;
    background: #eee;
    border-bottom: 1px solid #fff
}

.invoice table th {
    white-space: nowrap;
    font-weight: 400;
    font-size: 16px
}

.invoice table td h3 {
    margin: 0;
    font-weight: 400;
    color: #3989c6;
    font-size: 1.2em
}

.invoice table .qty,.invoice table .total,.invoice table .unit {
    text-align: right;
    font-size: 1.2em
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
    border: none
}

.invoice table tfoot td {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: right;
    padding: 10px 20px;
    font-size: 1.2em;
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
    border: none
} */

.invoice footer {
    width: 100%;
    text-align: center;
    color: #777;
    border-top: 1px solid #aaa;
    padding: 8px 0
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
}

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}
th, td {
  text-align: left;
  padding: 16px;
  border: 1px solid #ddd;
  text-align: center;
  border:solid 1px;

}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

@media print {
    .invoice {
        font-size: 12px!important;
        overflow: hidden!important
    }

    .invoice footer {
        position: absolute;
        bottom: 10px;
        page-break-after: always
    }

    .invoice>div:last-child {
        page-break-before: always
    }
    .table {
        border: solid #000 !important;
        border-width: 1px 0 0 1px !important;
    }
    th, td {
        /* border: solid #000 !important;
        border-width: 0 1px 1px 0 !important; */
        border:solid 1px;

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
    font-size: 1.2em
}

/* .invoice table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px
}

.invoice table td,.invoice table th {
    padding: 15px;
    background: #eee;
    border-bottom: 1px solid #fff
}

.invoice table th {
    white-space: nowrap;
    font-weight: 400;
    font-size: 16px
}

.invoice table td h3 {
    margin: 0;
    font-weight: 400;
    color: #3989c6;
    font-size: 1.2em
}

.invoice table .qty,.invoice table .total,.invoice table .unit {
    text-align: right;
    font-size: 1.2em
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
    border: none
}

.invoice table tfoot td {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: right;
    padding: 10px 20px;
    font-size: 1.2em;
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
    border: none
} */

.invoice footer {
    width: 100%;
    text-align: center;
    color: #777;
    border-top: 1px solid #aaa;
    padding: 8px 0
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
}

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
  border:solid 1px;

}
th, td {
  text-align: left;
  padding: 16px;
  border: 1px solid #ddd;
  text-align: center;
  border:solid 1px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
  border:solid 1px;
}
}
</style>
<div id="invoice">

    <div class="toolbar hidden-print">
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
                            ICT SERVICE REQUEST 
                            </a>
                        </h2>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light"><p> Please submit to Helpdesk Supervisor (27th Floor) Or  Call Hotline Number Extension 511 </p></div>
                    </div>
                </div>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="address" style="font-weight:bold">I. Description Of Request/Problem Experienced</div>
                            <textarea rows="8" cols="100" style="resize: none; border:solid 1px;" disabled> 
                            @foreach($detail as $d)
                               {{ $d->ireq_remark }}
                            @endforeach
                            </textarea>
                        </div>
                        <div class="col invoice-to">
                          <div class="address" style="font-weight:bold">Priority Level</div>
                            <textarea rows="8" cols="25" style="resize: none; border:solid 1px;" disabled> {{$detail[0]->prio_level}}  </textarea>
                        </div>
                </div>
                <div class="row">
                    <div class="column">
                        <table> <p> II. Requester / Reported By : </p>
                        <tr>
                            <th style="border:solid 1px;">{{$detail[0]->div_name}}</th>
                            <th style="border:solid 1px;">{{$detail[0]->ireq_requestor}}</th>
                            <th style="border:solid 1px;">{{$detail[0]->date_request}}</th>
                            <th style="border:solid 1px;">{{$detail[0]->time_request}}</th>
                        </tr>
                        <tr>
                            <td style="border:solid 1px;">Divison</td>
                            <td style="border:solid 1px;">Name</td>
                            <td style="border:solid 1px;">Date</td>
                            <td style="border:solid 1px;">Time</td>
                        </tr>
                        </table>
                    </div>
                    <div class="column">
                        <table> <p> Approved By : (For new installation/software loan) </p>
                            <tr>
                                <th style="border:solid 1px;">{{$detail[0]->usr_fullname}}</th>
                                <th style="border:solid 1px;">Manager {{$detail[0]->div_name}}</th>
                                <th style="border:solid 1px;">{{$detail[0]->date_approver1}}</th>
                                <th style="border:solid 1px;"></th>
                            </tr>
                            <tr>
                                <td style="border:solid 1px;">Name</td>
                                <td style="border:solid 1px;">Position</td>
                                <td style="border:solid 1px;">Date</td>
                                <td style="border:solid 1px;">Signature</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row contacts">
                  <div class="col invoice-to"><br>
                    <div class="text-gray-light"> <p> III. IT Use Only</p> </div>
                <div class="row">
                  <br> 
                    <table> <p> Approved By :   (Note : Sr. Manager approval needed for new equipment/software/tools)</p>
                      <tr> 
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                      </tr>
                      <tr>
                        <td style="border:solid 1px;">Name</td>
                        <td style="border:solid 1px;">Position</td>
                        <td style="border:solid 1px;">Date</td>
                        <td style="border:solid 1px;">Signature</td>
                        <td style="border:solid 1px;">Remarks(Including OE if required)</td>
                      </tr>
                    </table>
                </div>
                <br>
                <div class="row">
                  <br> 
                    <table>
                      <tr> 
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">{{$detail[0]->date_assigned}}</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                        <th style="border:solid 1px;">tes</th>
                      </tr>
                      <tr>
                        <td style="border:solid 1px;">Received By</td>
                        <td style="border:solid 1px;">Assigned To</td>
                        <td style="border:solid 1px;">Date Assigned</td>
                        <td style="border:solid 1px;">Date Completed</td>
                        <td style="border:solid 1px;">Manpower Effort</td>
                        <td style="border:solid 1px;">Costs</td>
                        <td style="border:solid 1px;">Category *(A/I/N/S)</td>
                        <td style="border:solid 1px;">Problem Area</td>
                      </tr>
                    </table>
                </div>
                  </div>
                </div>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="address" style="font-weight:bold">Solution/Action Implemented/Analysis</div>
                            <textarea rows="10" cols="120" style="resize: none; border:solid 1px;" disabled> </textarea>
                    </div>
                    <div class="col invoice-to">
                      <br>
                      <div class="row">
                        <div class="address" style="font-weight:bold"></div>
                          <table id="tes" style="table-layout:fixed;">
                            <tr> 
                                <th style="height:132px; border:solid 1px; "> Adhitya Trinanda Saputro </th>
                            </tr>
                            <tr>
                                <td style="height:132px;border:solid 1px;">Further Action By</td>
                            </tr>
                          </table>
                          </div>
                    </div>
                </div>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light"><p> IV. User Acceptance </p></div>
                          <br> 
                            <table>
                            <tr> 
                                <th style="height:100px; width:200px; border:solid 1px;">tes</th>
                                <th style="height:100px; width:200px; border:solid 1px;">tess</th>
                                <th style="border:solid 1px;"></th>
                            </tr>
                            <tr>
                                <td style="border:solid 1px;">User/Signature</td>
                                <td style="border:solid 1px;">Date & Time</td>
                                <td style="text-align: center; border:solid 1px; ">Remarks</td>
                            </tr>
                            </table>
                    </div>
                </div>
            </main>
            <footer>
                This pdf was created on a system and is valid without the signature and seal.
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