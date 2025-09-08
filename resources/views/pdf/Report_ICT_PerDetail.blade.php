<!DOCTYPE html>
<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* ===== Global A4 Page Settings ===== */
        html, body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            font-family: Arial, sans-serif;
        }

        #invoice {
            width: 100%;
            height: 100%;
            padding: 10mm;
            box-sizing: border-box;
            overflow: hidden;
        }

        .invoice {
            height: 100%;
            page-break-inside: avoid;
        }

        /* ===== Section spacing ===== */
        .wrap {
            max-height: 230mm;
            overflow: hidden;
            display: table;
            width: 100%;
            margin-top: 15px;
        }

        .cell-wrap {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding-right: 5px;
        }

        /* ===== Table styling ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
            margin-bottom: 10px;
        }

        table th, table td {
            border: 1px solid #000;
            font-size: 10pt;
            padding: 3px;
            text-align: center;
            word-wrap: break-word;
        }

        table th {
            font-weight: bold;
            background-color: #7CB9E8;
        }

        /* ===== Textarea / Remarks ===== */
        .textareacss {
            max-height: 120px;
            overflow: hidden;
            border: 1px solid #000;
            padding: 5px;
            font-weight: bold;
            width: 100%;
            box-sizing: border-box;
            background-color: #f4f4f4;
        }

        /* ===== QR Code ===== */
        .qrcode {
            width: 70px;
            height: 70px;
        }

        /* ===== Section headers ===== */
        .section-header {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 5px;
        }

        /* ===== Paragraph styling ===== */
        p {
            margin: 0;
            font-weight: bold;
        }

        /* ===== Space between tables ===== */
        .wrap + .wrap {
            margin-top: 15px;
        }

        /* ===== Fit content inside cells ===== */
        td, th {
            overflow: hidden;
        }

        /* ===== Optional: smaller font for long tables ===== */
        .small-font {
            font-size: 9pt;
        }

    </style>
</head>
<body>
<div id="invoice">
    <div class="invoice">
        <header>
            <div class="row align-items-center">
                <!-- Bagian kiri: logo + teks -->
                <div class="col-6" style="display: flex; align-items: center;">
                    <img src="{{ asset('assets/layout/images/logo-emp.png') }}" width="150" alt="Logo">
                    <span style="font-size: 18pt; font-weight: bold; margin-left: 10px;">
                        ICT Helpdesk System
                    </span>
                </div>
                <!-- Bagian kanan: judul + no request -->
                <div class="col-6 text-right">
                    <h2 style="margin: 0; font-size: 20pt;">ICT SERVICE REQUEST</h2>
                    <p style="margin: 0; font-size: 12pt;">No.request: {{ $detail[0]->ireq_no }}</p>
                </div>
            </div>
            <!-- Garis pembatas horizontal -->
            <hr style="border: 2px solid #000; margin-top: 10px; margin-bottom: 20px;">
        </header>


        <main>
            <!-- I. Description -->
            <div class="row mb-2">
                <div class="col-8">
                    <p>I. Description Of Request / Problem Experienced</p>
                    <div class="textareacss">
                        @php $no = 1; @endphp
                        @foreach($detail as $d)
                            @if($no > 3)
                                ... @break
                            @endif
                            {{ $no++ }}. {{ $d->ireq_remark }}<br>
                        @endforeach
                    </div>
                </div>
                <div class="col-4">
                    <p>Priority Level</p>
                    <textarea class="textareacss" readonly>{{ $detail[0]->prio_level }}</textarea>
                </div>
            </div>

            @php
                $appname = env('APP_URL');
                $linkRequester = "{$appname}/check/requestor/{$link->link_id}";
                $linkIctManager = "{$appname}/check/ictmanager/{$link->link_id}";
                $linkHigherLevel = "{$appname}/check/higherlevel/{$link->link_id}";
            @endphp

            <!-- II. Requester -->
            <div class="wrap mb-2">
                <div class="cell-wrap">
                    <table>
                        <tr>
                            <th colspan="3">II. Requester / Reported By</th>
                        </tr>
                        <tr>
                            <td>{{ $detail[0]->ireq_requestor }}</td>
                            <td>{{ $detail[0]->division_user ?? '-' }}</td>
                            <td rowspan="2">
                                {!! QrCode::size(70)->generate($linkRequester) !!}
                                <br>{{ formatDate($detail[0]->ireq_date, 'd M Y H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>Division</td>
                        </tr>
                    </table>
                </div>

                <!-- II. Approved By Supervisor -->
                <div class="cell-wrap">
                    <table>
                        <tr>
                            <th colspan="3">Approved By (Supervisor)</th>
                        </tr>
                        @if($detail[0]->ireq_approver1_date || $detail[0]->status == 'NA1')
                        <tr>
                            <td>{{ $detail[0]->ireq_spv ?? 'Tanpa Supervisor' }}</td>
                            <td>{{ $detail[0]->spv_job_title ?? '-' }}</td>
                            <td rowspan="2">
                                {!! QrCode::size(70)->generate($linkHigherLevel) !!}
                                <br>
                                @if($detail[0]->status == "RA1")
                                    <strong>Rejected</strong> on {{ formatDate($detail[0]->ireq_approver1_date, 'd M Y H:i') }}
                                @elseif($detail[0]->status == "NA1")
                                    Waiting Approval
                                @else
                                    <strong>Approved</strong> on {{ formatDate($detail[0]->ireq_approver1_date, 'd M Y H:i') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>Position</td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="3">This request is not yet in higher level approval stage</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- III. ICT Manager Approval -->
            <div class="wrap mb-2">
                <table>
                    <tr>
                        <th colspan="4">III. IT Use Only (ICT Manager Approval)</th>
                    </tr>
                    @if($detail[0]->ireq_approver2_date || $detail[0]->status == 'NA2')
                    <tr>
                        <td>{{ $detail[0]->manager_ict_name ?? 'Tanpa Manager' }}</td>
                        <td>{{ $detail[0]->manager_job_title ?? '-' }}</td>
                        <td rowspan="2">
                            {!! QrCode::size(70)->generate($linkIctManager) !!}
                            <br>
                            @if($detail[0]->status=='RA2')
                                <strong>Rejected</strong> On {{ formatDate($detail[0]->ireq_approver2_date, 'd M Y H:i') }}
                            @elseif($detail[0]->status=='NA2')
                                Waiting Approval
                            @else
                                <strong>Approved</strong> On {{ formatDate($detail[0]->ireq_approver2_date, 'd M Y H:i') }}
                            @endif
                        </td>
                        <td>{{ $detail[0]->ireq_approver2_remark ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>Position</td>
                        <td>Remarks</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="4">This request is not yet in ICT Manager approval stage</td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- IV. Verification & Assignment -->
            <div class="wrap" style="margin-top:20px;">
                <div class="cell-wrap full-width">
                    <p class="section-header">IV. Request Review</p>
                    <table>
                        @if($detail[0]->ireq_verificator)
                            <tr>
                                <th width="200px">{{$detail[0]->ireq_verificator}}</th>
                                <th>{{$detail[0]->ireq_verificator_remark}}</th>
                                <th width="200px">{{$detail[0]->ireq_loc}}</th>
                            </tr>
                            <tr>
                                <td width="200px">Received By</td>
                                <td>Remark</td>
                                <td width="200px">Problem Area</td>
                            </tr>
                        @else
                            <tr>
                                <th colspan="3" style="font-weight:bold; text-align:center;">
                                    Waiting for Verification
                                </th>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="wrap" style="margin-top:15px;">
                <div class="cell-wrap full-width">
                    <p class="section-header">V. Assignment / Task Detail</p>
                    <table>
                        <thead>
                            <tr>
                                <th width="90px">No Detail</th>
                                <th>Assigned To</th>
                                <th width="150px">Date Assigned</th>
                                <th width="150px">Date Completed</th>
                                <th>Remark</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($detail[0]->ireq_assigned_date)
                                @foreach($detail as $det)
                                <tr>
                                    <td width="90px">{{$det->ireqd_id}}</td>
                                    <td>{{$det->ireq_assigned_to}}</td>
                                    <td width="150px">{{formatDate($det->ireq_assigned_date, 'd M y')}}</td>
                                    <td width="150px">{{formatDate($det->ireq_date_closing, 'd M y')}}</td>
                                    <td>{{$det->assigned_remark_detail}}</td>
                                    <td>{{$det->ireq_status}}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" style="font-weight:bold; text-align:center;">
                                        This request is not yet in the assignment stage
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
